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
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->id('id_detail_order');
            $table->foreignId('id_order')->constrained('orders', 'id_order');
            $table->foreignId('id_masakan')->constrained('makanans', 'id_masakan');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->enum('status_detail_order', ['pending', 'diproses', 'selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_orders');
    }
};
