<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('app.name'))
                    ->required(),
                TextInput::make('email')
                    ->label(__('app.email'))
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label(__('app.password'))
                    ->password()
                    ->required(),
            ]);
    }
}
