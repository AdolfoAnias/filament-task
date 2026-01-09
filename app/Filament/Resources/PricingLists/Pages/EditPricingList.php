<?php

namespace App\Filament\Resources\PricingLists\Pages;

use App\Filament\Resources\PricingLists\PricingListResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPricingList extends EditRecord
{
    protected static string $resource = PricingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
