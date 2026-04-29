<?php

namespace App\Filament\Resources\Peminjamen;

use App\Filament\Resources\Peminjamen\Pages\CreatePeminjaman;
use App\Filament\Resources\Peminjamen\Pages\EditPeminjaman;
use App\Filament\Resources\Peminjamen\Pages\ListPeminjamen;
use App\Filament\Resources\Peminjamen\Pages\ViewPeminjaman;
use App\Filament\Resources\Peminjamen\Schemas\PeminjamanForm;
use App\Filament\Resources\Peminjamen\Tables\PeminjamenTable;
use App\Filament\Resources\Peminjamen\Widgets\PeminjamanChart;
use App\Filament\Resources\Peminjamen\Widgets\PeminjamanOverview;
use App\Models\Peminjaman;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PeminjamanResource extends Resource
{
    protected static ?string $model = Peminjaman::class;
    protected static ?string $slug = 'peminjaman';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
  
    protected static ?string $recordTitleAttribute = 'peminjaman';

    public static function form(Schema $schema): Schema
    {
        return PeminjamanForm::configure($schema);
    }


public static function getEloquentQuery(): Builder
{
        $query = parent::getEloquentQuery();
        if(auth()->user()->isMahasiswa()) {
            $query->whereHas('mahasiswa', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
        }
        if(auth()->user()->isLaboran()){
            $query->where('laboran_id', auth()->id());
        }
           if(auth()->user()->isKalab()){
             $query->whereHas('lab.kalab', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
        }
    return $query;
}
    public static function table(Table $table): Table
    {
        return PeminjamenTable::configure($table);
    }
public static function canAccess(): bool
{
    return in_array(auth()->user()->role,['laboran','kepala_laboran','mahasiswa']);
}
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
 protected function getCreatedNotification(): ?Notification
{
    return Notification::make()
        ->title('Berhasil')
        ->body('Peminjaman berhasil disubmit selanjutnya menunggu diproses oleh laboran')
        ->success();
}
protected function getSavedNotification(): ?Notification
{
    return Notification::make()
        ->title('Berhasil')
        ->body('Peminjaman berhasil diperbarui')
        ->success();
}
public static function getPages(): array
    {
        return [
            'index' => ListPeminjamen::route('/'),
            'create' => CreatePeminjaman::route('/create'),
            'view' => ViewPeminjaman::route('/{record}'),
            'edit' => EditPeminjaman::route('/{record}/edit'),
        ];
    }
}
