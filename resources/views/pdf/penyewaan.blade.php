<!DOCTYPE html>
<html>
<head>
    <title>Data Penyewaan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .header h1, .header h2, .header p {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        {{-- <img src="{{ asset('/img/logo.png') }}" alt="Logo Perusahaan"> --}}
        <h1>Sanggar Seni Kawali</h1>
        <p>Tetewatu, Kec. Lili Rilau, Kabupaten Soppeng, Sulawesi Selatan </p>
        <p>Telepon: +62 82194709778 | Instagram: sanggar.seni.kawali</p>
        <hr>
    </div>
    <div class="content">
        <h1>Data Penyewaan</h1>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Transaksi</th>
                    <th>Customer ID</th>
                    <th>Jenis</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Tanggal Pengambilan</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $penyewaan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $penyewaan->kode_transaksi }}</td>
                        <td>{{ $penyewaan->customer_id }}</td>
                        <td>{{ $penyewaan->jenis }}</td>
                        <td>{{ $penyewaan->produk }}</td>
                        <td>{{ $penyewaan->jumlah }}</td>
                        <td>{{ $penyewaan->tgl_pengambilan }}</td>
                        <td>{{ $penyewaan->tgl_pengembalian }}</td>
                        <td style="white-space: nowrap;">{{ "Rp. ". number_format($penyewaan->pembayaran->total, 0,2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8">Total Pendapatan</td>
                    <td style="white-space: nowrap;"> {{ $total_pendapatan }} </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
