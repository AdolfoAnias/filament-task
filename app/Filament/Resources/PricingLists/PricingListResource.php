<?php

namespace App\Filament\Resources\PricingLists;

use App\Filament\Resources\PricingLists\Pages\CreatePricingList;
use App\Filament\Resources\PricingLists\Pages\EditPricingList;
use App\Filament\Resources\PricingLists\Pages\ListPricingLists;
use App\Filament\Resources\PricingLists\Pages\ViewPricingList;
use App\Filament\Resources\PricingLists\Schemas\PricingListForm;
use App\Filament\Resources\PricingLists\Schemas\PricingListInfolist;
use App\Filament\Resources\PricingLists\Tables\PricingListsTable;
use App\Models\PricingList;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PricingListResource extends Resource
{
    protected static ?string $model = PricingList::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Pricing List';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return PricingListForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PricingListInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PricingListsTable::configure($table);
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
            'index' => ListPricingLists::route('/'),
            'create' => CreatePricingList::route('/create'),
            'view' => ViewPricingList::route('/{record}'),
            'edit' => EditPricingList::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.pricing_list');
    }

}
