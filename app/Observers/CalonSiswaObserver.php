<?php

namespace App\Observers;

use App\Models\CalonSiswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Log;

class CalonSiswaObserver
{
    /**
     * Handle the CalonSiswa "deleted" event.
     * Dipanggil ketika soft delete terjadi
     */
    public function deleted(CalonSiswa $calonSiswa): void
    {
        // Jika siswa yang dihapus adalah status ACCEPTED
        // Cek apakah ada siswa dalam antrian untuk dipromosikan
        if ($calonSiswa->status === 'accepted') {
            $this->promoteFromWaitingList($calonSiswa->tahun_ajaran_id);
        }
    }

    /**
     * Handle the CalonSiswa "restored" event.
     * Dipanggil ketika soft delete di-restore
     */
    public function restored(CalonSiswa $calonSiswa): void
    {
        // Jika siswa yang di-restore adalah ACCEPTED
        // Cek apakah ada siswa di-demote dari accepted ke waiting
        // (misal jika kuota sudah penuh lagi saat di-restore)
        if ($calonSiswa->status === 'accepted') {
            $this->demoteIfNeeded($calonSiswa->tahun_ajaran_id);
        }
    }

    /**
     * Promote siswa dari waiting list ke accepted
     */
    private function promoteFromWaitingList(int $tahunAjaranId): void
    {
        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);

        // Hitung jumlah siswa ACCEPTED (tidak termasuk yang di-trash)
        $jumlahAccepted = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranId)
            ->accepted()
            ->count();

        // Jika masih ada kuota, promote siswa pertama dari antrian
        while ($jumlahAccepted < $tahunAjaran->kuota) {
            $firstInQueue = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranId)
                ->waiting()
                ->orderBy('queue_date', 'asc') // FIFO
                ->first();

            if (!$firstInQueue) {
                break; // Tidak ada yang dalam antrian
            }

            $firstInQueue->update([
                'status' => 'accepted',
                'queue_position' => null,
                'promoted_at' => now(),
            ]);

            Log::info("Siswa dipromosikan dari waiting list", [
                'siswa_id' => $firstInQueue->id,
                'nama' => $firstInQueue->nama_lengkap,
                'tahun_ajaran_id' => $tahunAjaranId
            ]);

            $jumlahAccepted++;
        }
    }

    /**
     * Demote siswa dari accepted ke waiting jika perlu
     * (Jika kuota penuh saat restore)
     */
    private function demoteIfNeeded(int $tahunAjaranId): void
    {
        $tahunAjaran = TahunAjaran::findOrFail($tahunAjaranId);

        // Hitung jumlah siswa ACCEPTED
        $jumlahAccepted = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranId)
            ->accepted()
            ->count();

        // Jika melampaui kuota, demote siswa-siswa terakhir ke waiting
        if ($jumlahAccepted > $tahunAjaran->kuota) {
            $excess = $jumlahAccepted - $tahunAjaran->kuota;

            // Ambil siswa ACCEPTED yang paling akhir untuk di-demote
            $todemote = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranId)
                ->accepted()
                ->orderBy('created_at', 'desc')
                ->limit($excess)
                ->get();

            // Hitung posisi antrian terbaru
            $lastQueue = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranId)
                ->waiting()
                ->orderBy('queue_position', 'desc')
                ->first();
            $nextQueuePosition = ($lastQueue ? $lastQueue->queue_position : 0) + 1;

            foreach ($todemote as $student) {
                $student->update([
                    'status' => 'waiting',
                    'queue_position' => $nextQueuePosition,
                    'queue_date' => now(),
                ]);

                Log::info("Siswa di-demote ke waiting list", [
                    'siswa_id' => $student->id,
                    'nama' => $student->nama_lengkap,
                    'tahun_ajaran_id' => $tahunAjaranId
                ]);

                $nextQueuePosition++;
            }
        }
    }
}
