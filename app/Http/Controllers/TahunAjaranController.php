<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use App\Models\CalonSiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAjaran = TahunAjaran::withCount('calonSiswa')
            ->orderBy('tahun_ajaran', 'desc')
            ->get();

        return view('tahun-ajaran.index', compact('tahunAjaran'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tahun-ajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                'unique:tahun_ajarans,tahun_ajaran',
                'regex:/^\d{4}\/\d{4}$/' // Format: YYYY/YYYY
            ],
            'kuota' => 'required|integer|min:1|max:9999',
            'status' => 'required|in:aktif,tidak_aktif'
        ], [
            'tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2024/2025)',
            'tahun_ajaran.unique' => 'Tahun ajaran ini sudah terdaftar',
            'kuota.max' => 'Kuota maksimal 9999 siswa'
        ]);

        // Jika status aktif, nonaktifkan semua yang aktif
        if ($request->status == 'aktif') {
            TahunAjaran::where('status', 'aktif')->update(['status' => 'tidak_aktif']);
        }

        TahunAjaran::create([
            'tahun_ajaran' => $request->tahun_ajaran,
            'kuota' => $request->kuota,
            'status' => $request->status
        ]);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran ' . $request->tahun_ajaran . ' berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('tahun-ajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'tahun_ajaran' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tahun_ajarans')->ignore($tahunAjaran->id),
                'regex:/^\d{4}\/\d{4}$/'
            ],
            'kuota' => 'required|integer|min:1|max:9999',
            'status' => 'required|in:aktif,tidak_aktif'
        ], [
            'tahun_ajaran.regex' => 'Format tahun ajaran harus YYYY/YYYY (contoh: 2024/2025)',
            'kuota.max' => 'Kuota maksimal 9999 siswa'
        ]);

        // Cek apakah kuota baru lebih kecil dari jumlah pendaftar yang sudah ada
        $jumlahPendaftar = $tahunAjaran->calonSiswa()->count();
        if ($request->kuota < $jumlahPendaftar) {
            return back()
                ->withInput()
                ->withErrors(['kuota' => 'Kuota tidak boleh kurang dari jumlah pendaftar yang sudah ada (' . $jumlahPendaftar . ' siswa)']);
        }

        // Jika status diubah menjadi aktif, nonaktifkan yang lain
        if ($request->status == 'aktif' && $tahunAjaran->status != 'aktif') {
            TahunAjaran::where('status', 'aktif')
                ->where('id', '!=', $tahunAjaran->id)
                ->update(['status' => 'tidak_aktif']);
        }

        $tahunAjaran->update([
            'tahun_ajaran' => $request->tahun_ajaran,
            'kuota' => $request->kuota,
            'status' => $request->status
        ]);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran ' . $request->tahun_ajaran . ' berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        // Cek apakah ada pendaftar di tahun ajaran ini
        $jumlahPendaftar = $tahunAjaran->calonSiswa()->count();

        if ($jumlahPendaftar > 0) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus tahun ajaran yang sudah memiliki ' . $jumlahPendaftar . ' pendaftar.');
        }

        // Cek apakah ini tahun ajaran aktif
        if ($tahunAjaran->status == 'aktif') {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif. Nonaktifkan terlebih dahulu.');
        }

        $tahunAjaran->delete();

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran ' . $tahunAjaran->tahun_ajaran . ' berhasil dihapus.');
    }

    /**
     * Set tahun ajaran aktif (fungsi tambahan)
     */
    public function setAktif($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        // Nonaktifkan semua
        TahunAjaran::where('status', 'aktif')->update(['status' => 'tidak_aktif']);

        // Aktifkan yang dipilih
        $tahunAjaran->update(['status' => 'aktif']);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran ' . $tahunAjaran->tahun_ajaran . ' sekarang aktif.');
    }
}
