@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Dashboard</h1>
        <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}! ({{ ucfirst(auth()->user()->role) }})</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-full text-white mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pendaftar</p>
                    <p class="text-2xl font-bold">{{ number_format($totalPendaftar) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-full text-white mr-4">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Tahun Aktif</p>
                    <p class="text-2xl font-bold">{{ $tahunAjaranAktif ? $tahunAjaranAktif->tahun_ajaran : '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 rounded-full text-white mr-4">
                    <i class="fas fa-layer-group text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Tahun Ajaran</p>
                    <p class="text-2xl font-bold">{{ $totalTahunAjaran }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-full text-white mr-4">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Rata-rata/Tahun</p>
                    <p class="text-2xl font-bold">
                        {{ $totalTahunAjaran > 0 ? round($totalPendaftar / $totalTahunAjaran) : 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @if (auth()->user()->role === 'admin')
        <!-- Widget Grouping Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Pending Groups</p>
                        <p class="text-2xl font-bold">{{ $groupingStats['pendingGroups'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-user-friends text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Request Satu Kelas</p>
                        <p class="text-2xl font-bold">{{ $groupingStats['pendingRequests'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Sudah Dikelompokkan</p>
                        <p class="text-2xl font-bold">{{ $groupingStats['totalGrouped'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-handshake text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-500 text-sm">Mutual Request</p>
                        <p class="text-2xl font-bold">{{ $groupingStats['mutualDetected'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto-Detect Mutual Request Section -->
        @if (isset($mutualRequests) && count($mutualRequests) > 0)
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="border-b px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold">
                            <i class="fas fa-handshake text-green-600 mr-2"></i>
                            Auto-Detect Mutual Request
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Siswa yang saling meminta satu kelas - segera buat grouping untuk memudahkan pembagian kelas
                        </p>
                    </div>
                    <button onclick="refreshMutualRequests()" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa 1</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa 2</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request Lainnya
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($mutualRequests as $mutual)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $mutual['student1']->nama_lengkap }}
                                        </div>
                                        <div class="text-xs text-gray-500">No: {{ $mutual['student1']->no_peserta }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $mutual['student2']->nama_lengkap }}
                                        </div>
                                        <div class="text-xs text-gray-500">No: {{ $mutual['student2']->no_peserta }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if (count($mutual['other_names']) > 0)
                                            {{ implode(', ', $mutual['other_names']) }}
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        {{ $mutual['student1']->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <button
                                            onclick="createGroupingFromMutual({{ json_encode([$mutual['student1']->id, $mutual['student2']->id]) }})"
                                            class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                                            <i class="fas fa-users"></i> Buat Grouping
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Pending Groupings -->
        @if (isset($pendingGroupings) && $pendingGroupings->count() > 0)
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="border-b px-6 py-4">
                    <h2 class="text-xl font-semibold">
                        <i class="fas fa-hourglass-half text-yellow-600 mr-2"></i>
                        Pending Grouping Requests
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Grouping yang menunggu persetujuan</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Group</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Group</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Siswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Request
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingGroupings as $group)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono text-sm">{{ $group->request_code }}</td>
                                    <td class="px-6 py-4">{{ $group->group_name }}</td>
                                    <td class="px-6 py-4">{{ $group->students->count() }} siswa</td>
                                    <td class="px-6 py-4 text-sm">{{ $group->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('groupings.show', $group->id) }}"
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        <!-- JavaScript untuk AJAX -->
        @push('scripts')
            <script>
                function refreshMutualRequests() {
                    fetch('{{ route('dashboard.api.mutual') }}')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload(); // Reload page to show updated data
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }

                function createGroupingFromMutual(studentIds) {
                    if (!confirm('Buat grouping untuk siswa-siswa ini? Mereka akan ditempatkan dalam satu grup.')) {
                        return;
                    }

                    fetch('{{ route('dashboard.create-grouping') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                student_ids: studentIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = data.redirect_url;
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan, silakan coba lagi.');
                        });
                }
            </script>
        @endpush
    @endif

    @if ($tahunAjaranAktif)
        @php
            // Use the eager-loaded count from controller
            $jumlahPendaftar = $tahunAjaranAktif->calon_siswa_count ?? 0;
            $sisaKuota = $tahunAjaranAktif->kuota - $jumlahPendaftar;
            $persentase = round(($jumlahPendaftar / $tahunAjaranAktif->kuota) * 100);

            if ($persentase >= 100) {
                $warnaProgress = 'bg-red-500';
                $statusText = 'Kuota Penuh';
            } elseif ($persentase >= 75) {
                $warnaProgress = 'bg-yellow-500';
                $statusText = 'Hampir Penuh';
            } else {
                $warnaProgress = 'bg-green-500';
                $statusText = 'Tersedia';
            }
        @endphp

        <!-- Progress Kuota -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Progress Kuota {{ $tahunAjaranAktif->tahun_ajaran }}</h2>
                <span
                    class="px-3 py-1 bg-{{ $warnaProgress == 'bg-red-500' ? 'red' : ($warnaProgress == 'bg-yellow-500' ? 'yellow' : 'green') }}-100 text-{{ $warnaProgress == 'bg-red-500' ? 'red' : ($warnaProgress == 'bg-yellow-500' ? 'yellow' : 'green') }}-800 rounded-full text-sm font-semibold">
                    {{ $statusText }}
                </span>
            </div>

            <div class="flex items-center mb-2">
                <div class="flex-1">
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="{{ $warnaProgress }} h-4 rounded-full transition-all duration-500"
                            style="width: {{ $persentase }}%"></div>
                    </div>
                </div>
                <span class="ml-4 font-bold text-lg">{{ $persentase }}%</span>
            </div>

            <div class="flex justify-between text-sm text-gray-600">
                <span>{{ number_format($jumlahPendaftar) }} dari {{ number_format($tahunAjaranAktif->kuota) }}
                    siswa</span>
                <span class="font-bold {{ $sisaKuota < 10 ? 'text-red-600' : 'text-green-600' }}">
                    Sisa: {{ number_format($sisaKuota) }}
                </span>
            </div>
        </div>

        <!-- Grafik Pendaftar per Bulan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Grafik Pendaftar {{ $tahunAjaranAktif->tahun_ajaran }}</h2>
                <div class="h-64 flex items-end space-x-2">
                    @foreach ($pendaftarPerBulan as $bulan => $jumlah)
                        @php
                            $tinggi = $jumlah * 8; // 1 siswa = 8px tinggi
                            $warna = $jumlah > 0 ? 'bg-blue-500' : 'bg-gray-200';
                        @endphp
                        <div class="flex-1 flex flex-col items-center group">
                            <div class="relative w-full">
                                <div class="{{ $warna }} rounded-t transition-all duration-300 group-hover:bg-blue-600"
                                    style="height: {{ $tinggi > 0 ? $tinggi : 4 }}px"></div>
                                @if ($jumlah > 0)
                                    <div
                                        class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        {{ $jumlah }} siswa
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs mt-2 text-gray-600">{{ substr($bulan, 0, 3) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Grafik Perbandingan Tahun Ajaran -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Perbandingan 5 Tahun Terakhir</h2>
                <canvas id="chartTahunAjaran" class="h-64"></canvas>
            </div>
        </div>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                </div>
                <div>
                    <p class="font-bold">Perhatian!</p>
                    <p>Belum ada tahun ajaran yang aktif. Silakan <a href="{{ route('tahun-ajaran.index') }}"
                            class="underline">atur tahun ajaran</a> terlebih dahulu.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Pendaftar -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Pendaftar Terbaru</h2>
            <a href="{{ route('pendaftaran.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentPendaftar as $siswa)
                        <tr>
                            <td class="px-6 py-4 font-mono text-sm">{{ $siswa->no_peserta }}</td>
                            <td class="px-6 py-4">{{ $siswa->nama_lengkap }}</td>
                            <td class="px-6 py-4">{{ $siswa->tahunAjaran->tahun_ajaran }}</td>
                            <td class="px-6 py-4">{{ $siswa->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('pendaftaran.show', $siswa->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pendaftaran.cetak', $siswa->id) }}" target="_blank"
                                    class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data pendaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart untuk perbandingan tahun ajaran
            const ctx = document.getElementById('chartTahunAjaran').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Pendaftar',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
