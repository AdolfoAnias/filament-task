<?php

namespace App\Filament\Resources\PricingLists\Pages;

use App\Filament\Resources\PricingLists\PricingListResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPricingLists extends ListRecords
{
    protected static string $resource = PricingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
