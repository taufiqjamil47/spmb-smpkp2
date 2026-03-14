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
        Schema::create('calon_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('no_peserta', 20)->unique();
            $table->foreignId('tahun_ajaran_id')->constrained();
            $table->string('nama_lengkap', 100);
            $table->string('nisn', 20)->unique();
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 20);
            $table->text('alamat');
            $table->string('no_hp_siswa', 15);
            $table->string('sekolah_asal', 100);
            $table->year('tahun_lulus');
            $table->string('nama_ayah', 100);
            $table->string('nama_ibu', 100);
            $table->string('pekerjaan_ortu', 50);
            $table->string('no_hp_ortu', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_siswas');
    }
};
