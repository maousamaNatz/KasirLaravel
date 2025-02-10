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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->integer('no_meja');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->dateTime('tanggal');
            $table->text('keterangan')->nullable();
            $table->enum('status_order', ['pending', 'proses', 'siap', 'selesai', 'dibatalkan']);
            $table->decimal('total_harga', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
