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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->string('bukti');
            $table->foreignId('penyewaan_id')->constrained('penyewaans')->onDelete('cascade');
            $table->decimal('total', 10,2);
            $table->string('jenis_bayar');
            $table->date('tgl');
            $table->enum('status', ['PENDING','SELESAI', 'DITERIMA','DITOLAK',"DIBATALKAN"])->default('PENDING');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
