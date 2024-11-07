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
        Schema::create('produk_jasas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->decimal('harga', 10,2);
            $table->decimal('biaya_transportasi', 10,2);
            $table->text('keterangan');
            $table->enum('status', ['0','1'])->comment('0 = tersedia, 1=tidak tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_jasas');
    }
};
