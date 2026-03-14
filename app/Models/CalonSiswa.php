<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini

class CalonSiswa extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan SoftDeletes

    protected $fillable = [
        'no_peserta',
        'tahun_ajaran_id',
        'nama_lengkap',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp_siswa',
        'sekolah_asal',
        'tahun_lulus',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ortu',
        'no_hp_ortu'
    ];

    protected $dates = ['deleted_at']; // Tambahkan ini

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenSiswa::class);
    }
}
