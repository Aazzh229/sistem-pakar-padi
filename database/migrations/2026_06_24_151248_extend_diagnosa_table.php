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
        Schema::table('diagnosa', function (Blueprint $table) {
            $table->text('deskripsi')->nullable();
            $table->text('solusi')->nullable();
            $table->text('penyebab')->nullable();
            $table->text('pencegahan')->nullable();
            $table->string('gambar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnosa', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'solusi', 'penyebab', 'pencegahan', 'gambar']);
        });
    }
};
