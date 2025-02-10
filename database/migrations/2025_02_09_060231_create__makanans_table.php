<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('makanans', function (Blueprint $table) {
            $table->id('id_masakan');
            $table->string('nama_masakan');
            $table->decimal('harga', 10, 2);
            $table->string('status_masakan');
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Makanans');
    }
};
