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
            $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('rujukan_oleh_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('jadwal_id')->nullable()->constrained('jadwal_konselings')->onDelete('set null');
            $table->string('jenis_masalah'); // akademik, karir, sosial, pribadi, keluarga
            $table->text('deskripsi_masalah');
            $table->text('alasan_rujukan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'berlangsung', 'ditolak', 'selesai', 'tidak_hadir'])->default('menunggu');
            $table->date('tanggal_konseling')->nullable();
            $table->time('jam_konseling')->nullable();
            $table->string('tempat')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->text('feedback_siswa')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konselings');
    }
};
