<?php

namespace App\Filament\Resources\Peminjamen\Widgets;

use App\Filament\Resources\Peminjamen\Pages\ListPeminjamen;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PeminjamanOverview extends StatsOverviewWidget
{
use InteractsWithPageTable;
  protected function getTablePage(): string
    {
        return ListPeminjamen::class;
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Peminjaman', $this->getPageTableQuery()->count()),
             Stat::make('Disetujui', $this->getPageTableQuery()->where('status', 'approved')->count()),
            Stat::make('Ditolak', $this->getPageTableQuery()->where('status', 'rejected')->count())->color('success'),
        ];
    }
}
