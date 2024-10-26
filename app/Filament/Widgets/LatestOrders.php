<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Order;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\OrderResource;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int | string | array  $columnSpan = 'full';

    protected static ?int $sort =2;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at','desc')
            ->columns([

                TextColumn::make('id')
                ->label('Order ID')
                ->searchable(),

                TextColumn::make('user.name')
                ->label('Customer')
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
                ->actions([
                    Action::make('view order')
                        ->url(fn(Order $record):string => OrderResource::getUrl('view',['record'=>$record]))
                        ->color('info')
                        ->label('')
                        ->icon('heroicon-o-eye')
                ]);
    }
}
