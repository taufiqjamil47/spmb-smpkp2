@extends('layouts.app')

@section('title', 'Detail Grouping Request')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Detail Grouping</h1>
                        <div class="flex items-center space-x-3">
                            <p class="text-purple-100">Kode Request:</p>
                            <span
                                class="font-mono font-semibold bg-white bg-opacity-20 px-3 py-1 rounded-lg">{{ $grouping->request_code }}</span>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('groupings.index') }}"
                            class="bg-white text-purple-600 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-all font-medium shadow-md hover:shadow-lg flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>

                        @if ($grouping->status == 'pending')
                            <button type="button" id="approveBtn"
                                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-5 py-2.5 rounded-xl hover:shadow-lg transition-all font-medium">
                                <i class="fas fa-check mr-2"></i> Setujui
                            </button>

                            <button type="button" id="rejectBtn"
                                class="bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2.5 rounded-xl hover:shadow-lg transition-all font-medium">
                                <i class="fas fa-times mr-2"></i> Tolak
                            </button>

                            <!-- Hidden forms untuk approve dan reject -->
                            <form id="approveForm" action="{{ route('groupings.approve', $grouping->id) }}" method="POST"
                                class="hidden">
                                @csrf
                            </form>

                            <form id="rejectForm" action="{{ route('groupings.reject', $grouping->id) }}" method="POST"
                                class="hidden">
                                @csrf
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Rest of your content remains the same -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info Grouping -->
            <div class="lg:col-span-1">
                <!-- ... konten informasi grup tetap sama ... -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-purple-100">
                        <h2 class="text-lg font-bold text-gray-800">
                            <i class="fas fa-info-circle text-purple-500 mr-2"></i>
                            Informasi Grup
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">
                                <i class="fas fa-tag mr-1"></i> Nama Grup
                            </label>
                            <p class="font-semibold text-gray-800 text-lg">{{ $grouping->group_name }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3">
                            <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">
                                <i class="fas fa-qrcode mr-1"></i> Kode Grup
                            </label>
                            <p
                                class="font-mono text-sm font-semibold text-purple-600 bg-purple-100 inline-block px-3 py-1 rounded-lg">
                                {{ $grouping->request_code }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3">
                            <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">
                                <i class="fas fa-flag-checkered mr-1"></i> Status
                            </label>
                            <div>
                                @if ($grouping->status == 'pending')
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @elseif($grouping->status == 'approved')
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                                    </span>
                                @elseif($grouping->status == 'rejected')
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-spinner mr-1"></i> Diproses
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3">
                            <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i> Tahun Ajaran
                            </label>
                            <p class="font-medium text-gray-800">{{ $grouping->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-3">
                            <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">
                                <i class="fas fa-user-plus mr-1"></i> Dibuat
                            </label>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-800">{{ $grouping->created_at->format('d/m/Y H:i') }}</span>
                                <span class="text-xs text-gray-500">oleh {{ $grouping->creator->name ?? 'Sistem' }}</span>
                            </div>
                        </div>

                        @if ($grouping->approved_at)
                            <div class="bg-gray-50 rounded-xl p-3">
                                <label class="text-xs text-gray-500 uppercase tracking-wider block mb-1">
                                    <i class="fas fa-user-check mr-1"></i> Disetujui
                                </label>
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm text-gray-800">{{ $grouping->approved_at->format('d/m/Y H:i') }}</span>
                                    <span class="text-xs text-gray-500">oleh {{ $grouping->approver->name ?? '-' }}</span>
                                </div>
                            </div>
                        @endif

                        @if ($grouping->notes)
                            <div class="bg-yellow-50 rounded-xl p-3 border-l-4 border-yellow-400">
                                <label class="text-xs text-yellow-700 uppercase tracking-wider block mb-1">
                                    <i class="fas fa-sticky-note mr-1"></i> Catatan
                                </label>
                                <p class="text-sm text-yellow-800">{{ $grouping->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tambah Siswa (opsional) -->
                @if ($grouping->status != 'rejected' && isset($availableStudents) && $availableStudents->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mt-6">
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-6 py-4 border-b border-blue-100">
                            <h2 class="text-lg font-bold text-gray-800">
                                <i class="fas fa-user-plus text-blue-500 mr-2"></i>
                                Tambah Siswa
                            </h2>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('groupings.add-student', $grouping->id) }}" method="POST">
                                @csrf
                                <div class="relative mb-4">
                                    <i
                                        class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <select name="student_id"
                                        class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer"
                                        required>
                                        <option value="">Pilih siswa...</option>
                                        @foreach ($availableStudents as $student)
                                            <option value="{{ $student->id }}">{{ $student->nama_lengkap }}
                                                ({{ $student->no_peserta }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <i
                                        class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                </div>
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-500 to-cyan-500 text-white px-4 py-3 rounded-xl hover:shadow-lg transition-all font-medium">
                                    <i class="fas fa-plus mr-2"></i> Tambahkan ke Grup
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Daftar Siswa -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-purple-100">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-bold text-gray-800">
                                <i class="fas fa-users text-purple-500 mr-2"></i>
                                Daftar Siswa dalam Grup
                            </h2>
                            <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $grouping->students->count() }} siswa
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        No. Peserta</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Nama Lengkap</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        NISN</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Prioritas</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($grouping->students as $student)
                                    <tr class="hover:bg-purple-50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <span
                                                class="font-mono text-sm font-semibold text-purple-600 bg-purple-100 px-2 py-1 rounded-lg">{{ $student->no_peserta }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">{{ $student->nama_lengkap }}</div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                {{ $student->tempat_lahir }},
                                                {{ \Carbon\Carbon::parse($student->tanggal_lahir)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-sm text-gray-700">{{ $student->nisn }}</td>
                                        <td class="px-6 py-4">
                                            @if ($student->grouping_priority == 'high')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-arrow-up mr-1"></i> Prioritas Tinggi
                                                </span>
                                            @elseif($student->grouping_priority == 'medium')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-minus mr-1"></i> Prioritas Sedang
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                                    <i class="fas fa-circle mr-1 text-gray-400"></i> Normal
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('pendaftaran.show', $student->id) }}"
                                                    class="text-blue-600 hover:text-white hover:bg-blue-600 p-2 rounded-lg transition-all"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if ($grouping->status != 'approved')
                                                    <form
                                                        action="{{ route('groupings.remove-student', [$grouping->id, $student->id]) }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirmRemoveStudent(this)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-white hover:bg-red-600 p-2 rounded-lg transition-all"
                                                            title="Keluarkan dari Grup">
                                                            <i class="fas fa-user-minus"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                    <i class="fas fa-users-slash text-gray-400 text-3xl"></i>
                                                </div>
                                                <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Siswa</h3>
                                                <p class="text-sm text-gray-500">Belum ada siswa dalam grup ini</p>
                                                @if ($grouping->status != 'rejected' && isset($availableStudents) && $availableStudents->count() > 0)
                                                    <p class="text-xs text-gray-400 mt-2">Gunakan form di samping untuk
                                                        menambahkan siswa</p>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Pastikan SweetAlert2 sudah dimuat -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Fungsi untuk konfirmasi remove student
            window.confirmRemoveStudent = function(formElement) {
                Swal.fire({
                    title: 'Keluarkan Siswa?',
                    text: 'Apakah Anda yakin ingin mengeluarkan siswa ini dari grup?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Keluarkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formElement.submit();
                    }
                });
                return false; // Mencegah submit langsung
            };

            // Event handler untuk tombol Approve
            const approveBtn = document.getElementById('approveBtn');
            if (approveBtn) {
                approveBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Setujui Grouping?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Grouping yang disetujui akan digunakan dalam pembagian kelas.</p>
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mt-2">
                                    <p class="text-sm text-yellow-800">
                                        <i class="fas fa-info-circle"></i> Pastikan data siswa sudah benar sebelum menyetujui.
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Setujui!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById('approveForm');
                                // Submit form secara manual
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    });
                });
            }

            // Event handler untuk tombol Reject
            const rejectBtn = document.getElementById('rejectBtn');
            if (rejectBtn) {
                rejectBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Tampilkan input untuk alasan penolakan (opsional)
                    Swal.fire({
                        title: 'Tolak Grouping?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Grouping yang ditolak tidak akan digunakan dalam pembagian kelas.</p>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Penolakan (Opsional):
                                    </label>
                                    <textarea id="rejectReason" class="swal2-textarea" rows="3" 
                                        placeholder="Masukkan alasan penolakan..."></textarea>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Tolak!',
                        cancelButtonText: 'Batal',
                        preConfirm: () => {
                            const reason = document.getElementById('rejectReason').value;
                            // Simpan reason ke dalam form jika perlu
                            if (reason) {
                                const form = document.getElementById('rejectForm');
                                let input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'reason';
                                input.value = reason;
                                form.appendChild(input);
                            }
                            return true;
                        },
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById('rejectForm');
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            }

            // Notifikasi flash message jika ada session data
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#10B981',
                    timer: 3000,
                    showConfirmButton: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#EF4444'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: '{{ session('warning') }}',
                    confirmButtonColor: '#F59E0B'
                });
            @endif
        </script>
    @endpush
@endsection
