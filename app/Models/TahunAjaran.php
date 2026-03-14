<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran',
        'kuota',
        'status'
    ];

    public function calonSiswa()
    {
        return $this->hasMany(CalonSiswa::class);
    }
}
