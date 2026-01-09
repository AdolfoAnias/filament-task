<?php

namespace App\Filament\Resources\PricingLists\Pages;

use App\Filament\Resources\PricingLists\PricingListResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPricingList extends ViewRecord
{
    protected static string $resource = PricingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
