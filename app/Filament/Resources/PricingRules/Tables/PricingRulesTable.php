<?php

namespace App\Filament\Resources\PricingRules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PricingRulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apply_to')
                    ->label(__('app.apply_to'))
                    ->searchable(),
                TextColumn::make('client_id')
                    ->label(__('app.client'))
                    ->formatStateUsing(function ($state, $record) {
                        $client = \App\Models\Client::find($state);
                        return $client?->name ?? $state;
                    })
                    ->searchable(),
                TextColumn::make('product_id')
                    ->label(__('app.product'))
                    ->formatStateUsing(function ($state, $record) {
                        $product = \App\Models\Product::find($state);
                        return $product?->name ?? $state;
                    })
                    ->searchable(),
                TextColumn::make('type_rule')
                    ->label(__('app.type_price'))
                    ->formatStateUsing(fn (string $state): string =>
                        $state === 'precio_fijo' ? 'Precio Fijo' : 'Descuento'
                    )
                    ->badge()
                    ->color(fn (string $state): string =>
                        $state === 'precio_fijo' ? 'primary' : 'danger'
                    )
                    ->searchable(),
                TextColumn::make('value')
                    ->label(__('app.value'))
                    ->searchable(),
                TextColumn::make('min_quantity')
                    ->label(__('app.min_quantity'))
                    ->searchable(),
                /*
                TextColumn::make('init_date')
                    ->label(__('app.init_date'))
                    ->searchable(),
                TextColumn::make('expired_date')
                    ->label(__('app.expired_date'))
                    ->searchable(),
                */
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
