<?php

namespace App\Filament\Resources\SaleOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Get;

class SaleOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label(__('app.code'))
                    ->required(),
                DatePicker::make('order_date')
                    ->label(__('app.order_date'))
                    ->default(now())
                    ->required(),
                Select::make('client_id')
                    ->label(__('app.client'))
                    ->relationship('client', 'name') // relación belongsTo client()
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('status')
                    ->label(__('app.status'))
                    ->default('Pendiente') // valor por defecto al crear
                    ->required(),

                /*
                Repeater::make('items')
                    ->relationship()
                    ->schema([
                        // Mismos campos pero NO reactive/live
                        Select::make('product_id')->label('Producto')->relationship('product', 'name')->searchable()->required(),
                        TextInput::make('quantity')->numeric()->default(1)->required(),
                        TextInput::make('price')->numeric()->required(),
                        TextInput::make('subtotal')->numeric()->disabled()->dehydrated(),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->addable(false)  // NO agrega filas
                    ->deletable(false) // NO borra filas
                    ->reorderable(false),
                */

                Section::make(__('app.new_product'))
                    ->schema([
                        Select::make('new_item.product_id')
                        ->label(__('app.product'))
                        ->options(\App\Models\Product::pluck('name', 'id'))  // ← OPCIONES MANUALES
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $product = \App\Models\Product::find($state);
                            if ($product) {
                                $set('new_item.price', $product->price);
                            }
                        }),

                        TextInput::make('new_item.quantity')
                            ->label(__('app.quantity'))
                            ->numeric()
                            ->default(1)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $price = $get('new_item.price') ?? 0;
                                $set('new_item.subtotal', $state * $price);
                            }),

                        TextInput::make('new_item.price')
                            ->label(__('app.price'))
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $qty = $get('new_item.quantity') ?? 1;
                                $set('new_item.subtotal', $qty * $state);
                            }),

                        TextInput::make('new_item.subtotal')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->footerActions([
                        Action::make('addItem')
                            ->label(__('app.add_new_product'))
                            ->icon('heroicon-o-plus')
                            ->color('success')
                            ->action(function ($set, $get) {
                                $newItem = [
                                    'product_id' => $get('new_item.product_id'),
                                    'quantity' => $get('new_item.quantity'),
                                    'price' => $get('new_item.price'),
                                    'subtotal' => $get('new_item.subtotal'),
                                ];

                                $currentItems = $get('items') ?? [];
                                $currentItems[] = $newItem;

                                $set('items', $currentItems);

                                // Limpiar el formulario del nuevo item
                                $set('new_item.product_id', null);
                                $set('new_item.quantity', 1);
                                $set('new_item.price', null);
                                $set('new_item.subtotal', null);
                            }),
                    ]),

                Placeholder::make('items_preview')
                    ->label('Resumen de productos')
                    ->content(function ($get) {
                        $items = $get('items') ?? [];

                        if (! count($items)) {
                            return 'Sin productos';
                        }

                        $html = '<table class="fi-ta min-w-full text-sm">
                                    <thead>
                                        <tr>
                                            <th class="px-2 py-1 text-left">Product</th>
                                            <th class="px-2 py-1 text-left">Quantity</th>
                                            <th class="px-2 py-1 text-left">Price</th>
                                            <th class="px-2 py-1 text-left">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                                foreach ($items as $item) {
                                    $productName = 'Sin productos';

                                    // Buscar el nombre del producto por ID
                                    if (isset($item['product_id'])) {
                                        $product = \App\Models\Product::find($item['product_id']);
                                        $productName = $product ? $product->name : 'Sin productos';
                                    }

                                    $html .= '<tr>
                                                <td class="px-2 py-1 text-left">'.$productName.'</td>
                                                <td class="px-2 py-1 text-left">'.($item['quantity'] ?? '').'</td>
                                                <td class="px-2 py-1 text-left">'.($item['price'] ?? '').'</td>
                                                <td class="px-2 py-1 text-left">'.($item['subtotal'] ?? '').'</td>
                                                </tr>';
                                }

                        $html .= '   </tbody>
                                </table>';

                        return new \Illuminate\Support\HtmlString($html);
                    })
                    ->columnSpanFull(),
            ]);
    }
}
