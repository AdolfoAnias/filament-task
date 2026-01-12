<?php

namespace App\Filament\Resources\SaleOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Get;
use App\Services\ProductPricingService;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Hidden;

class SaleOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label(__('app.code'))
                    ->required(),
                DatePicker::make('order_date')
                    ->label(__('app.order_date'))
                    ->default(now())
                    ->required(),
                Select::make('client_id')
                    ->label(__('app.client'))
                    ->relationship('client', 'name')
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $productId = $get('new_item.product_id');

                        if ($productId) {
                            ProductPricingService::calculate($productId, $set, $get, 'new_item');
                        }

                        //ProductPricingService::recalculateAllItems($set, $get, 'items');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('status')
                    ->label(__('app.status'))
                    ->default('Pendiente')
                    ->extraInputAttributes([
                        'class' => 'bg-gray-100 text-gray-200'
                    ])
                    ->readonly(),

                Section::make(__('app.new_product'))
                ->schema([
                    Select::make('new_item.product_id')
                        ->label(__('app.product'))
                        ->options(\App\Models\Product::pluck('name', 'id'))
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(fn ($state, $set, $get) =>
                            ProductPricingService::calculate($state, $set, $get, 'new_item')
                        ),

                    TextInput::make('new_item.quantity')
                        ->label(__('app.quantity'))
                        ->numeric()
                        ->default(1)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $productId = $get('new_item.product_id');
                            ProductPricingService::calculate($productId, $set, $get, 'new_item');
                        }),

                    TextInput::make('new_item.price')
                        ->label(__('app.price'))
                        ->numeric()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $qty = $get('new_item.quantity') ?? 1;
                            $set('new_item.subtotal', $qty * $state);
                        }),

                    TextInput::make('new_item.subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                ])
                ->columns(4)
                ->columnSpanFull()
                ->footerActions([
                    Action::make('addItem')
                    ->label(__('app.add_new_product'))
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->action(function ($set, $get) {
                        $productId = $get('new_item.product_id');
                        $quantity = $get('new_item.quantity') ?? 1;
                        $price = $get('new_item.price') ?? 0;
                        $clientId = $get('client_id');

                        // Validación: cliente requerido PRIMERO
                        if (!$clientId) {
                            Notification::make()
                                ->title(__('app.error'))
                                ->body(__('app.select_client_first'))
                                ->danger()
                                ->send();
                            return;
                        }

                        // Validación: producto requerido
                        if (!$productId) {
                            Notification::make()
                                ->title(__('app.error'))
                                ->body(__('app.select_product_first'))
                                ->danger()
                                ->send();
                            return;
                        }

                        // Validación: cantidad mayor que 0
                        if ($quantity <= 0) {
                            Notification::make()
                                ->title(__('app.error'))
                                ->body(__('app.quantity_must_be_greater_than_zero'))
                                ->danger()
                                ->send();
                            return;
                        }

                        // Validación: precio mayor que 0
                        if ($price <= 0) {
                            Notification::make()
                                ->title(__('app.error'))
                                ->body(__('app.price_must_be_greater_than_zero'))
                                ->danger()
                                ->send();
                            return;
                        }

                        $newItem = [
                            'product_id' => $productId,
                            'quantity' => $quantity,
                            'price' => $price,
                            'subtotal' => $get('new_item.subtotal') ?? 0,
                        ];

                        $currentItems = $get('items') ?? [];
                        $currentItems[] = $newItem;
                        $set('items', $currentItems);

                        $set('new_item.product_id', null);
                        $set('new_item.quantity', 1);
                        $set('new_item.price', null);
                        $set('new_item.subtotal', null);

                        Notification::make()
                            ->title(__('app.success'))
                            ->body(__('app.product_added_successfully'))
                            ->success()
                            ->send();
                    }),

                ]),

                Hidden::make('items')
                    ->default([]),

                Placeholder::make('items_preview')
                    ->label(__('app.product_summary'))
                    ->content(function ($get) {
                        $items = $get('items') ?? [];

                        if (!count($items)) {
                            return __('app.no_products');
                        }

                        $html = '<table class="fi-ta min-w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="px-2 py-1 text-left font-semibold text-xs">Product</th>
                                            <th class="px-2 py-1 text-left font-semibold text-xs">Quantity</th>
                                            <th class="px-2 py-1 text-left font-semibold text-xs">Price</th>
                                            <th class="px-2 py-1 text-left font-semibold text-xs">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                        $totalGeneral = 0;

                        foreach ($items as $item) {
                            $productName = __('app.no_products');

                            if (isset($item['product_id'])) {
                                $product = \App\Models\Product::find($item['product_id']);
                                $productName = $product ? $product->name : __('app.no_products');
                            }

                            $subtotal = ($item['subtotal'] ?? 0);
                            $totalGeneral += $subtotal;

                            $html .= '<tr>
                                        <td class="px-2 py-1 text-left">'.$productName.'</td>
                                        <td class="px-2 py-1 text-left">'.($item['quantity'] ?? '').'</td>
                                        <td class="px-2 py-1 text-left">'.($item['price'] ?? '').'</td>
                                        <td class="px-2 py-1 text-left font-semibold">$'.number_format($subtotal, 2).'</td>
                                    </tr>';
                        }

                        $html .= '</tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 h-4">
                                        <td colspan="4" class="border-t-2 border-gray-200">&nbsp;</td>
                                    </tr>
                                    <tr class="bg-gray-50 border-t-2 border-gray-200">
                                        <td colspan="3" class="px-2 py-2 text-right font-bold text-lg">TOTAL:</td>
                                        <td class="px-2 py-2 text-left font-bold text-xl text-blue-600">$'.number_format($totalGeneral, 2).'</td>
                                    </tr>
                                </tfoot>
                                </table>';

                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->columnSpanFull()
                    ->live(),

            ]);
    }

}
