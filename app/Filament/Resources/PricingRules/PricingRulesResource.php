<?php

namespace App\Filament\Resources\PricingRules;

use App\Filament\Resources\PricingRules\Pages\CreatePricingRules;
use App\Filament\Resources\PricingRules\Pages\EditPricingRules;
use App\Filament\Resources\PricingRules\Pages\ListPricingRules;
use App\Filament\Resources\PricingRules\Pages\ViewPricingRules;
use App\Filament\Resources\PricingRules\Schemas\PricingRulesForm;
use App\Filament\Resources\PricingRules\Schemas\PricingRulesInfolist;
use App\Filament\Resources\PricingRules\Tables\PricingRulesTable;
use App\Models\PricingRule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PricingRulesResource extends Resource
{
    protected static ?string $model = PricingRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Pricing Rules';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return PricingRulesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PricingRulesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PricingRulesTable::configure($table);
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
            'index' => ListPricingRules::route('/'),
            'create' => CreatePricingRules::route('/create'),
            'view' => ViewPricingRules::route('/{record}'),
            'edit' => EditPricingRules::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('app.pricing_rules');
    }

}
