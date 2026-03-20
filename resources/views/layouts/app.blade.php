<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB SMP - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar-transition {
            transition: width 0.3s ease-in-out;
        }

        .menu-text {
            transition: opacity 0.2s ease-in-out, margin 0.3s ease-in-out;
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
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex relative">
        <!-- Sidebar -->
        <div class="sidebar bg-blue-800 text-white flex flex-col fixed h-screen sidebar-transition"
            style="width: 240px; z-index: 50;" id="sidebar">

            <!-- Header dengan toggle button -->
            <div class="p-4 flex items-center justify-between">
                <div class="menu-text">
                    <h2 class="text-2xl font-bold whitespace-nowrap">SPMB SMP</h2>
                    <p class="text-sm opacity-75 whitespace-nowrap">Tahun Ajaran {{ date('Y') }}</p>
                </div>
                <button onclick="toggleSidebar()" class="text-white hover:bg-blue-700 p-2 rounded-lg toggle-btn">
                    <i class="fas fa-chevron-left fa-2x"></i>
                </button>
            </div>

            <!-- Navigation Menu - Scrollable area -->
            <nav class="flex-1 overflow-y-auto py-4">
                <a href="{{ route('dashboard') }}"
                    class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }} flex items-center">
                    <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                    <span class="menu-text whitespace-nowrap">Dashboard</span>
                </a>

                <a href="{{ route('pendaftaran.index') }}"
                    class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('pendaftaran.*') ? 'bg-blue-700' : '' }} flex items-center">
                    <i class="fas fa-users mr-3 w-5"></i>
                    <span class="menu-text whitespace-nowrap">Data Pendaftar</span>
                </a>

                <a href="{{ route('pendaftaran.create') }}"
                    class="nav-link block py-3 px-4 hover:bg-blue-700 flex items-center">
                    <i class="fas fa-user-plus mr-3 w-5"></i>
                    <span class="menu-text whitespace-nowrap">Pendaftaran Baru</span>
                </a>

                @if (auth()->check() && auth()->user()->role === 'admin')
                    <div class="border-t border-blue-700 my-4"></div>

                    <a href="{{ route('tahun-ajaran.index') }}"
                        class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('tahun-ajaran.*') ? 'bg-blue-700' : '' }} flex items-center">
                        <i class="fas fa-calendar mr-3 w-5"></i>
                        <span class="menu-text whitespace-nowrap">Kelola Kuota</span>
                    </a>

                    <a href="{{ route('statistik.index') }}"
                        class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('statistik.*') ? 'bg-blue-700' : '' }} flex items-center">
                        <i class="fas fa-chart-pie mr-3 w-5"></i>
                        <span class="menu-text whitespace-nowrap">Statistik</span>
                    </a>

                    <a href="{{ route('users.index') }}"
                        class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('users.*') ? 'bg-blue-700' : '' }} flex items-center">
                        <i class="fas fa-user-cog mr-3 w-5"></i>
                        <span class="menu-text whitespace-nowrap">Kelola User</span>
                    </a>
                @endif
            </nav>

            <!-- User Info & Logout - Fixed at bottom -->
            <div class="border-t border-blue-700 p-4">
                @if (auth()->check())
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-600 rounded-full p-2 mr-3 w-10 h-10 flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div class="menu-text">
                            <p class="text-sm font-medium whitespace-nowrap">{{ auth()->user()->name }}</p>
                            <p class="text-xs opacity-75 whitespace-nowrap">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="menu-text">
                        @csrf
                        <button type="submit"
                            class="text-sm hover:underline text-blue-200 hover:text-white w-full text-left flex items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8 transition-all duration-300" style="margin-left: 240px;" id="mainContent">

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

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');

            if (isCollapsed) {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.style.width = '240px';
                mainContent.style.marginLeft = '240px';
            } else {
                sidebar.classList.add('sidebar-collapsed');
                sidebar.style.width = '80px';
                mainContent.style.marginLeft = '80px';
            }

            // Simpan preferensi user di localStorage
            localStorage.setItem('sidebarCollapsed', !isCollapsed);
        }

        // Cek preferensi tersimpan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            if (isCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                sidebar.style.width = '80px';
                mainContent.style.marginLeft = '80px';
            }
        });

        // Menangani klik pada link saat sidebar collapsed
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    // Optional: Auto expand saat hover? Bisa ditambahkan jika diinginkan
                }
            });
        });
    </script>
</body>

</html>
