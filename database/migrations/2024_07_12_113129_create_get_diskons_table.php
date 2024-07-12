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
        Schema::create('get_diskons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diskon_id')->constrained('diskons')->onDelete('cascade');
            $table->decimal('min_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('get_diskons');
    }
};
