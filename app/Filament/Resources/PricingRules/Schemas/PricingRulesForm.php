<?php

namespace App\Filament\Resources\PricingRules\Schemas; // Ajusta el namespace

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class PricingRulesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Select::make('apply_to')
                            ->label('Aplicar a')
                            ->options([
                                'cliente' => 'Cliente',
                                'producto' => 'Producto',
                            ])
                            ->default('cliente')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('apply_id', null);
                            }),

                        Select::make('client_id')
                            ->label('Cliente')
                            ->visible(fn (callable $get) => $get('apply_to') === 'cliente')
                            ->options(\App\Models\Client::pluck('name', 'id'))
                            ->searchable()
                            ->preload(),

                        Select::make('product_id')
                            ->label('Producto')
                            ->options(\App\Models\Product::pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ])
                    ->columnSpan(1),

                Grid::make(2)
                    ->schema([
                        Select::make('type_rule')
                            ->label('Tipo de regla')
                            ->options([
                                'precio_fijo' => 'Precio Fijo',
                                'descuento' => 'Descuento',
                            ])
                            ->default('precio_fijo')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('value', null);
                            }),

                        TextInput::make('value')
                            ->label(fn (callable $get): string =>
                                $get('type_rule') === 'precio_fijo'
                                    ? 'Precio Fijo'
                                    : 'Descuento'
                            )
                            ->numeric()
                            ->required(),
                    ])
                    ->columnSpan(1),

                Grid::make(3)
                    ->schema([
                        TextInput::make('min_quantity')
                            ->label('Cantidad mínima')
                            ->numeric()
                            ->placeholder('Opcional'),

                        /*
                        DatePicker::make('init_date')
                            ->label('Fecha inicio')
                            ->displayFormat('d/m/Y')
                            ->placeholder('Opcional'),

                        DatePicker::make('expired_date')
                            ->label('Fecha expiración')
                            ->displayFormat('d/m/Y')
                            ->placeholder('Opcional')
                            ->after('init_date'),
                        */
                    ])
                    ->columnSpan(1),

                Hidden::make('active')
                    ->default(true),
            ]);
    }
}
