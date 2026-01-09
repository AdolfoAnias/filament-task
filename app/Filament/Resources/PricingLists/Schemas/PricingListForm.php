<?php

namespace App\Filament\Resources\PricingLists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PricingListForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('apply_to')
                    ->required(),
            ]);
    }
}
