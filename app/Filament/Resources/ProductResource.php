<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500),

                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->required(),

                Forms\Components\Select::make('stock')
                    ->label('Stock Status')
                    ->options([
                        'available' => 'Available',
                        'unavailable' => 'Unavailable',
                    ])
                    ->required(),

                    Forms\Components\FileUpload::make('image')
                    ->label('Product Image')
                    ->disk('public') // Save to the public disk
                    ->directory('product-images') // Save to this directory
                    ->required(false)
                

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                    Tables\Columns\TextColumn::make('image')
                    ->label('Image')
                    ->formatStateUsing(function ($state) {
                        return '<img src="/storage/' . $state . '" alt="Product Image" class="w-16 h-16 object-cover rounded">';
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->money('MYR', true),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->badge() // Enables badge styling for this column
                    ->colors([
                        'success' => 'available',    // Green for "Available"
                        'danger' => 'unavailable',  // Red for "Unavailable"
                    ]),

            ]);
    }



    public static function getRelations(): array
    {
        return [
            // Define any relations if necessary
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
