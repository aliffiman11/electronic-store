<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\EditAction; // For editing orders
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Customer')
                    ->relationship('user', 'name') // Resolves `user_id` to `name` from `users` table
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('remarks')
                    ->label('Remarks'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order ID')->sortable(),
                TextColumn::make('user.name') // Resolves `user_id` to `name` from the `users` table
                    ->label('Customer')
                    ->sortable(),
                TextColumn::make('total_price')->label('Total Price')->money('MYR'),
                TextColumn::make('status')->label('Status')->badge(),
                TextColumn::make('created_at')->label('Date')->date(),
            ])
            ->filters([
                Filter::make('pending')->query(fn ($query) => $query->where('status', 'Pending')),
                Filter::make('paid')->query(fn ($query) => $query->where('status', 'paid')),
            ])
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->visible(fn ($record) => $record->status === 'Pending'), // Only show "Edit" for pending orders
            ])
            ->recordUrl(fn ($record) => $record->status === 'ending' 
                ? route('filament.resources.orders.edit', $record) 
                : null) // Prevent navigation for non-pending orders
            ->defaultSort('created_at', 'desc')
            ->bulkActions([]); // Disable bulk actions if not needed
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
