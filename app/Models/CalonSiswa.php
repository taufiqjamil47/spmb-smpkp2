<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini
use Illuminate\Support\Str;

class CalonSiswa extends Model
{
    use HasFactory, SoftDeletes; // Tambahkan SoftDeletes

    protected $table = 'calon_siswas';

    protected $fillable = [
        'no_peserta',
        'slug',
        'tahun_ajaran_id',
        'periode',
        'classroom_id',

        // Data pribadi
        'nama_lengkap',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'no_hp_siswa',
        'no_telp',

        // Data sekolah asal
        'sekolah_asal',
        'tahun_lulus',

        // Data kesehatan & lainnya
        'tinggi_badan',
        'berat_badan',
        'anak_ke',
        'ukuran_baju',

        // Data program bantuan
        'pkh',
        'kks',
        'pip',

        // Data ayah
        'nama_ayah',
        'tahun_lahir_ayah',
        'pekerjaan_ayah',
        'pendidikan_ayah',

        // Data ibu
        'nama_ibu',
        'tahun_lahir_ibu',
        'pekerjaan_ibu',
        'pendidikan_ibu',

        // Data wali (opsional)
        'nama_wali',
        'tahun_lahir_wali',
        'pekerjaan_wali',
        'pendidikan_wali',

        // Data tambahan
        'no_hp_ortu',
    ];

    protected $dates = ['deleted_at']; // Tambahkan ini

    protected static function boot()
    {
        parent::boot();

        // Membuat slug otomatis saat create
        static::creating(function ($siswa) {
            $siswa->slug = Str::slug($siswa->nama_lengkap . '-' . $siswa->nisn . '-' . uniqid());
        });

        // Update slug saat update nama
        static::updating(function ($siswa) {
            if ($siswa->isDirty('nama_lengkap')) {
                $siswa->slug = Str::slug($siswa->nama_lengkap . '-' . $siswa->nisn . '-' . uniqid());
            }
        });
    }

    /**
     * Relasi ke tahun ajaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Relasi ke dokumen
     */
    public function dokumen()
    {
        return $this->hasMany(DokumenSiswa::class, 'calon_siswa_id');
    }

    /**
     * Accessor untuk alamat lengkap
     */
    public function getAlamatLengkapAttribute()
    {
        $alamat = $this->alamat;
        $rt = $this->rt ? "RT.{$this->rt}" : '';
        $rw = $this->rw ? "RW.{$this->rw}" : '';
        $desa = $this->desa ?? '';
        $kecamatan = $this->kecamatan ?? '';

        return trim("{$alamat}, {$rt} {$rw}, {$desa}, {$kecamatan}");
    }

    /**
     * Accessor untuk nama orang tua lengkap
     */
    public function getNamaOrtuAttribute()
    {
        if ($this->nama_wali) {
            return "Wali: {$this->nama_wali}";
        }
        return "{$this->nama_ayah} & {$this->nama_ibu}";
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopePeriode($query, $periode)
    {
        return $query->where('periode', $periode);
    }

    /**
     * Scope untuk filter berdasarkan classroom
     */
    public function scopeKelas($query, $classroomId)
    {
        return $query->where('classroom_id', $classroomId);
    }
}
