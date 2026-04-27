<?php

namespace App\Filament\Resources\Peminjamen\Tables;

use App\Models\Lab;
use App\Services\WaSender;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class PeminjamenTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lab.nama_labor')
                    ->description(fn($record) => $record->lab->laboran ? 'Nama Laboran : ' . $record->lab->laboran?->name : '')
                    ->searchable()
                    ->hidden(fn() => in_array(auth()->user()->role, [ 'kepala_laboran'])),// hanya tampil untuk admin,
                TextColumn::make('mahasiswa.nama')
                    ->visible(fn() => in_array(auth()->user()->role, ['laboran', 'kepala_laboran'])) // hanya tampil untuk admin
                    ->description(fn($record) => $record->mahasiswa ? 'NIM: ' . $record->mahasiswa?->nim . ' | Prodi: ' . $record->mahasiswa?->prodi : '')
                    ->default('Tidak ada mahasiswa')
                    ->searchable()
                    ->url(fn($record) => $record->mahasiswa ? route('filament.admin.resources.mahasiswas.view', $record->mahasiswa->id) : null)
                , // opsional
                TextColumn::make('waktu_peminjaman')
                    ->label('Waktu Peminjaman')
                    ->default(fn($record) => 'Tanggal : ' . $record->tanggal_mulai?->format('d F Y') ?? '-')
                    ->description(fn($record) => 'Jam : ' . $record->tanggal_mulai?->format('H:i') . ' - ' . $record->tanggal_selesai?->format('H:i'))
                ,
                TextColumn::make('keperluan')
                    ->searchable(),
                TextColumn::make('surat_peminjaman')
                    ->label('Surat')
                    ->icon('heroicon-o-document')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Lihat Surat' : '-')
                    ->color('danger')
                    ->action(
                        Action::make('preview')
                            ->modalHeading('Preview Surat Peminjaman')
                            ->modalWidth('7xl')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup')
                            ->modalContent(fn($record) => new HtmlString(
                                $record->surat_peminjaman
                                ? "<iframe src='" . Storage::url($record->surat_peminjaman) . "' width='100%' height='600px'></iframe>"
                                : "File tidak tersedia"
                            ))
                    ),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Menunggu diproses',
                            'confirmed_laboran' => 'Dikonfirmasi Laboran',
                            'pending_kepala' => 'Menunggu Persetujuan Kepala Laboran',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            default => ucfirst($state),
                        };
                    })
                    ->description(function ($record) {
                        return match ($record->status) {
                            'confirmed_laboran' => 'Pada: ' . optional($record->confirmed_laboran_at)?->format('d M Y H:i'),
                            'approved' => new HtmlString('<small style="color:green">Pada: ' . optional($record->approved_at)?->format('d M Y H:i') . '<br>Catatan: ' . ($record->catatan ?? '-') .
                                '</small>'),
                            'rejected' => new HtmlString('<small style="color:red">Pada: ' . optional($record->rejected_at)?->format('d M Y H:i') . '<br>Catatan: ' . ($record->catatan ?? '-') .
                                '</small>'),
                            default => null,
                        };
                    })
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed_laboran',
                        'gray' => 'pending_kepala',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),


                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('lab_id')
                    ->label('Laboratorium')
                    ->relationship('lab', 'nama_labor')
                    ->placeholder('Semua Lab'),
                Filter::make('created_at')
                    ->form([
                       
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('tanggal_mulai', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('tanggal_mulai', '<=', $data['until']));
                    })
            ])
            ->headerActions([
                Action::make('print')
                    ->visible(function ($livewire) {
                        $filters = $livewire->tableFilters;

                        return !empty($filters['created_at']['from'])
                            && !empty($filters['created_at']['until']);
                    })

                    ->label('Cetak Rekap Peminjaman')
                    ->icon('heroicon-o-printer')
                    ->action(function ($livewire) {
                        $filters = $livewire->tableFilters;
                        $table['data'] = $livewire->getFilteredTableQuery()->get();
                        $table['tanggal_mulai'] = $filters['created_at']['from'] ?? null;
                        $table['tanggal_selesai'] = $filters
                        ['created_at']['until'] ?? null;
                        $table['nama_labor'] = $filters
                        ['lab_id']['value'] ? Lab::find($filters
                            ['lab_id']['value'])->nama_labor : null;
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.rekap', $table);

                        return response()->streamDownload(
                            fn() => print ($pdf->output()),
                            'laporan.pdf'
                        );
                    })
            ])
            ->actions([

            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('tindaklanjut')
                    ->visible(function ($record) {
                        $role = in_array(auth()->user()->role, ['laboran', 'kepala_laboran']);
                        $status = in_array($record->status, ['pending', 'confirmed_laboran']);
                        if (auth()->user()->isLaboran()) {
                            return $role && $status;
                        } elseif (auth()->user()->isKalab()) {
                            return $role && $record->status == 'pending_kepala';
                        }
                    })
                    ->label('Tindak Lanjut')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')

                    ->form([
                        Select::make('status')
                            ->label('Status Peminjaman')
                            ->options(auth()->user()->isLaboran() ? [
                                'confirmed_laboran' => 'Konfirmasi Laboran',
                                'pending_kepala' => 'Menunggu Persetujuan Kepala',
                                'rejected' => 'Ditolak',

                            ] : [
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                            ])
                            ->default(fn($record) => $record?->status)
                            ->required(),

                        Textarea::make('catatan')
                            ->label('Catatan')
                            ->rows(4)
                            ->default(fn($record) => $record?->catatan)
                            ->placeholder('Masukkan catatan jika diperlukan...')
                            ->required(fn($get) => $get('status') === 'rejected'),
                    ])

                    ->action(function ($record, array $data) {

                        $update = [
                            'status' => $data['status'],
                            'catatan' => $data['catatan'] ?? null,
                        ];

                        // 🔥 auto set timestamp berdasarkan status
                        if ($data['status'] == 'confirmed_laboran') {
                            $update['confirmed_laboran_at'] = now();
                        }
                        if ($data['status'] == 'pending_kepala') {

                            $message = "📢 *Sistem Peminjaman Laboratorium*\n\n"
                                . "Halo Kepala Laboran *{$record->lab->nama_labor}*,\n\n"
                                . "Saat ini ada Permohonan peminjaman laboratorium pada tanggal {$record->tanggal_mulai} mohon ditindaklanjuti.\n\n"
                                . "Terima kasih 🙏";

                            $nohp = $record->lab->kalab->nohp;

                            app(WaSender::class)->send($nohp, $message);
                        }
                        if ($data['status'] == 'approved') {
                            $update['approved_at'] = now();

                                $message = "📢 *Sistem Peminjaman Laboratorium*\n\n"
                                    . "Halo {$record->mahasiswa->nama},\n\n"
                                    . "Permohonan peminjaman laboratorium {$record->lab->nama_labor} pada tanggal {$record->tanggal_mulai} anda telah *disetujui*.\n\n"
                                    . "Terima kasih 🙏";

                                // kirim WA (tanpa nunggu response)
                                $nohp = $record->mahasiswa->nohp;

                                app(WaSender::class)->send($nohp, $message);

                        }

                        if ($data['status'] === 'rejected') {
                            $update['rejected_at'] = now();
                            $message = "📢 *Sistem Peminjaman Laboratorium*\n\n"
                                . "Halo {$record->mahasiswa->nama},\n\n"
                                . "Permohonan peminjaman laboratorium {$record->lab->nama_labor} pada tanggal *{$record->tanggal_mulai}* saat ini  *ditolak* karena *{$data['catatan']}*.\n\n"
                                . "Terima kasih 🙏";

                            // kirim WA (tanpa nunggu response)
                            $nohp = $record->mahasiswa->nohp;

                            app(WaSender::class)->send($nohp, $message);
                        }

                        $record->update($update);

                        Notification::make()
                            ->title('Berhasil')
                            ->body('Tindak lanjut berhasil disimpan')
                            ->success()
                            ->send();
                    })

                    ->modalHeading('Tindak Lanjut Peminjaman')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalWidth('lg'),
                EditAction::make()->visible(fn($record) => $record->status == 'pending'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
