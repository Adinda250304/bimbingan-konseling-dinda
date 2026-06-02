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
        Schema::table('konselings', function (Blueprint $table) {
            $table->foreignId('rujukan_oleh_id')->nullable()->constrained('users')->onDelete('set null')->after('guru_id');
            $table->text('alasan_rujukan')->nullable()->after('deskripsi_masalah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konselings', function (Blueprint $table) {
            $table->dropForeign(['rujukan_oleh_id']);
            $table->dropColumn(['rujukan_oleh_id', 'alasan_rujukan']);
        });
    }
};
