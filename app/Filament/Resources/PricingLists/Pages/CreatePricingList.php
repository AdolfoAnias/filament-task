<?php

namespace App\Filament\Resources\PricingLists\Pages;

use App\Filament\Resources\PricingLists\PricingListResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePricingList extends CreateRecord
{
    protected static string $resource = PricingListResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
