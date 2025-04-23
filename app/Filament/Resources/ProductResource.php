<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatusEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Main data')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->unique(ignoreRecord: true),
                            TextInput::make('price')
                                ->required()
                                ->rule('numeric'),
                        ]),
                    Forms\Components\Wizard\Step::make('Additional data')
                        ->schema([
                            Radio::make('status')
                                ->options(ProductStatusEnum::class),
                            Select::make('category_id')
                                ->relationship('category', 'name'),
                        ]),
                    ])
                ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(isIndividual: true, isGlobal: false),
                TextColumn::make('price')
                    ->sortable()
                    ->money()
                    ->alignEnd()
                    ->getStateUsing(function (Product $record): float {
                        return $record->price / 100;
                    }),
                TextColumn::make('status')
                    ->badge(),
//                SelectColumn::make('status')
//                    ->options(ProductStatusEnum::class),
                TextColumn::make('category.name')
                    ->label('Category Name'),
//                    ->url(function (Product $record) {
//                        return CategoryResource::getUrl('edit', [
//                            'record' => $record->category_id
//                        ]);
//                    }),
                TextColumn::make('tags.name')->badge(),
                ToggleColumn::make('is_active'),
            ])
            ->defaultSort('price', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ProductStatusEnum::class),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                Tables\Filters\Filter::make('created_from')
                    ->form([
                        DatePicker::make('created_from'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('created_until')
                    ->form([
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ], Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TagsRelationManager::class
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
