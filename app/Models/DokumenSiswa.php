<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenSiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'calon_siswa_id',
        'jenis_dokumen',
        'nama_file',
        'path'
    ];

    public function calonSiswa()
    {
        return $this->belongsTo(CalonSiswa::class);
    }
}
