<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPMB KP2 - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            width: 240px;
        }

        #mainContent {
            margin-left: 240px;
        }

        .sidebar-ready .sidebar {
            transition: width 0.3s ease-in-out;
        }

        .sidebar-ready #mainContent {
            transition: margin-left 0.3s ease-in-out;
        }

        .menu-text {
            transition: opacity 0.2s ease-in-out, margin 0.3s ease-in-out;
        }

        .sidebar-collapsed .sidebar {
            width: 80px !important;
        }

        .sidebar-collapsed #mainContent {
            margin-left: 80px !important;
        }

        .sidebar-collapsed .menu-text {
            opacity: 0;
            width: 0;
            display: none;
        }

        .sidebar-collapsed .nav-link {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        .sidebar-collapsed .fa-2x {
            margin-right: 0 !important;
        }

        .toggle-btn {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-collapsed .toggle-btn {
            transform: rotate(180deg);
        }
    </style>
    <script>
        try {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
            document.documentElement.classList.add('sidebar-ready');
        } catch (e) {
            // ignore if localStorage is unavailable
        }
    </script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex relative">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div class="flex-1 p-8 transition-all duration-300" id="mainContent">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <div id="student-count-badge"
        class="fixed top-0 left-1/2 -translate-x-1/2 z-50 rounded-b-xl bg-blue-600 px-4 py-0 text-white">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3">
                <div class="text-xs uppercase tracking-wider text-blue-100/80">Pendaftar</div>
                <div id="student-count-value" class="text-2xl font-semibold text-right">Memuat...</div>
            </div>
            <div class="h-10 w-px bg-blue-400/30"></div>
            <div id="student-count-hint" class="text-xs text-blue-100/70 whitespace-nowrap">Diperbarui setiap 35s.</div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        function updateStudentCount() {
            const countElement = document.getElementById('student-count-value');
            const hintElement = document.getElementById('student-count-hint');

            fetch('{{ route('dashboard.api.total-students') }}', {
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && typeof data.total === 'number') {
                        countElement.textContent = data.total.toLocaleString('id-ID');
                        hintElement.textContent = 'Diperbarui pada: ' + new Date().toLocaleTimeString('id-ID');
                    } else {
                        countElement.textContent = '—';
                        hintElement.textContent = 'Gagal memuat data.';
                    }
                })
                .catch(() => {
                    countElement.textContent = '—';
                    hintElement.textContent = 'Tidak dapat terhubung ke server.';
                });
        }

        // Cek preferensi tersimpan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                document.documentElement.classList.add('sidebar-collapsed');
            }

            updateStudentCount();
            setInterval(updateStudentCount, 35000);
        });

        // Menangani klik pada link saat sidebar collapsed
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                // Placeholder for future behavior when collapsed
            });
        });
    </script>
</body>

</html>
