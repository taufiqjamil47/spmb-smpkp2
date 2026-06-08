@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="div mt-8">
        <!-- Welcome Section dengan Background Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg>
            </div>
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
                <p class="text-indigo-100">Selamat datang kembali, {{ auth()->user()->name }}!
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Statistik Cards dengan Desain Modern -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card Total Pendaftar -->
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <span class="text-3xl font-bold text-gray-800">{{ number_format($totalPendaftar) }}</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Total Pendaftar</p>
                    {{-- <div class="mt-2 h-1 bg-blue-100 rounded-full">
                        <div class="w-3/4 h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></div>
                    </div> --}}
                </div>
            </div>

            <!-- Card Tahun Aktif -->
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>
                        <span
                            class="text-xl font-bold text-gray-800 truncate max-w-[150px]">{{ $tahunAjaranAktif ? $tahunAjaranAktif->tahun_ajaran : '-' }}</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tahun Ajaran Aktif</p>
                    {{-- <div class="mt-2 h-1 bg-green-100 rounded-full">
                        <div class="w-full h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                    </div> --}}
                </div>
            </div>

            <!-- Card Total Tahun Ajaran -->
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                            <i class="fas fa-layer-group text-white text-xl"></i>
                        </div>
                        <span class="text-3xl font-bold text-gray-800">{{ $totalTahunAjaran }}</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Total Tahun Ajaran</p>
                    {{-- <div class="mt-2 h-1 bg-purple-100 rounded-full">
                        <div class="w-2/3 h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full"></div>
                    </div> --}}
                </div>
            </div>

            <!-- Card Rata-rata -->
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <span class="text-3xl font-bold text-gray-800">
                            {{ $totalTahunAjaran > 0 ? round($totalPendaftar / $totalTahunAjaran) : 0 }}
                        </span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Rata-rata/Tahun</p>
                    {{-- <div class="mt-2 h-1 bg-yellow-100 rounded-full">
                        <div class="w-1/2 h-full bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full"></div>
                    </div> --}}
                </div>
            </div>
        </div>

        @if (auth()->user()->role === 'admin')
            <!-- Widget Grouping Stats dengan Desain Elegant -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div
                    class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl shadow-md p-6 border border-yellow-200 hover:shadow-lg transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-800 text-sm font-semibold mb-1">Pending Request</p>
                            <p class="text-3xl font-bold text-yellow-900">{{ $groupingStats['pendingGroups'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-yellow-500 rounded-xl shadow-md">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-md p-6 border border-blue-200 hover:shadow-lg transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-800 text-sm font-semibold mb-1">Request Satu Kelas</p>
                            <p class="text-3xl font-bold text-blue-900">{{ $groupingStats['pendingRequests'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-blue-500 rounded-xl shadow-md">
                            <i class="fas fa-user-friends text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-md p-6 border border-green-200 hover:shadow-lg transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-800 text-sm font-semibold mb-1">Sudah Dikelompokkan</p>
                            <p class="text-3xl font-bold text-green-900">{{ $groupingStats['totalGrouped'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-500 rounded-xl shadow-md">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl shadow-md p-6 border border-red-200 hover:shadow-lg transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-800 text-sm font-semibold mb-1">Mutual Request</p>
                            <p class="text-3xl font-bold text-red-900">{{ $groupingStats['mutualDetected'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-red-500 rounded-xl shadow-md">
                            <i class="fas fa-handshake text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auto-Detect Mutual Request Section dengan Desain Premium -->
            @if (isset($mutualRequests) && count($mutualRequests) > 0)
                <div class="bg-white rounded-2xl shadow-xl mb-8 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-white">
                                <i class="fas fa-handshake mr-2"></i>
                                Auto-Detect Mutual Request
                            </h2>
                            <p class="text-green-100 text-sm mt-1">
                                Siswa yang saling meminta satu kelas - segera buat grouping untuk memudahkan pembagian kelas
                            </p>
                        </div>
                        <button onclick="refreshMutualRequests()"
                            class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-xl transition-all">
                            <i class="fas fa-sync-alt mr-2"></i> Refresh
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Siswa 1</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Siswa 2</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Request Lainnya</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tahun Ajaran</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($mutualRequests as $mutual)
                                    <tr class="hover:bg-green-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">
                                                {{ $mutual['student1']->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-500">No: {{ $mutual['student1']->no_peserta }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">
                                                {{ $mutual['student2']->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-500">No: {{ $mutual['student2']->no_peserta }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            @if (count($mutual['other_names']) > 0)
                                                <span class="inline-flex flex-wrap gap-1">
                                                    @foreach (array_slice($mutual['other_names'], 0, 3) as $name)
                                                        <span
                                                            class="bg-gray-100 px-2 py-0.5 rounded-full text-xs">{{ $name }}</span>
                                                    @endforeach
                                                    @if (count($mutual['other_names']) > 3)
                                                        <span
                                                            class="text-gray-400 text-xs">+{{ count($mutual['other_names']) - 3 }}</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs">{{ $mutual['student1']->tahunAjaran->tahun_ajaran ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button
                                                onclick="createGroupingFromMutual({{ json_encode([$mutual['student1']->id, $mutual['student2']->id]) }})"
                                                class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-all shadow-md hover:shadow-lg">
                                                <i class="fas fa-users mr-1"></i> Buat Grouping
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Pending Groupings dengan Desain Modern -->
            @if (isset($pendingGroupings) && $pendingGroupings->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl mb-8 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-600 to-orange-600 px-6 py-4">
                        <h2 class="text-xl font-semibold text-white">
                            <i class="fas fa-hourglass-half mr-2"></i>
                            Pending Grouping Requests
                        </h2>
                        <p class="text-yellow-100 text-sm mt-1">Grouping yang menunggu persetujuan</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kode Group</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Group</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jumlah Siswa</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Request</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($pendingGroupings as $group)
                                    <tr class="hover:bg-yellow-50 transition-colors">
                                        <td class="px-6 py-4 font-mono text-sm font-semibold text-gray-700">
                                            {{ $group->request_code }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $group->group_name }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $group->students->count() }} siswa
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $group->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('groupings.show', $group->id) }}"
                                                class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @push('scripts')
                <script>
                    function refreshMutualRequests() {
                        fetch('{{ route('dashboard.api.mutual') }}')
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
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
                $jumlahPendaftar = $tahunAjaranAktif->calon_siswa_count ?? 0;
                $sisaKuota = $tahunAjaranAktif->kuota - $jumlahPendaftar;
                $persentase = round(($jumlahPendaftar / $tahunAjaranAktif->kuota) * 100);

                if ($persentase >= 100) {
                    $warnaProgress = 'from-red-500 to-red-600';
                    $warnaBg = 'bg-red-50';
                    $statusText = 'Kuota Penuh';
                    $statusColor = 'text-red-800 bg-red-100';
                } elseif ($persentase >= 75) {
                    $warnaProgress = 'from-yellow-500 to-yellow-600';
                    $warnaBg = 'bg-yellow-50';
                    $statusText = 'Hampir Penuh';
                    $statusColor = 'text-yellow-800 bg-yellow-100';
                } else {
                    $warnaProgress = 'from-green-500 to-green-600';
                    $warnaBg = 'bg-green-50';
                    $statusText = 'Tersedia';
                    $statusColor = 'text-green-800 bg-green-100';
                }
            @endphp

            <!-- Progress Kuota dengan Desain Modern -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Progress Kuota</h2>
                        <p class="text-gray-500 text-sm">{{ $tahunAjaranAktif->tahun_ajaran }}</p>
                    </div>
                    <span class="px-4 py-2 {{ $statusColor }} rounded-xl text-sm font-semibold">
                        <i class="fas fa-info-circle mr-1"></i> {{ $statusText }}
                    </span>
                </div>

                <div class="mb-3">
                    <div class="relative pt-1">
                        <div class="overflow-hidden h-3 text-xs flex rounded-full bg-gray-200">
                            <div style="width: {{ $persentase }}%"
                                class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r {{ $warnaProgress }} transition-all duration-1000 rounded-full">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex items-baseline space-x-4">
                        <div>
                            <span class="text-2xl font-bold text-gray-800">{{ number_format($jumlahPendaftar) }}</span>
                            <span class="text-gray-500"> / {{ number_format($tahunAjaranAktif->kuota) }}</span>
                        </div>
                        <span class="text-sm text-gray-500">siswa</span>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold {{ $sisaKuota < 10 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($sisaKuota) }}
                        </span>
                        <span class="text-sm text-gray-500"> sisa kuota</span>
                    </div>
                </div>
            </div>

            <!-- Grafik Pendaftar Dinamis dengan Filter -->
            <div class="bg-white rounded-2xl shadow-xl p-6 mb-8">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Grafik Pendaftar</h2>
                        <p class="text-gray-500 text-sm">Visualisasi data pendaftar berdasarkan periode yang dipilih</p>
                    </div>

                    <!-- Filter Controls -->
                    <div class="flex flex-wrap gap-3">
                        <div class="relative">
                            <select id="filterType"
                                class="appearance-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block px-4 py-2 pr-8 cursor-pointer">
                                <option value="daily" {{ $filterType == 'daily' ? 'selected' : '' }}>📅 Harian</option>
                                <option value="monthly" {{ $filterType == 'monthly' ? 'selected' : '' }}>📊 Bulanan
                                </option>
                                <option value="yearly" {{ $filterType == 'yearly' ? 'selected' : '' }}>📈 Tahunan</option>
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>

                        <div id="yearFilter" class="relative"
                            style="{{ $filterType == 'yearly' ? 'display: none;' : '' }}">
                            <select id="yearSelect"
                                class="appearance-none bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block px-4 py-2 pr-8 cursor-pointer">
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>

                        <div id="monthFilter" class="relative"
                            style="{{ $filterType != 'daily' ? 'display: none;' : '' }}">
                            <input type="month" id="datePicker" value="{{ $selectedDate }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block px-4 py-2">
                        </div>

                        <div id="dateFilter" class="relative"
                            style="{{ $filterType != 'daily' ? 'display: none;' : '' }}">
                            <input type="date" id="dayPicker" value="{{ $selectedDate }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block px-4 py-2">
                        </div>

                        <button onclick="refreshChart()"
                            class="bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white px-4 py-2 rounded-xl transition-all shadow-md hover:shadow-lg">
                            <i class="fas fa-chart-line mr-2"></i> Tampilkan
                        </button>

                        <button onclick="resetFilters()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-xl transition-all shadow-md hover:shadow-lg">
                            <i class="fas fa-undo-alt mr-2"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Statistik Ringkasan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-800 text-xs font-semibold uppercase tracking-wide">Total Pendaftar</p>
                                <p class="text-2xl font-bold text-blue-900 mt-1" id="totalPendaftarStat">
                                    {{ array_sum($chartDataFiltered) }}
                                </p>
                            </div>
                            <div class="p-2 bg-blue-500 rounded-lg">
                                <i class="fas fa-users text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-800 text-xs font-semibold uppercase tracking-wide">Rata-rata per
                                    Periode</p>
                                <p class="text-2xl font-bold text-green-900 mt-1" id="avgPeriodeStat">
                                    {{ count($chartDataFiltered) > 0 ? round(array_sum($chartDataFiltered) / count($chartDataFiltered)) : 0 }}
                                </p>
                            </div>
                            <div class="p-2 bg-green-500 rounded-lg">
                                <i class="fas fa-chart-line text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-800 text-xs font-semibold uppercase tracking-wide">Periode Tertinggi
                                </p>
                                <p class="text-2xl font-bold text-purple-900 mt-1" id="maxPeriodeStat">
                                    {{ !empty($chartDataFiltered) ? max($chartDataFiltered) : 0 }}
                                </p>
                            </div>
                            <div class="p-2 bg-purple-500 rounded-lg">
                                <i class="fas fa-arrow-up text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="h-96">
                    <canvas id="dynamicChart" class="w-full h-full"></canvas>
                </div>

                <!-- Keterangan -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-center space-x-6 text-xs text-gray-500">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-full mr-2"></div>
                            <span>Jumlah Pendaftar</span>
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt mr-1"></i>
                            <span id="periodInfo">
                                @if ($filterType == 'daily')
                                    Data per jam untuk tanggal {{ Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                                @elseif($filterType == 'monthly')
                                    Data per bulan untuk tahun {{ $selectedYear }}
                                @else
                                    Data 5 tahun terakhir
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-500 rounded-xl p-6 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="font-bold text-yellow-800">Perhatian!</p>
                        <p class="text-yellow-700">Belum ada tahun ajaran yang aktif. Silakan <a
                                href="{{ route('tahun-ajaran.index') }}"
                                class="underline font-semibold hover:text-yellow-900">atur tahun ajaran</a> terlebih
                            dahulu.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Pendaftar dengan Desain Premium -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white">
                        <i class="fas fa-users mr-2"></i>
                        Pendaftar Terbaru
                    </h2>
                    <p class="text-indigo-100 text-sm mt-1">10 pendaftar terakhir yang mendaftar</p>
                </div>
                <a href="{{ route('pendaftaran.index') }}"
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-4 py-2 rounded-xl transition-all text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.
                                Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tahun Ajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl
                                Daftar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentPendaftar as $siswa)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-sm font-semibold text-gray-700">
                                    {{ $siswa->no_peserta }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-medium">{{ $siswa->tahunAjaran->tahun_ajaran }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $siswa->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('pendaftaran.show', $siswa->id) }}"
                                            class="text-indigo-600 hover:text-indigo-800 transition-colors p-1"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pendaftaran.cetak', $siswa->id) }}" target="_blank"
                                            class="text-green-600 hover:text-green-800 transition-colors p-1"
                                            title="Cetak">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2 opacity-50"></i>
                                    <p>Belum ada data pendaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart untuk pendaftar per bulan dengan desain yang lebih baik
            const chartElement = document.getElementById('chartPerBulan');
            if (chartElement) {
                const ctx = chartElement.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_keys($pendaftarPerBulan ?? [])) !!},
                        datasets: [{
                            label: 'Jumlah Pendaftar',
                            data: {!! json_encode(array_values($pendaftarPerBulan ?? [])) !!},
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(99, 102, 241)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 10,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        </script>
        <script>
            // Dynamic chart (Grafik Pendaftar) - inisialisasi dan fungsi refresh
            const dynamicApiUrl = "{{ route('dashboard.api.chart') }}";
            const initialLabels = {!! json_encode($chartLabelsFiltered ?? []) !!};
            const initialData = {!! json_encode($chartDataFiltered ?? []) !!};
            const initialFilter = "{{ $filterType }}";

            function numberSum(arr) {
                return arr.reduce((a, b) => a + (Number(b) || 0), 0);
            }

            const dynamicCanvas = document.getElementById('dynamicChart');
            let dynamicChart = null;

            if (dynamicCanvas) {
                const ctx = dynamicCanvas.getContext('2d');
                dynamicChart = new Chart(ctx, {
                    // type: initialFilter === 'daily' ? 'line' : 'bar',
                    type: 'line',
                    data: {
                        labels: initialLabels,
                        datasets: [{
                            label: 'Jumlah Pendaftar',
                            data: initialData,
                            backgroundColor: 'rgba(99,102,241,0.4)',
                            borderColor: 'rgb(99,102,241)',
                            borderWidth: 2,
                            fill: initialFilter === 'daily',
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(99,102,241)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            function updateStatsFromData(arr) {
                const total = numberSum(arr);
                const avg = arr.length > 0 ? Math.round(total / arr.length) : 0;
                const max = arr.length > 0 ? Math.max(...arr.map(n => Number(n) || 0)) : 0;

                const totalEl = document.getElementById('totalPendaftarStat');
                const avgEl = document.getElementById('avgPeriodeStat');
                const maxEl = document.getElementById('maxPeriodeStat');

                if (totalEl) totalEl.textContent = total;
                if (avgEl) avgEl.textContent = avg;
                if (maxEl) maxEl.textContent = max;
            }

            function toggleFilterVisibility() {
                const ft = document.getElementById('filterType')?.value;
                const yearFilter = document.getElementById('yearFilter');
                const monthFilter = document.getElementById('monthFilter');
                const dateFilter = document.getElementById('dateFilter');

                if (!ft) return;
                // Keep behaviour consistent with server-rendered inline styles
                if (yearFilter) yearFilter.style.display = ft === 'yearly' ? 'none' : '';
                if (monthFilter) monthFilter.style.display = ft !== 'daily' ? 'none' : '';
                if (dateFilter) dateFilter.style.display = ft !== 'daily' ? 'none' : '';
            }

            function refreshChart() {
                const ft = document.getElementById('filterType')?.value || 'monthly';
                const params = new URLSearchParams();
                params.append('filter_type', ft);

                const year = document.getElementById('yearSelect')?.value;
                if (year) params.append('year', year);

                const monthVal = document.getElementById('datePicker')?.value;
                if (monthVal) {
                    // month input returns YYYY-MM, controller doesn't use month param for monthly filter
                    params.append('month', monthVal.split('-')[1] || '');
                }

                const dateVal = document.getElementById('dayPicker')?.value;
                if (dateVal) params.append('date', dateVal);

                fetch(dynamicApiUrl + '?' + params.toString())
                    .then(res => res.json())
                    .then(json => {
                        if (!json.success) return;
                        if (dynamicChart) {
                            dynamicChart.data.labels = json.labels;
                            dynamicChart.data.datasets[0].data = json.data;
                            dynamicChart.update();
                        }
                        updateStatsFromData(json.data);

                        const periodInfo = document.getElementById('periodInfo');
                        if (periodInfo) {
                            if (json.filter_type === 'daily') {
                                periodInfo.textContent = 'Data per jam untuk tanggal ' + (dateVal ||
                                    '{{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}');
                            } else if (json.filter_type === 'monthly') {
                                periodInfo.textContent = 'Data per bulan untuk tahun ' + (year || '{{ $selectedYear }}');
                            } else {
                                periodInfo.textContent = 'Data 5 tahun terakhir';
                            }
                        }
                    })
                    .catch(err => {
                        console.error('Gagal memuat data chart:', err);
                    });
            }

            function resetFilters() {
                const ftEl = document.getElementById('filterType');
                if (ftEl) ftEl.value = 'monthly';
                const yearEl = document.getElementById('yearSelect');
                if (yearEl) yearEl.value = '{{ $selectedYear }}';
                const monthEl = document.getElementById('datePicker');
                if (monthEl) monthEl.value = '{{ $selectedDate }}';
                const dateEl = document.getElementById('dayPicker');
                if (dateEl) dateEl.value = '{{ $selectedDate }}';
                toggleFilterVisibility();
                refreshChart();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const ft = document.getElementById('filterType');
                if (ft) ft.addEventListener('change', toggleFilterVisibility);
                toggleFilterVisibility();
                updateStatsFromData(initialData || []);
            });
        </script>
    @endpush
@endsection
