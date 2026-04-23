<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tindak_lanjut', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konseling_id')->constrained('konselings')->onDelete('cascade');
            $table->string('jenis'); // pemanggilan_ortu, mediasi, rujukan, lainnya
            $table->text('catatan');
            $table->string('kode_unik')->unique(); // untuk QR code verifikasi
            $table->string('status_wa')->default('belum');    // belum, terkirim, gagal
            $table->string('status_email')->default('belum'); // belum, terkirim, gagal
            $table->timestamp('dikirim_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindak_lanjut');
    }
};
