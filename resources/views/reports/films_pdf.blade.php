<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Film Bioskop</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 0;
            color: #666666;
            font-size: 11px;
        }
        .meta-info {
            margin-bottom: 15px;
            font-size: 10px;
            color: #555555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th {
            background-color: #f2f2f2;
            border: 1px solid #dddddd;
            padding: 8px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        table td {
            border: 1px solid #dddddd;
            padding: 8px 10px;
            font-size: 10px;
            vertical-align: middle;
        }
        table tr:nth-child(even) td {
            background-color: #fafafa;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999999;
            border-top: 1px solid #eeeeee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Data Film Bioskop</h1>
        <p>Aplikasi Rekayasa Web - CineManage</p>
    </div>

    <div class="meta-info">
        <table style="width: 100%; border: none; margin-bottom: 10px;">
            <tr style="background: none;">
                <td style="border: none; padding: 0; width: 50%;">
                    <strong>Tanggal Cetak:</strong> {{ date('d F Y H:i:s') }}<br>
                    <strong>Total Film:</strong> {{ $films->count() }} data
                </td>
                <td style="border: none; padding: 0; text-align: right; width: 50%;">
                    <strong>Nama Mahasiswa:</strong> Dimas Alfa Pratama (UAS Rekayasa Web)<br>
                    <strong>NIM:</strong> 241011750041
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%; text-align: center;">Poster</th>
                <th style="width: 13%;">ID Film</th>
                <th style="width: 30%;">Judul Film</th>
                <th style="width: 15%;">Genre</th>
                <th style="width: 15%;">Sutradara</th>
                <th style="width: 10%; text-align: center;">Tahun Rilis</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($films as $film)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center" style="padding: 4px;">
                        @if($film->gambar)
                            @php
                                $isRemote = strpos($film->gambar, 'http') === 0;
                                if ($isRemote) {
                                    $imagePath = $film->gambar;
                                } else {
                                    $imagePath = storage_path('app/public/' . $film->gambar);
                                }
                            @endphp
                            <img src="{{ $imagePath }}" style="width: 40px; height: 60px; border: 1px solid #cccccc; border-radius: 2px;" alt="Poster">
                        @else
                            <span style="color: #999999; font-size: 8px;">No Image</span>
                        @endif
                    </td>
                    <td style="font-family: monospace;">#{{ str_pad($film->id_film, 4, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $film->judul }}</strong></td>
                    <td>{{ $film->genre }}</td>
                    <td>{{ $film->sutradara }}</td>
                    <td class="text-center">{{ $film->tahun_rilis }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat otomatis oleh sistem CineManage - NIM 241011750041. Halaman 1 dari 1
    </div>

</body>
</html>
