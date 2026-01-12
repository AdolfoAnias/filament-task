<?php

namespace App\Filament\Resources\SaleOrders\Pages;

use App\Filament\Resources\SaleOrders\SaleOrderResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\SaleOrderItems;

class CreateSaleOrder extends CreateRecord
{
    protected static string $resource = SaleOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /*
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Limpiar datos que no se guardan en sale_orders
        unset($data['new_item'], $data['items_preview']);

        // items se queda en $data['items'] para afterCreate()
        return $data;
    }
    */

    protected function afterCreate(): void
    {
        $formData = $this->form->getState();
        $items = $formData['items'] ?? [];

        foreach ($items as $item) {
            SaleOrderItems::create([
                'sale_order_id' => $this->record->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }
    }
}
