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
        Schema::table('calon_siswas', function (Blueprint $table) {
            // Status pendaftaran: accepted (diterima), waiting (antrian), rejected (ditolak)
            $table->enum('status', ['accepted', 'waiting', 'rejected'])->default('accepted')->after('classroom_id');

            // Posisi dalam antrian (null jika tidak dalam antrian)
            $table->unsignedInteger('queue_position')->nullable()->after('status');

            // Tanggal masuk antrian
            $table->timestamp('queue_date')->nullable()->after('queue_position');

            // Tanggal dipromosikan dari antrian ke accepted
            $table->timestamp('promoted_at')->nullable()->after('queue_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_siswas', function (Blueprint $table) {
            $table->dropColumn(['status', 'queue_position', 'queue_date', 'promoted_at']);
        });
    }
};
