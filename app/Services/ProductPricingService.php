<?php

namespace App\Services;

use App\Models\Product;
use App\Models\PricingRule;
use Illuminate\Support\Collection;

class ProductPricingService
{
    public static function calculate(
        int $productId,
        callable $set,
        callable $get,
        string $prefix = 'new_item',
        $clientId = null
    ): void {
        if (!$productId) {
            $set("{$prefix}.price", null);
            $set("{$prefix}.subtotal", null);
            return;
        }

        $product = Product::find($productId);
        if (!$product) return;

        $quantity = $get("{$prefix}.quantity") ?? 1;

        $clientId = $clientId ?? $get('../../client_id') ?? $get('../client_id') ?? $get('client_id');

        // Reglas para cliente (prioridad máxima)
        $clientRules = self::getClientRules($clientId);

        if ($clientRules->isNotEmpty()) {
            $price = self::applyClientRules($product, $clientRules);
            $subtotal = $quantity * $price;
            $set("{$prefix}.price", $price);
            $set("{$prefix}.subtotal", $subtotal);
            return;
        }

        // Reglas para producto
        [$price, $subtotal] = self::applyProductRules($product, $quantity);
        $set("{$prefix}.price", $price);
        $set("{$prefix}.subtotal", $subtotal);
    }

    private static function getClientRules(?int $clientId): Collection
    {
        if ($clientId) {
            return PricingRule::where('apply_to', 'cliente')
                ->where('client_id', $clientId)
                ->get();
        }
        return PricingRule::where('apply_to', 'cliente')
            ->whereNull('client_id')
            ->get();
    }

    private static function applyClientRules(Product $product, Collection $rules): float
    {
        $price = $product->price ?? 0;
        foreach ($rules as $rule) {
            if ($rule->type_rule === 'precio_fijo') return $rule->value;
            if ($rule->type_rule === 'descuento' &&
                ($rule->product_id === null || $rule->product_id == $product->id)) {
                $price -= ($price * $rule->value) / 100;
            }
        }
        return $price;
    }

    private static function applyProductRules(Product $product, int $quantity): array
    {
        $specificRule = PricingRule::where('apply_to', 'producto')
        ->where('product_id', $product->id)
        ->first();

        $generalRules = collect();

        if (!$specificRule) {
        $generalRules = PricingRule::where('apply_to', 'producto')
                    ->whereNull('product_id')
                    ->get();
        }

        $price = $product->price;

        if ($specificRule && $specificRule->type_rule) {
            if ($specificRule->min_quantity !== null && $quantity < $specificRule->min_quantity) {
                $price = $product->price;
            } else {
                $price = self::applySingleRule($price, $specificRule);
            }
        } else {
            foreach ($generalRules as $rule) {
                if ($rule->min_quantity === null || $quantity >= $rule->min_quantity) {
                    $price = self::applySingleRule($price, $rule);
                }
            }
        }

        return [$price, $quantity * $price];
    }

    private static function applySingleRule(float $price, $rule): float
    {
        return match($rule->type_rule) {
            'precio_fijo' => $rule->value,
            'descuento' => $price - (($price * $rule->value) / 100),
            default => $price
        };
    }

    public static function recalculateAllItems(
        callable $set,
        callable $get,
        string $itemsField = 'items'
    ): void {
        $items = $get($itemsField) ?? [];
        $clientId = $get('client_id');

        foreach ($items as $index => $item) {
            if (!isset($item['product_id'])) continue;

            $prefix = "{$itemsField}.{$index}";

            self::calculate(
                $item['product_id'],
                $set,
                $get,
                $prefix,
                $clientId
            );
        }
    }
}
