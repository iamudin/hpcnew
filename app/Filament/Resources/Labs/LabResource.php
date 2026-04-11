<?php

namespace App\Filament\Resources\Labs;

use App\Filament\Resources\Labs\Pages\CreateLab;
use App\Filament\Resources\Labs\Pages\EditLab;
use App\Filament\Resources\Labs\Pages\ListLabs;
use App\Filament\Resources\Labs\Schemas\LabForm;
use App\Filament\Resources\Labs\Tables\LabsTable;
use App\Models\Lab;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LabResource extends Resource
{

    protected static ?string $model = Lab::class;
    protected static ?string $modelLabel = 'Laboratorium';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Beaker;
    protected static ?string $pluralModelLabel = 'Laboratorium';

    protected static ?string $recordTitleAttribute = 'Laboratorium';

    public static function form(Schema $schema): Schema
    {
        return LabForm::configure($schema);
    }
public static function canAccess(): bool
{
    return auth()->user()->isLaboran();
}
    public static function table(Table $table): Table
    {
        return LabsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
           RelationManagers\JadwalKosongsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLabs::route('/'),
            'create' => CreateLab::route('/create'),
            'edit' => EditLab::route('/{record}/edit'),
            'view'   => Pages\ViewLab::route('/{record}'),
        ];
    }
}
