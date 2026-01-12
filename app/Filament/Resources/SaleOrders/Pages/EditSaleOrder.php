<?php

namespace App\Filament\Resources\SaleOrders\Pages;

use App\Filament\Resources\SaleOrders\SaleOrderResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSaleOrder extends EditRecord
{
    protected static string $resource = SaleOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['new_item'], $data['items_preview']);
        return $data;
    }

    protected function afterSave(): void
    {
        // Limpiar items anteriores
        $this->record->items()->delete();

        // Crear nuevos items
        $items = $this->form->getState()['items'] ?? [];
        foreach ($items as $item) {
            \App\Models\SaleOrderItem::create([
                'sale_order_id' => $this->record->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }
    }
}
