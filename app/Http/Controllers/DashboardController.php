<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\GroupingRequest;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')
            ->withCount('calonSiswa')
            ->first();

        // Statistik umum
        $totalPendaftar = CalonSiswa::count();
        $totalTahunAjaran = TahunAjaran::count();

        // Data untuk grafik per tahun ajaran
        $dataTahunAjaran = TahunAjaran::withCount('calonSiswa')
            ->orderBy('tahun_ajaran', 'desc')
            ->take(5)
            ->get();

        $chartLabels = $dataTahunAjaran->pluck('tahun_ajaran');
        $chartData = $dataTahunAjaran->pluck('calon_siswa_count');

        // Get filter parameters
        $filterType = $request->get('filter_type', 'monthly'); // monthly, daily, yearly
        $selectedYear = $request->get('year', date('Y'));
        $selectedMonth = $request->get('month', date('m'));
        $selectedDate = $request->get('date', date('Y-m-d'));

        // Data pendaftar dengan filter dinamis
        $chartDataFiltered = [];
        $chartLabelsFiltered = [];

        if ($filterType == 'daily') {
            // Get daily data for selected date
            $dailyData = CalonSiswa::whereDate('created_at', $selectedDate)
                ->selectRaw("HOUR(created_at) as jam, COUNT(*) as jumlah")
                ->groupBy('jam')
                ->orderBy('jam')
                ->pluck('jumlah', 'jam')
                ->toArray();

            for ($i = 0; $i <= 23; $i++) {
                $chartLabelsFiltered[] = sprintf("%02d:00", $i);
                $chartDataFiltered[] = $dailyData[$i] ?? 0;
            }
        } elseif ($filterType == 'monthly') {
            // Get monthly data for selected year
            $monthlyData = CalonSiswa::whereYear('created_at', $selectedYear)
                ->selectRaw("MONTH(created_at) as bulan, COUNT(*) as jumlah")
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->pluck('jumlah', 'bulan')
                ->toArray();

            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            foreach (range(1, 12) as $bulanNum) {
                $chartLabelsFiltered[] = $months[$bulanNum - 1];
                $chartDataFiltered[] = $monthlyData[$bulanNum] ?? 0;
            }
        } else { // yearly
            // Get yearly data for last 5 years
            $startYear = $selectedYear - 4;
            $yearlyData = CalonSiswa::whereYear('created_at', '>=', $startYear)
                ->selectRaw("YEAR(created_at) as tahun, COUNT(*) as jumlah")
                ->groupBy('tahun')
                ->orderBy('tahun')
                ->pluck('jumlah', 'tahun')
                ->toArray();

            for ($i = $startYear; $i <= $selectedYear; $i++) {
                $chartLabelsFiltered[] = $i;
                $chartDataFiltered[] = $yearlyData[$i] ?? 0;
            }
        }

        // Get tahun ajaran options for filter (untuk dropdown tahun)
        $availableYears = CalonSiswa::selectRaw("YEAR(created_at) as tahun")
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Data pendaftar per bulan (untuk tahun aktif) - Keep for backward compatibility
        $pendaftarPerBulan = [];
        if ($tahunAjaranAktif) {
            $bulanData = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->selectRaw("MONTH(created_at) as bulan, COUNT(*) as jumlah")
                ->groupByRaw("MONTH(created_at)")
                ->pluck('jumlah', 'bulan')
                ->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $bulan = date('F', mktime(0, 0, 0, $i, 1));
                $pendaftarPerBulan[$bulan] = $bulanData[$i] ?? 0;
            }
        }

        // Data untuk tabel recent pendaftar
        $recentPendaftar = CalonSiswa::with('tahunAjaran')
            ->latest()
            ->take(5)
            ->get();

        // ========== FITUR AUTO-DETECT MUTUAL REQUEST ==========
        $groupingStats = [];
        $mutualRequests = [];
        $pendingGroupings = collect();

        $isAdmin = Auth::user()->role === 'admin';

        if ($isAdmin) {
            $groupingStats = $this->getGroupingStats();
            $mutualRequests = $this->detectMutualRequests($request);
            $pendingGroupings = GroupingRequest::with(['students', 'tahunAjaran'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact(
            'tahunAjaranAktif',
            'totalPendaftar',
            'totalTahunAjaran',
            'chartLabels',
            'chartData',
            'pendaftarPerBulan',
            'recentPendaftar',
            'groupingStats',
            'mutualRequests',
            'pendingGroupings',
            'filterType',
            'selectedYear',
            'selectedMonth',
            'selectedDate',
            'chartLabelsFiltered',
            'chartDataFiltered',
            'availableYears'
        ));
    }

    // API endpoint untuk mendapatkan data chart dinamis (untuk AJAX)
    public function apiChartData(Request $request)
    {
        $filterType = $request->get('filter_type', 'monthly');
        $selectedYear = $request->get('year', date('Y'));
        $selectedMonth = $request->get('month', date('m'));
        $selectedDate = $request->get('date', date('Y-m-d'));

        $chartData = [];
        $chartLabels = [];

        if ($filterType == 'daily') {
            $dailyData = CalonSiswa::whereDate('created_at', $selectedDate)
                ->selectRaw("HOUR(created_at) as jam, COUNT(*) as jumlah")
                ->groupBy('jam')
                ->orderBy('jam')
                ->pluck('jumlah', 'jam')
                ->toArray();

            for ($i = 0; $i <= 23; $i++) {
                $chartLabels[] = sprintf("%02d:00", $i);
                $chartData[] = $dailyData[$i] ?? 0;
            }
        } elseif ($filterType == 'monthly') {
            $monthlyData = CalonSiswa::whereYear('created_at', $selectedYear)
                ->selectRaw("MONTH(created_at) as bulan, COUNT(*) as jumlah")
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->pluck('jumlah', 'bulan')
                ->toArray();

            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            foreach (range(1, 12) as $bulanNum) {
                $chartLabels[] = $months[$bulanNum - 1];
                $chartData[] = $monthlyData[$bulanNum] ?? 0;
            }
        } else {
            $startYear = $selectedYear - 4;
            $yearlyData = CalonSiswa::whereYear('created_at', '>=', $startYear)
                ->selectRaw("YEAR(created_at) as tahun, COUNT(*) as jumlah")
                ->groupBy('tahun')
                ->orderBy('tahun')
                ->pluck('jumlah', 'tahun')
                ->toArray();

            for ($i = $startYear; $i <= $selectedYear; $i++) {
                $chartLabels[] = $i;
                $chartData[] = $yearlyData[$i] ?? 0;
            }
        }

        return response()->json([
            'success' => true,
            'labels' => $chartLabels,
            'data' => $chartData,
            'filter_type' => $filterType
        ]);
    }

    // Other methods (getGroupingStats, detectMutualRequests, etc.) remain the same
    public function getGroupingStats()
    {
        $pendingGroups = GroupingRequest::where('status', 'pending')->count();
        $pendingRequests = CalonSiswa::whereNotNull('requested_with_names')->count();

        $mutualDetected = CalonSiswa::whereNotNull('requested_with_names')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('calon_siswas as cs2')
                    ->whereRaw('cs2.requested_with_names LIKE CONCAT("%", calon_siswas.nama_lengkap, "%")')
                    ->whereColumn('cs2.tahun_ajaran_id', 'calon_siswas.tahun_ajaran_id');
            })->count();

        $totalGrouped = CalonSiswa::whereNotNull('grouping_request_id')->count();

        $totalRequests = $pendingRequests + GroupingRequest::count();
        $processedRequests = $totalRequests > 0 ? round(($pendingGroups / $totalRequests) * 100) : 0;

        return compact('pendingGroups', 'pendingRequests', 'mutualDetected', 'totalGrouped', 'processedRequests');
    }

    public function detectMutualRequests(Request $request)
    {
        $tahunAjaranId = $request->get('tahun', null);

        $query = CalonSiswa::whereNotNull('requested_with_names')
            ->where('requested_with_names', '!=', '')
            ->with('tahunAjaran');

        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        $studentsWithRequests = $query->get();

        $requestMap = [];
        $studentMap = [];

        foreach ($studentsWithRequests as $student) {
            $studentUpper = strtoupper($student->nama_lengkap);
            $requestedNames = explode('|', $student->requested_with_names);
            $requestedNames = array_map('trim', $requestedNames);
            $requestedNames = array_map('strtoupper', $requestedNames);

            $studentMap[$student->id] = [
                'student' => $student,
                'requested_names' => $requestedNames,
                'requested_names_upper' => $requestedNames
            ];

            foreach ($requestedNames as $name) {
                if (!isset($requestMap[$name])) {
                    $requestMap[$name] = [];
                }
                $requestMap[$name][] = $student->id;
            }
        }

        $mutualPairs = [];

        foreach ($studentMap as $studentId => $studentData) {
            $student = $studentData['student'];
            $requestedNames = $studentData['requested_names'];

            foreach ($requestedNames as $requestedName) {
                if (isset($requestMap[$requestedName])) {
                    foreach ($requestMap[$requestedName] as $potentialMatchId) {
                        if ($studentId === $potentialMatchId) continue;

                        $potentialMatch = $studentsWithRequests->find(function ($s) use ($potentialMatchId) {
                            return $s->id === $potentialMatchId;
                        });

                        if (!$potentialMatch) continue;

                        $potentialMatchWants = in_array(strtoupper($student->nama_lengkap), $studentMap[$potentialMatchId]['requested_names']);

                        if ($potentialMatchWants) {
                            $pairKey = min($studentId, $potentialMatchId) . '-' . max($studentId, $potentialMatchId);

                            if (!isset($mutualPairs[$pairKey])) {
                                $mutualPairs[$pairKey] = [
                                    'student1' => $student,
                                    'student2' => $potentialMatch,
                                    'other_names' => array_diff($requestedNames, [strtoupper($potentialMatch->nama_lengkap)]),
                                    'detected_at' => now()
                                ];
                            }
                        }
                    }
                }
            }
        }

        return array_values($mutualPairs);
    }

    public function apiMutualRequests(Request $request)
    {
        $mutualRequests = $this->detectMutualRequests($request);

        return response()->json([
            'success' => true,
            'data' => $mutualRequests,
            'count' => count($mutualRequests)
        ]);
    }

    public function apiTotalStudents()
    {
        return response()->json([
            'success' => true,
            'total' => CalonSiswa::count(),
        ]);
    }

    public function createGroupingFromMutual(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:2',
            'student_ids.*' => 'exists:calon_siswas,id',
            'group_name' => 'nullable|string|max:100'
        ]);

        $students = CalonSiswa::whereIn('id', $request->student_ids)->get();

        if ($students->count() < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal 2 siswa untuk membuat grouping'
            ], 422);
        }

        $tahunAjaranId = $students->first()->tahun_ajaran_id;
        $groupName = $request->group_name ?? 'Mutual Request: ' . $students->pluck('nama_lengkap')->implode(', ');

        DB::beginTransaction();
        try {
            $grouping = GroupingRequest::create([
                'request_code' => GroupingRequest::generateCode(),
                'group_name' => $groupName,
                'tahun_ajaran_id' => $tahunAjaranId,
                'status' => 'pending',
                'notes' => 'Auto-detected mutual request - ' . now()->format('d/m/Y H:i'),
                'created_by' => Auth::id(),
            ]);

            CalonSiswa::whereIn('id', $request->student_ids)->update([
                'grouping_request_id' => $grouping->id,
                'grouping_priority' => 'high',
                'requested_with_names' => null
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Grouping berhasil dibuat',
                'redirect_url' => route('groupings.show', $grouping->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat grouping: ' . $e->getMessage()
            ], 500);
        }
    }
}
