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
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('jenis');
            $table->foreignId('produk_id')->nullable();
            $table->string('produk');
            $table->string('tgl_pengambilan');
            $table->string('tgl_pengembalian');
            $table->integer('jumlah')->nullable();
            $table->string('status')->comment('Dalam Penyewaan, Di Kembalikan, DIBATALKAN, SELESAI');
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
