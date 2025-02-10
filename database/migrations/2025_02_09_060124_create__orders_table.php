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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('id_order');
            $table->integer('no_meja');
            $table->foreignId('id_user')->constrained('users', 'id_user');
            $table->dateTime('tanggal');
            $table->text('keterangan')->nullable();
            $table->enum('status_order', ['pending', 'proses', 'siap', 'selesai', 'dibatalkan']);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('uang_bayar', 15, 2)->nullable();
            $table->decimal('uang_kembali', 15, 2)->nullable();
            $table->enum('metode_pembayaran', ['tunai', 'debit', 'kredit', 'qris'])->default('tunai');
            $table->enum('status_pembayaran', ['belum_bayar', 'kurang', 'lunas'])->default('belum_bayar');
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
