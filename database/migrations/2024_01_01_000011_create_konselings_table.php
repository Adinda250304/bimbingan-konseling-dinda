<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal_konselings')->onDelete('set null');
            $table->string('jenis_masalah'); // akademik, karir, sosial, pribadi
            $table->text('deskripsi_masalah');
            $table->enum('jenis', ['online', 'offline'])->default('offline');
            $table->enum('status', ['menunggu', 'disetujui', 'berlangsung', 'ditolak', 'selesai'])->default('menunggu');
            $table->string('link_meeting')->nullable(); // untuk online
            $table->date('tanggal_konseling')->nullable();
            $table->time('jam_konseling')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konselings');
    }
};
