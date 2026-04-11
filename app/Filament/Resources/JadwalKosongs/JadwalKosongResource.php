<?php

namespace App\Filament\Resources\JadwalKosongs;

use App\Filament\Resources\JadwalKosongs\Pages\CreateJadwalKosong;
use App\Filament\Resources\JadwalKosongs\Pages\EditJadwalKosong;
use App\Filament\Resources\JadwalKosongs\Pages\ListJadwalKosongs;
use App\Filament\Resources\JadwalKosongs\Schemas\JadwalKosongForm;
use App\Filament\Resources\JadwalKosongs\Tables\JadwalKosongsTable;
use App\Models\JadwalKosong;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JadwalKosongResource extends Resource
{
    protected static ?string $model = JadwalKosong::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'jadwalkosong';

    public static function form(Schema $schema): Schema
    {
        return JadwalKosongForm::configure($schema);
    }
public static function canAccess(): bool
{
    return auth()->user()->isLaboran();
}
    public static function table(Table $table): Table
    {
        return JadwalKosongsTable::configure($table);
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
            'index' => ListJadwalKosongs::route('/'),
            'create' => CreateJadwalKosong::route('/create'),
            'edit' => EditJadwalKosong::route('/{record}/edit'),
        ];
    }
}
