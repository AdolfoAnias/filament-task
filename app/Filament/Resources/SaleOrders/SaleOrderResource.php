<?php

namespace App\Filament\Resources\SaleOrders;

use App\Filament\Resources\SaleOrders\Pages\CreateSaleOrder;
use App\Filament\Resources\SaleOrders\Pages\EditSaleOrder;
use App\Filament\Resources\SaleOrders\Pages\ListSaleOrders;
use App\Filament\Resources\SaleOrders\Pages\ViewSaleOrder;
use App\Filament\Resources\SaleOrders\Schemas\SaleOrderForm;
use App\Filament\Resources\SaleOrders\Schemas\SaleOrderInfolist;
use App\Filament\Resources\SaleOrders\Tables\SaleOrdersTable;
use App\Models\SaleOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SaleOrderResource extends Resource
{
    protected static ?string $model = SaleOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Sale Order';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return SaleOrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SaleOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SaleOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSaleOrders::route('/'),
            'create' => CreateSaleOrder::route('/create'),
            'view' => ViewSaleOrder::route('/{record}'),
            'edit' => EditSaleOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.sale_orders');
    }

    protected function afterCreate(): void
    {
        $this->createSaleOrderItems();
    }

    protected function afterSave(): void
    {
        // Limpiar items anteriores
        $this->record->items()->delete();

        // Crear nuevos items
        $this->createSaleOrderItems();
    }

    private function createSaleOrderItems(): void
    {
        $items = $this->form->getState()['items'] ?? [];

        foreach ($items as $item) {
            SaleOrderItem::create([
                'sale_order_id' => $this->record->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }
    }





}
