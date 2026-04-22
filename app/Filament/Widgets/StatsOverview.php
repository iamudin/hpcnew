<?php

namespace App\Filament\Widgets;

use App\Models\Peminjaman;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{

    protected function getStats(): array
    {
         $stats = [];
        if (auth()->user()->isKalab() || auth()->user()->isLaboran()) {



            if (auth()->user()->isKalab()) {
                $stats[] = Stat::make('Total Peminjaman', auth()->user()->labs->peminjaman?->count())
                    ->color('primary')
                    ->description(fn() => !auth()->user()->labs->peminjaman?->count() ? 'Belum ada peminjaman di laboratorium Anda' : 'Total peminjaman yang terjadi di laboratorium Anda')
                    ->icon('heroicon-o-document-text')
                    ->url(route('filament.admin.resources.peminjaman.index'));
            }
            if (auth()->user()->isLaboran()) {

                $stats[] = Stat::make('Total Laboratorium', auth()->user()->labs->count())
                    ->color('primary')
                    ->description(fn() => !auth()->user()->labs->count() ? 'Anda belum ditugaskan sebagai laboran di laboratorium manapun' : 'Total laboratorium yang Anda kelola')
                    ->icon('heroicon-o-building-office')
                    ->url(route('filament.admin.resources.labs.index'));
                $stats[] = Stat::make('Total Peminjaman', Peminjaman::whereHas('lab', fn($query) => $query->where('laboran_id', auth()->id()))->count())
                    ->color('primary')
                    ->description( 'Total peminjaman yang terjadi di laboratorium Anda')
                    ->icon('heroicon-o-document-text')
                    ->url(route('filament.admin.resources.peminjaman.index'));
            }
            return $stats;
        } elseif (auth()->user()->isMahasiswa()) {

            return [
                Stat::make('Total Peminjaman', auth()->user()->isMahasiswa() ? auth()->user()->mahasiswa->peminjaman->count() : 0)
                    ->color('primary')
                    ->description(fn() => !auth()->user()->mahasiswa->peminjaman->count() ? 'Anda belum pernah melakukan peminjaman' : 'Total peminjaman yang telah Anda lakukan')
                    ->icon('heroicon-o-document-text')
                    ->url(route('filament.admin.resources.peminjaman.index'))
            ];
        }else{
            return [
                Stat::make('Total User',User::count())
                    ->color('primary')
                    ->description( 'Total User')
                    ->icon('heroicon-o-document-text')
                    ->url(route('filament.admin.resources.users.index'))
            ];
        }
    }
}
