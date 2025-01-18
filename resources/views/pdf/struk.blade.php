<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penyewaan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h1, .container h2 {
            text-align: center;
        }
        .container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .container table th, .container table td {
            padding: 3px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .container .total {
            text-align: right;
            font-weight: bold;
        }
        .container .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
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
    <div class="container">
        <h1>Struk Penyewaan Barang</h1>
        <h2>No. Invoice: #{{ $data->pembayaran->kode_transaksi }}</h2>
        <h3>Tanggal Transaksi: #{{ $data->pembayaran->tgl }}</h3>

        <table>
            <tr>
                <th>Nama Customer</th>
                <td>{{ $data->customer->nama }}</td>
            </tr>
            <tr>
                <th>No. Telpon Customer</th>
                <td>{{ $data->customer->no_telpon }}</td>
            </tr>
            <tr>
                <th>Alamat Customer</th>
                <td>{{ $data->customer->alamat }}</td>
            </tr>
            <tr>
                <th>Nama Produk</th>
                <td>{{ $data->produk }}</td>
            </tr>
            @if ($data->jenis == 'alat')
                <tr>
                    <th>Tanggal Pengambilan</th>
                    <td>{{ $data->tgl_pengambilan }}</td>
                </tr>
            @endif
            @if ($data->jenis == 'jasa')
                <tr>
                    <th>Tanggal Penyewaan</th>
                    <td>{{ $data->tgl_penyewaan }}</td>
                </tr>
            @endif
            <tr>
                <th>Pengiriman</th>
                <td>{{ $data->ongkir }}</td>
            </tr>
            <tr>
                <th>Harga Pengiriman</th>
                <td>{{ $data->biaya_ongkir }}</td>
            </tr>
            <tr>
                <th>Lokasi</th>
                <td>{{ $data->lokasi }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $data->pembayaran->jenis_bayar }}</td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>{{ $data->jumlah }}</td>
            </tr>
            <tr>
                <th>Total Bayar</th>
                <td>Rp {{ number_format($data->pembayaran->total,0,2) }}</td>
            </tr>
        </table>

        <div class="total">
            <p>Sub Total : Rp {{ number_format($data->pembayaran->sub_total,0,2) }}</p>
        </div>

        <div class="footer">
            <p>Terima kasih atas penyewaan Anda!</p>
            <p>Jika ada pertanyaan, silakan hubungi kami di +62 82194709778</p>
        </div>
    </div>
</body>
</html>
