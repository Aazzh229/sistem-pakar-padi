<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diagnosis_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('diagnosis_histories', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('diagnosis_histories', function (Blueprint $table) {
            if (Schema::hasColumn('diagnosis_histories', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
