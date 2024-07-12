<?php

namespace Database\Seeders;

use App\Models\ProdukJasa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukJasaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produk_jasas = array(
            array(
                "id" => 1,
                "nama" => "Paket Lengkap",
                "harga" => 5000000.00,
                "keterangan" => "<p>- Oni-oni Toriolo untuk acara Mappacci</p><p>- Osong (Angaru)</p><p>- Tari Padduppa musik hidup</p><p>- Tari Kreasi musik hidup</p><p><br></p>",
                "status" => "0",
                "created_at" => "2024-07-12 17:27:46",
                "updated_at" => "2024-07-12 17:27:46",
            ),
            array(
                "id" => 2,
                "nama" => "Paket Akad Lengkap musik hidup",
                "harga" => 4000000.00,
                "keterangan" => "<p>- Osong (Angaru)</p><p>- Tari Padduppa musik hidup&nbsp;</p><p>- Tari Kreasi musik hidup</p>",
                "status" => "0",
                "created_at" => "2024-07-12 17:28:20",
                "updated_at" => "2024-07-12 17:28:20",
            ),
            array(
                "id" => 3,
                "nama" => "Paket Akad lengkap mix",
                "harga" => 3000000.00,
                "keterangan" => "<p>- Osong (Angaru)</p><p>- Tari Padduppa musik hidup</p><p>- Tari Kreasi musik jadi / audio</p><p><br></p>",
                "status" => "0",
                "created_at" => "2024-07-12 17:28:44",
                "updated_at" => "2024-07-12 17:28:44",
            ),
            array(
                "id" => 4,
                "nama" => "Paket Akad lengkap musik jadi / audio",
                "harga" => 2500000.00,
                "keterangan" => "<p>- Osong (Angaru)</p><p>- Tari Padduppa musik jadi</p><p>- Tari Kreasi musik jadi / audio</p>",
                "status" => "0",
                "created_at" => "2024-07-12 17:29:15",
                "updated_at" => "2024-07-12 17:30:01",
            ),
            array(
                "id" => 5,
                "nama" => "Paket Akad musik hidup",
                "harga" => 2000000.00,
                "keterangan" => "<p>- Osong (Angaru)</p><p>- Tari Padduppa musik hidup&nbsp;</p>",
                "status" => "0",
                "created_at" => "2024-07-12 17:29:37",
                "updated_at" => "2024-07-12 17:29:51",
            ),
            array(
                "id" => 6,
                "nama" => "Paket Akad musik jadi / audio",
                "harga" => 1500000.00,
                "keterangan" => "<p>- Osong (Angaru)</p><p>- Tari Padduppa musik jadi / audio</p>",
                "status" => "0",
                "created_at" => "2024-07-12 17:30:22",
                "updated_at" => "2024-07-12 17:30:22",
            ),
        );
        ProdukJasa::insert($produk_jasas);
    }
}
