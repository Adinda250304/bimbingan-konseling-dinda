<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_konselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konseling_id')->constrained('konselings')->onDelete('cascade');
            $table->text('catatan_konselor');
            $table->text('saran');
            $table->string('tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_konselings');
    }
};
