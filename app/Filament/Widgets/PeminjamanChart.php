<?php

namespace App\Filament\Widgets;

use App\Models\Lab;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PeminjamanChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Peminjaman';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 2;
    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari ini',
            'week' => 'Minggu terakhir',
            'month' => 'Bulan terakhir',
            'year' => 'Tahun ini',
        ];
    }
    protected function getData(): array
    {
        $filter = $this->filter;

        // ambil semua lab
        $labs = Lab::whereLaboranId(auth()->id())->pluck('nama_labor', 'id');

        $labels = [];
        $datasets = [];

        // 🎯 tentukan label berdasarkan filter
        if ($filter === 'today') {
            for ($i = 0; $i < 24; $i++) {
                $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
            }
        } elseif ($filter === 'week') {
            $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        } elseif ($filter === 'month') {
            $days = Carbon::now()->daysInMonth;
            for ($i = 1; $i <= $days; $i++) {
                $labels[] = (string) $i;
            }
        } else {
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        }

        // 🎯 loop tiap labor
        foreach ($labs as $labId => $labName) {

            $query = Peminjaman::where('lab_id', $labId);

            // filter waktu
            match ($filter) {
                'today' => $query->whereDate('tanggal_mulai', Carbon::today()),
                'week' => $query->whereBetween('tanggal_mulai', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ]),
                'month' => $query->whereMonth('tanggal_mulai', Carbon::now()->month),
                'year' => $query->whereYear('tanggal_mulai', Carbon::now()->year),
                default => null,
            };

            // grouping
            if ($filter === 'today') {
                $data = $query->selectRaw('HOUR(tanggal_mulai) as label, COUNT(*) as total')
                    ->groupBy('label')
                    ->pluck('total', 'label');

                $values = [];
                for ($i = 0; $i < 24; $i++) {
                    $values[] = $data[$i] ?? 0;
                }

            } elseif ($filter === 'week') {
                $data = $query->selectRaw('DAYOFWEEK(tanggal_mulai) as label, COUNT(*) as total')
                    ->groupBy('label')
                    ->pluck('total', 'label');

                $values = [];
                for ($i = 1; $i <= 7; $i++) {
                    $values[] = $data[$i] ?? 0;
                }

            } elseif ($filter === 'month') {
                $data = $query->selectRaw('DAY(tanggal_mulai) as label, COUNT(*) as total')
                    ->groupBy('label')
                    ->pluck('total', 'label');

                $days = Carbon::now()->daysInMonth;
                $values = [];
                for ($i = 1; $i <= $days; $i++) {
                    $values[] = $data[$i] ?? 0;
                }

            } else {
                $data = $query->selectRaw('MONTH(tanggal_mulai) as label, COUNT(*) as total')
                    ->groupBy('label')
                    ->pluck('total', 'label');

                $values = [];
                for ($i = 1; $i <= 12; $i++) {
                    $values[] = $data[$i] ?? 0;
                }
            }

            // warna random
            $colors = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#06b6d4'];
            $color = $colors[$labId % count($colors)];
            $datasets[] = [
                'label' => $labName,
                'data' => $values,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'tension' => 0.4,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
