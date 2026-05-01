<div>
    <!-- Live as if you were to die tomorrow. Learn as if you were to live forever. - Mahatma Gandhi -->
</div>
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Peminjaman</title>
</head>

<body style="font-family: Arial, sans-serif; font-size: 12px; color: #000;">

    <div style="text-align: center; margin-bottom: 10px;">
        <h2 style="margin: 0;">REKAP PEMINJAMAN <br>{{ $nama_labor ? 'Labor ' . $nama_labor : '' }}</h2>
        <p style="margin: 0;">
            Periode: {{ $tanggal_mulai }} s/d {{ $tanggal_selesai }}
        </p>
    </div>

    <!-- Tabel -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr>
                <th style="border: 1px solid #000; padding: 6px; background-color: #f2f2f2; text-align: center;">No</th>
          
                @if(!$nama_labor)
                    <th style="border: 1px solid #000; padding: 6px; background-color: #f2f2f2;">Labor</th>

                @endif
                    <th style="border: 1px solid #000; padding: 6px; background-color: #f2f2f2;">NIM</th>
                <th style="border: 1px solid #000; padding: 6px; background-color: #f2f2f2;">Nama</th>
                <th style="border: 1px solid #000; padding: 6px; background-color: #f2f2f2;">Prodi</th>
                <th style="border: 1px solid #000; padding: 6px; background-color: #f2f2f2;">Semester</th>
                <th style="border: 1px solid #000; padding: 6px; background-color: #f2f2f2;">Kelas</th>
                <th style="border: 1px solid #000; padding: 6px; background-color: #f2f2f2;">Tanggal Pinjam</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td style="border: 1px solid #000; padding: 6px; text-align: center;">
                        {{ $index + 1 }}
                    </td>
                    @if(!$nama_labor)
                    <td style="border: 1px solid #000; padding: 6px;">
                        {{ $item->lab->nama_labor }}
                    </td>
                    @endif
                    <td style="border: 1px solid #000; padding: 6px;">
                        {{ $item->mahasiswa->nim }}
                    </td>
                    <td style="border: 1px solid #000; padding: 6px;">
                        {{ $item->mahasiswa->nama }}
                    </td>
                    <td style="border: 1px solid #000; padding: 6px;">
                        {{ $item->mahasiswa->prodi }}
                    </td>
                    <td style="border: 1px solid #000; padding: 6px;">
                        {{ $item->mahasiswa->semester }}
                    </td>
                    <td style="border: 1px solid #000; padding: 6px;">
                        {{ $item->mahasiswa->kelas }}
                    </td>
                    <td style="border: 1px solid #000; padding: 6px;">
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d-m-Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div style="margin-top: 20px; text-align: right;">
        <p style="margin: 0;">Dicetak pada: {{ date('d-m-Y') }}</p>
    </div>

</body>

</html>