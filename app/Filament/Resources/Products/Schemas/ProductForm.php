<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('app.name'))
                    ->required(),
                TextInput::make('price')
                    ->label(__('app.price'))
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('qty')
                    ->label(__('app.qty'))
                    ->required()
                    ->numeric(),
            ]);
    }
}
