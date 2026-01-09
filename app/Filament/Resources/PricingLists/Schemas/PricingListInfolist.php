<?php

namespace App\Filament\Resources\PricingLists\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PricingListInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('apply_to'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
