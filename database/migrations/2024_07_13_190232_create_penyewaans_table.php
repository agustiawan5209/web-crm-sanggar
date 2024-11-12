<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyewaans', function (Blueprint $table) {
            $table->id();
            $table->string('struk')->nullable();
            $table->foreignId('customer_id')->constrained('customers');
            $table->json('customer_user');
            $table->string('jenis');
            $table->json('produk_id')->nullable();
            $table->string('produk');
            $table->date('tgl_pengambilan')->nullable();
            $table->date('tgl_pengembalian')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('status')->comment('Dalam Penyewaan, Di Kembalikan, DIBATALKAN, SELESAI');
            $table->string('tipe_bayar',20)->nullable();

            $table->date('tgl_penyewaan')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('ongkir',50)->nullable();
            $table->bigInteger('biaya_ongkir')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaans');
    }
};
