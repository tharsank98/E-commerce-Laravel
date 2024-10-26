<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\OrderResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                   ->label('Order ID')
                   ->searchable(),

                TextColumn::make('grand_total')
                   ->money('LKR'),

                TextColumn::make('status')
                   ->badge()
                   ->color(fn(string $state):string =>match($state){
                        'new'=>'info',
                        'processing'=>'warning',
                        'shipped'=>'success',
                        'delivered'=>'success',
                        'canceled'=>'danger'
                    })
                    ->icon(fn(string $state):string =>match($state){
                        'new'=>'heroicon-m-sparkles',
                        'processing'=>'heroicon-m-arrow-path',
                        'shipped'=>'heroicon-m-truck',
                        'delivered'=>'heroicon-m-check-badge',
                        'canceled'=>'heroicon-m-x-circle'
                    })
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Action::make('view order')
                  ->url(fn(Order $record):string => OrderResource::getUrl('view',['record'=>$record]))
                  ->color('info')
                  ->icon('heroicon-o-eye')
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
