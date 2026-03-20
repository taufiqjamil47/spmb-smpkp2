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
            // Data pribadi tambahan
            $table->string('nik', 20)->unique()->nullable()->after('nisn');
            $table->string('no_telp', 15)->nullable()->after('no_hp_siswa');
            $table->integer('rt')->nullable()->after('alamat');
            $table->integer('rw')->nullable()->after('rt');
            $table->string('desa', 100)->nullable()->after('rw');
            $table->string('kecamatan', 100)->nullable()->after('desa');

            // Data kesehatan dan lainnya
            $table->integer('tinggi_badan')->nullable()->after('tahun_lulus');
            $table->integer('berat_badan')->nullable()->after('tinggi_badan');
            $table->integer('anak_ke')->nullable()->after('berat_badan');
            $table->enum('ukuran_baju', ['S', 'M', 'L', 'XL', 'XXL'])->nullable()->after('anak_ke');

            // Data program bantuan
            $table->string('pkh', 50)->nullable()->after('ukuran_baju');
            $table->string('kks', 50)->nullable()->after('pkh');
            $table->string('pip', 50)->nullable()->after('kks');

            // Data orang tua - tambahan
            $table->string('tahun_lahir_ayah', 4)->nullable()->after('nama_ayah');
            $table->enum('pekerjaan_ayah', [
                'Tidak Bekerja',
                'Nelayan',
                'Petani',
                'Peternak',
                'PNS/TNI/POLRI',
                'Karyawan Swasta',
                'Pedagang Kecil',
                'Pedagang Besar',
                'Wiraswasta',
                'Wirausaha',
                'Buruh',
                'Pensiunan',
                'Lainnya'
            ])->nullable()->after('tahun_lahir_ayah');
            $table->enum('pendidikan_ayah', [
                'Tidak Sekolah',
                'Putus SD',
                'SD Sederajat',
                'SMP',
                'SMA',
                'D1',
                'D2',
                'D3',
                'D4/S1',
                'S2',
                'S3'
            ])->nullable()->after('pekerjaan_ayah');

            $table->string('tahun_lahir_ibu', 4)->nullable()->after('nama_ibu');
            $table->enum('pekerjaan_ibu', [
                'Tidak Bekerja',
                'Nelayan',
                'Petani',
                'Peternak',
                'PNS/TNI/POLRI',
                'Karyawan Swasta',
                'Pedagang Kecil',
                'Pedagang Besar',
                'Wiraswasta',
                'Wirausaha',
                'Buruh',
                'Pensiunan',
                'Lainnya'
            ])->nullable()->after('tahun_lahir_ibu');
            $table->enum('pendidikan_ibu', [
                'Tidak Sekolah',
                'Putus SD',
                'SD Sederajat',
                'SMP',
                'SMA',
                'D1',
                'D2',
                'D3',
                'D4/S1',
                'S2',
                'S3'
            ])->nullable()->after('pekerjaan_ibu');

            // Data wali (opsional)
            $table->string('nama_wali', 100)->nullable()->after('pendidikan_ibu');
            $table->string('tahun_lahir_wali', 4)->nullable()->after('nama_wali');
            $table->enum('pekerjaan_wali', [
                'Tidak Bekerja',
                'Nelayan',
                'Petani',
                'Peternak',
                'PNS/TNI/POLRI',
                'Karyawan Swasta',
                'Pedagang Kecil',
                'Pedagang Besar',
                'Wiraswasta',
                'Wirausaha',
                'Buruh',
                'Pensiunan',
                'Lainnya'
            ])->nullable()->after('tahun_lahir_wali');
            $table->enum('pendidikan_wali', [
                'Tidak Sekolah',
                'Putus SD',
                'SD Sederajat',
                'SMP',
                'SMA',
                'D1',
                'D2',
                'D3',
                'D4/S1',
                'S2',
                'S3'
            ])->nullable()->after('pekerjaan_wali');
            // Data tambahan
            $table->string('slug')->unique()->nullable()->after('no_peserta');
            $table->string('periode')->nullable()->after('tahun_ajaran_id');
            $table->unsignedBigInteger('classroom_id')->nullable()->after('periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_siswas', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'no_telp',
                'rt',
                'rw',
                'desa',
                'kecamatan',
                'tinggi_badan',
                'berat_badan',
                'anak_ke',
                'ukuran_baju',
                'pkh',
                'kks',
                'pip',
                'tahun_lahir_ayah',
                'pekerjaan_ayah',
                'pendidikan_ayah',
                'tahun_lahir_ibu',
                'pekerjaan_ibu',
                'pendidikan_ibu',
                'nama_wali',
                'tahun_lahir_wali',
                'pekerjaan_wali',
                'pendidikan_wali',
                'slug',
                'periode',
                'classroom_id'
            ]);
        });
    }
};
