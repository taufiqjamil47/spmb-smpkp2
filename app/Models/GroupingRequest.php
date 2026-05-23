<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupingRequest extends Model
{
    use HasFactory;

    protected $table = 'grouping_requests';

    protected $fillable = [
        'request_code',
        'group_name',
        'tahun_ajaran_id',
        'status',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relasi ke siswa-siswa dalam grup ini
    public function students()
    {
        return $this->hasMany(CalonSiswa::class, 'grouping_request_id');
    }

    // Relasi ke tahun ajaran
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Relasi ke user pembuat
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke user approval
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Generate kode unik
    public static function generateCode()
    {
        $prefix = 'GRP-' . date('Y') . '-';
        $last = self::where('request_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = intval(substr($last->request_code, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    // Status badge helper
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            'processing' => '<span class="badge bg-info">Diproses</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    // Approve the request
    public function approve($userId)
    {
        $this->status = 'approved';
        $this->approved_by = $userId;
        $this->approved_at = now();
        $this->save();

        // Update semua siswa dalam grup
        $this->students()->update(['grouping_priority' => 'high']);
    }

    // Reject the request
    public function reject()
    {
        $this->status = 'rejected';
        $this->save();

        // Clear grouping dari siswa
        $this->students()->update([
            'grouping_request_id' => null,
            'grouping_priority' => 'none'
        ]);
    }
}
