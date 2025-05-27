<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UMKMResource\Pages;
use App\Models\UMKM;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class UMKMResource extends Resource
{
    protected static ?string $model = UMKM::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('umkm-images')
                    ->visibility('public'),
                Forms\Components\TextInput::make('bank_name')
                    ->label('Bank Name'),
                Forms\Components\TextInput::make('bank_account_number')
                    ->label('Bank Account Number'),
                Forms\Components\TextInput::make('bank_account_name')
                    ->label('Bank Account Name'),
                Forms\Components\TextInput::make('whatsapp'),
                Forms\Components\TextInput::make('instagram'),
                Forms\Components\TextInput::make('facebook'),
                Forms\Components\TextInput::make('operating_hours')
                    ->label('Operating Hours'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('user.name')->label('Owner'),
                Tables\Columns\TextColumn::make('bank_name')->label('Bank'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan jika ada RelationManagers
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUMKMs::route('/'),
            'create' => Pages\CreateUMKM::route('/create'),
            'edit' => Pages\EditUMKM::route('/{record}/edit'),
        ];
    }
}
