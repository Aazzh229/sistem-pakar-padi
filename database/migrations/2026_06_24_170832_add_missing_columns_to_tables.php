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
        Schema::table('gejala', function (Blueprint $table) {
            if (!Schema::hasColumn('gejala', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            }
        });

        Schema::table('penyakit', function (Blueprint $table) {
            if (!Schema::hasColumn('penyakit', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('penyakit', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            }
        });

        Schema::table('hama', function (Blueprint $table) {
            if (!Schema::hasColumn('hama', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('hama', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gejala', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['created_by']);
        });

        Schema::table('penyakit', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['deskripsi', 'created_by']);
        });

        Schema::table('hama', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['deskripsi', 'created_by']);
        });
    }
};
