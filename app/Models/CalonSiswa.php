<?php

namespace App\Models;

use App\Models\GroupingRequest;
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

        'grouping_request_id',  // TAMBAHKAN
        'requested_with_names', // TAMBAHKAN
        'grouping_priority',    // TAMBAHKAN
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

    // Relasi ke grouping request
    public function groupingRequest()
    {
        return $this->belongsTo(GroupingRequest::class);
    }

    // Get all requested students (dari requested_with_names)
    public function getRequestedStudentsAttribute()
    {
        if (!$this->requested_with_names) {
            return collect();
        }

        $names = explode('|', $this->requested_with_names);
        return CalonSiswa::whereIn('nama_lengkap', $names)
            ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
            ->get();
    }

    // Get formatted requested names
    public function getFormattedRequestedNamesAttribute()
    {
        if (!$this->requested_with_names) {
            return '-';
        }
        return str_replace('|', ', ', $this->requested_with_names);
    }

    // Check if student has grouping request
    public function hasGroupingRequest()
    {
        return !is_null($this->grouping_request_id) || !empty($this->requested_with_names);
    }

    // Scope untuk filter berdasarkan grouping status
    public function scopeHasGroupingRequest($query)
    {
        return $query->whereNotNull('grouping_request_id')
            ->orWhereNotNull('requested_with_names');
    }

    public function scopeWithoutGrouping($query)
    {
        return $query->whereNull('grouping_request_id')
            ->whereNull('requested_with_names');
    }
}
