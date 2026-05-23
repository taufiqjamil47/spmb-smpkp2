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
        // Tabel master grouping request
        Schema::create('grouping_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_code', 50)->unique(); // Kode unik grup
            $table->string('group_name', 100); // Nama grup (optional, dari user)
            $table->foreignId('tahun_ajaran_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing'])->default('pending');
            $table->text('notes')->nullable(); // Catatan dari petugas
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Tambah kolom grouping ke tabel calon_siswas
        Schema::table('calon_siswas', function (Blueprint $table) {
            $table->foreignId('grouping_request_id')->nullable()->after('classroom_id')
                ->constrained('grouping_requests')->onDelete('set null');
            $table->string('requested_with_names', 500)->nullable()->after('grouping_request_id')
                ->comment('Nama-nama yang diminta satu kelas (disimpan saat pendaftaran)');
            $table->enum('grouping_priority', ['none', 'low', 'medium', 'high'])->default('none')
                ->after('requested_with_names');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_siswas', function (Blueprint $table) {
            $table->dropForeign(['grouping_request_id']);
            $table->dropColumn(['grouping_request_id', 'requested_with_names', 'grouping_priority']);
        });
        Schema::dropIfExists('grouping_requests');
    }
};
