<?php

namespace App\Filament\Resources\Laborans;

use App\Filament\Resources\Laborans\Pages\CreateLaboran;
use App\Filament\Resources\Laborans\Pages\EditLaboran;
use App\Filament\Resources\Laborans\Pages\ListLaborans;
use App\Filament\Resources\Laborans\Schemas\LaboranForm;
use App\Filament\Resources\Laborans\Tables\LaboransTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LaboranResource extends Resource
{
    protected static ?string $title = 'Laboran';

    protected static ?string $model = User::class;
    protected static ?string $slug = 'laborans';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Laboran';

    public static function form(Schema $schema): Schema
    {
        return LaboranForm::configure($schema);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    public static function table(Table $table): Table
    {
        return LaboransTable::configure($table);
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
            'index' => ListLaborans::route('/'),
            'create' => CreateLaboran::route('/create'),
            'edit' => EditLaboran::route('/{record}/edit'),
        ];
    }
}
