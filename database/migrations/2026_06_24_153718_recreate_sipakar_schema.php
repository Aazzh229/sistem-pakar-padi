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
        // 1. Drop old tables if they exist
        Schema::dropIfExists('rule_detail');
        Schema::dropIfExists('rules');
        Schema::dropIfExists('gejala');
        Schema::dropIfExists('diagnosa');

        // 2. Re-create gejala table
        Schema::create('gejala', function (Blueprint $table) {
            $table->id();
            $table->string('kode_gejala')->unique();
            $table->string('nama_gejala');
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 3. Re-create penyakit table
        Schema::create('penyakit', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penyakit')->unique();
            $table->string('nama_penyakit');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 4. Re-create hama table
        Schema::create('hama', function (Blueprint $table) {
            $table->id();
            $table->string('kode_hama')->unique();
            $table->string('nama_hama');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 5. Re-create rules table
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gejala_id')->constrained('gejala')->onDelete('cascade');
            $table->string('target_type'); // 'penyakit' or 'hama'
            $table->unsignedBigInteger('target_id');
            $table->decimal('cf_pakar', 4, 2);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // 6. Re-create library table
        Schema::create('library', function (Blueprint $table) {
            $table->id();
            $table->string('jenis'); // 'penyakit' or 'hama'
            $table->string('nama');
            $table->string('nama_latin')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('penyebab')->nullable();
            $table->text('solusi')->nullable();
            $table->text('pencegahan')->nullable();
            $table->string('gambar')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // 7. Create diagnosis_histories table
        Schema::create('diagnosis_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('hasil'); // JSON string format
            $table->decimal('persentase', 5, 2);
            $table->timestamps();
        });

        // 8. Alter users table if columns don't exist
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password');
            }
            if (!Schema::hasColumn('users', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosis_histories');
        Schema::dropIfExists('library');
        Schema::dropIfExists('rules');
        Schema::dropIfExists('hama');
        Schema::dropIfExists('penyakit');
        Schema::dropIfExists('gejala');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'created_by', 'status']);
        });
    }
};
