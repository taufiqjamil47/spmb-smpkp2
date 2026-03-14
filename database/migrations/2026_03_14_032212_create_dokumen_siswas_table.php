<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('dokumen_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calon_siswa_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_dokumen', ['ijazah', 'kk', 'foto']);
            $table->string('nama_file', 255);
            $table->string('path', 255);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_siswas');
    }
};
