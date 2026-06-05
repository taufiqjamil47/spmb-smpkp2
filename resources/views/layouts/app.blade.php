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
        /* Base styles */
        .sidebar {
            width: 280px;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #mainContent {
            margin-left: 280px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: calc(100% - 280px);
            min-height: 100vh;
        }

        /* Collapsed state */
        .sidebar-collapsed .sidebar {
            width: 80px !important;
        }

        .sidebar-collapsed #mainContent {
            margin-left: 80px !important;
            width: calc(100% - 80px) !important;
        }

        /* Menu text animation */
        .menu-text {
            transition: opacity 0.2s ease-in-out, width 0.3s ease-in-out;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-collapsed .menu-text {
            opacity: 0;
            width: 0;
            display: none;
        }

        /* Navigation link styling */
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar-collapsed .nav-link {
            justify-content: center;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .sidebar-collapsed .nav-link i {
            margin-right: 0 !important;
        }

        /* Toggle button animation */
        .toggle-btn {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-collapsed .toggle-btn {
            transform: rotate(180deg);
        }

        /* Fix for content overflow */
        #mainContent {
            overflow-x: auto;
            overflow-y: auto;
        }

        /* Responsive adjustment */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                transform: translateX(-100%);
            }

            .sidebar-collapsed .sidebar {
                transform: translateX(0);
                width: 280px !important;
            }

            #mainContent {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .sidebar-collapsed #mainContent {
                margin-left: 0 !important;
            }
        }

        /* Custom scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        /* Badge notification */
        .badge-pending {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }
    </style>
    <script>
        // Prevent FOUC (Flash of Unstyled Content)
        try {
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
            document.documentElement.classList.add('sidebar-ready');
        } catch (e) {
            // ignore if localStorage is unavailable
        }
    </script>
    @stack('styles')
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex relative">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <main class="flex-1 transition-all duration-300" id="mainContent">
            <div class="container mx-auto px-4 py-4 md:px-6 md:py-6">
                <!-- Student Count Badge - Repositioned -->
                <div id="student-count-badge"
                    class="hidden md:block fixed top-2 left-1/2 transform -translate-x-1/2 z-40 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg px-5 py-1 text-white">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-users text-blue-200"></i>
                            <div class="text-xs uppercase tracking-wider text-blue-100/80">Total Pendaftar</div>
                        </div>
                        <div class="h-6 w-px bg-blue-400/30"></div>
                        <div id="student-count-value" class="text-xl font-bold">Memuat...</div>
                        <div class="h-6 w-px bg-blue-400/30 hidden lg:block"></div>
                        <div id="student-count-hint" class="text-xs text-blue-100/70 hidden lg:block">Diperbarui setiap
                            35s</div>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if (session('success'))
                    <div
                        class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-sm mb-6 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @push('styles')
        <style>
            /* Pastikan parent elements tidak memotong dropdown */
            .bg-gradient-to-r.from-blue-600.to-indigo-600 {
                overflow: visible !important;
            }

            .relative.z-10 {
                overflow: visible !important;
            }

            /* Biarkan tabel tetap scrollable tapi tidak memotong absolute elements di luarnya */
            .overflow-x-auto {
                overflow-x: auto;
                overflow-y: visible;
            }
        </style>
    @endpush

    <script>
        function toggleSidebar() {
            const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);

            // Trigger resize event for any components that need to recalculate
            window.dispatchEvent(new Event('resize'));
        }

        function updateStudentCount() {
            const countElement = document.getElementById('student-count-value');
            const hintElement = document.getElementById('student-count-hint');

            if (!countElement) return;

            fetch('{{ route('dashboard.api.total-students') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (data.success && typeof data.total === 'number') {
                        countElement.textContent = data.total.toLocaleString('id-ID');
                        if (hintElement) {
                            hintElement.textContent = 'Diperbarui: ' + new Date().toLocaleTimeString('id-ID');
                        }
                    } else {
                        countElement.textContent = '—';
                        if (hintElement) hintElement.textContent = 'Gagal memuat data';
                    }
                })
                .catch(error => {
                    console.error('Error fetching student count:', error);
                    countElement.textContent = '—';
                    if (hintElement) hintElement.textContent = 'Tidak dapat terhubung';
                });
        }

        // Check saved preference on load
        document.addEventListener('DOMContentLoaded', function() {
            // Initial load
            updateStudentCount();

            // Set interval for updates
            setInterval(updateStudentCount, 35000);

            // Ensure main content has proper height
            const mainContent = document.getElementById('mainContent');
            if (mainContent) {
                mainContent.style.minHeight = window.innerHeight + 'px';
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const mainContent = document.getElementById('mainContent');
            if (mainContent) {
                mainContent.style.minHeight = window.innerHeight + 'px';
            }
        });

        // Smooth scroll handling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
