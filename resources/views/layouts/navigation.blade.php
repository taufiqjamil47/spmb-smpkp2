<style>
    /* Custom scrollbar untuk sidebar */
    .sidebar-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    /* Animasi untuk nav-link */
    .nav-link {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin: 4px 12px;
        border-radius: 14px;
    }

    .nav-link:hover {
        transform: translateX(4px);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.05));
        backdrop-filter: blur(10px);
    }

    .nav-link.active {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.25), rgba(255, 255, 255, 0.1));
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .nav-link i {
        transition: transform 0.3s ease;
    }

    .nav-link:hover i {
        transform: scale(1.1);
    }

    /* Animasi untuk toggle button */
    .toggle-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .toggle-btn:hover {
        transform: rotate(180deg);
        background: rgba(255, 255, 255, 0.2);
    }

    /* Sidebar transition - floating style */
    .sidebar-transition {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* User avatar pulse effect */
    @keyframes pulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
        }

        50% {
            box-shadow: 0 0 0 8px rgba(255, 255, 255, 0);
        }
    }

    /* Badge styling */
    .notification-badge {
        animation: bounce 1s infinite;
    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-2px);
        }
    }

    /* Glassmorphism effect untuk sidebar - floating & modern */
    .glass-sidebar {
        background: linear-gradient(135deg,
                rgba(15, 25, 45, 0.85) 0%,
                rgba(20, 30, 55, 0.8) 50%,
                rgba(10, 20, 40, 0.85) 100%);
        backdrop-filter: blur(20px);
        border-radius: 15px;
        box-shadow:
            0 25px 50px -12px rgba(0, 0, 0, 0.25),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Floating animation */
    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-8px);
        }
    }

    .floating-sidebar {
        /* animation: float 6s ease-in-out infinite; */
        box-shadow: #000000 0px 15px 30px -10px, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;
    }

    /* Hover effect untuk logout button */
    .logout-btn {
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        transform: translateX(4px);
        color: #fbbf24;
    }

    /* Active link indicator - elegant line */
    .nav-link.active::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 24px;
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        border-radius: 4px;
        box-shadow: 0 0 10px rgba(96, 165, 250, 0.5);
    }

    /* Tooltip untuk collapsed mode */
    .nav-link[data-tooltip] {
        position: relative;
    }

    .nav-link[data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        margin-left: 12px;
        padding: 6px 12px;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        color: white;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
        border-radius: 8px;
        z-index: 100;
        pointer-events: none;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Hover scale effect for icons in collapsed mode */
    .sidebar-collapsed .nav-link i {
        font-size: 1.25rem;
    }
</style>

<div class="fixed inset-0 pointer-events-none" style="z-index: 40;">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 via-purple-500/5 to-pink-500/5"></div>
</div>

<div class="sidebar glass-sidebar text-white flex flex-col fixed sidebar-transition floating-sidebar"
    style="z-index: 50; width: 280px; top: 20px; left: 20px; bottom: 20px; height: calc(100vh - 40px);" id="sidebar">

    <!-- Header dengan toggle button - More elegant -->
    <div class="p-6 pb-4 flex items-center justify-between border-b border-white/10">
        <div class="menu-text">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <img src="{{ asset('Logo.png') }}" alt="Logo" class="w-5 h-5 object-contain">
                </div>
                <div>
                    <h2
                        class="text-xl font-bold tracking-tight bg-gradient-to-r from-white via-blue-100 to-white bg-clip-text text-transparent">
                        SPMB SMP
                    </h2>
                    <p class="text-xs text-white/60 font-medium mt-0.5">Tahun Ajaran {{ date('Y') }}</p>
                </div>
            </div>
        </div>
        <button onclick="toggleSidebar()"
            class="toggle-btn text-white/60 hover:text-white p-2 rounded-xl transition-all duration-300 hover:bg-white/10 hover:scale-110">
            <i class="fas fa-chevron-left text-xl"></i>
        </button>
    </div>

    <!-- Navigation Menu - Scrollable area with better spacing -->
    <nav class="flex-1 overflow-y-auto py-6 sidebar-scroll">
        <div class="px-4 mb-3">
            <span class="text-xs font-semibold text-white/40 uppercase tracking-wider menu-text">Menu Utama</span>
        </div>

        <a href="{{ route('dashboard') }}"
            class="nav-link block py-2.5 px-4 hover:bg-white/5 {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center group"
            data-tooltip="Dashboard">
            <i
                class="fas fa-tachometer-alt mr-3 w-5 text-lg text-white/70 group-hover:text-white group-hover:scale-110 transition-all"></i>
            <span class="menu-text text-sm font-medium">Dashboard</span>
        </a>

        <a href="{{ route('pendaftaran.index') }}"
            class="nav-link block py-2.5 px-4 hover:bg-white/5 {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }} flex items-center group"
            data-tooltip="Data Pendaftar">
            <i
                class="fas fa-users mr-3 w-5 text-lg text-white/70 group-hover:text-white group-hover:scale-110 transition-all"></i>
            <span class="menu-text text-sm font-medium">Data Pendaftar</span>
        </a>

        @if (auth()->check() && auth()->user()->role === 'admin')
            <div class="px-4 mt-6 mb-3">
                <span class="text-xs font-semibold text-white/40 uppercase tracking-wider menu-text">Administrasi</span>
            </div>

            <a href="{{ route('tahun-ajaran.index') }}"
                class="nav-link block py-2.5 px-4 hover:bg-white/5 {{ request()->routeIs('tahun-ajaran.*') ? 'active' : '' }} flex items-center group"
                data-tooltip="Kelola Kuota">
                <i
                    class="fas fa-calendar-alt mr-3 w-5 text-lg text-white/70 group-hover:text-white group-hover:scale-110 transition-all"></i>
                <span class="menu-text text-sm font-medium">Kelola Kuota</span>
            </a>

            <a href="{{ route('users.index') }}"
                class="nav-link block py-2.5 px-4 hover:bg-white/5 {{ request()->routeIs('users.*') ? 'active' : '' }} flex items-center group"
                data-tooltip="Manajemen User">
                <i
                    class="fas fa-users-cog mr-3 w-5 text-lg text-white/70 group-hover:text-white group-hover:scale-110 transition-all"></i>
                <span class="menu-text text-sm font-medium">Manajemen User</span>
            </a>

            <a href="{{ route('groupings.index') }}"
                class="nav-link block py-2.5 px-4 hover:bg-white/5 {{ request()->routeIs('groupings.*') ? 'active' : '' }} flex items-center group justify-between"
                data-tooltip="Manajemen Request">
                <div class="flex items-center">
                    <i
                        class="fas fa-sitemap mr-3 w-5 text-lg text-white/70 group-hover:text-white group-hover:scale-110 transition-all"></i>
                    <span class="menu-text text-sm font-medium">Manajemen Request</span>
                </div>
                @php
                    $pendingCount = \App\Models\GroupingRequest::where('status', 'pending')->count();
                @endphp
                @if ($pendingCount > 0)
                    <span
                        class="ml-2 notification-badge bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold rounded-full px-2 py-0.5 min-w-[20px] text-center shadow-lg shadow-red-500/20">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
        @endif

        <div class="px-4 mt-6 mb-3">
            <span class="text-xs font-semibold text-white/40 uppercase tracking-wider menu-text">Laporan &
                Analisis</span>
        </div>

        <a href="{{ route('statistik.index') }}"
            class="nav-link block py-2.5 px-4 hover:bg-white/5 {{ request()->routeIs('statistik.*') ? 'active' : '' }} flex items-center group"
            data-tooltip="Statistik">
            <i
                class="fas fa-chart-pie mr-3 w-5 text-lg text-white/70 group-hover:text-white group-hover:scale-110 transition-all"></i>
            <span class="menu-text text-sm font-medium">Statistik</span>
        </a>
    </nav>

    <!-- User Info & Logout - Elegant bottom section -->
    <div class="border-t border-white/10 p-5 mt-auto">
        @if (auth()->check())
            <div class="flex items-center mb-4 p-2 rounded-xl bg-white/5 backdrop-blur-sm">
                <div class="user-avatar relative">
                    <div
                        class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl p-2 w-10 h-10 flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                    <div
                        class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-gray-900 ring-2 ring-green-400/20">
                    </div>
                </div>
                <div class="menu-text ml-3 flex-1">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/50 font-medium mt-0.5">
                        <span class="capitalize">{{ auth()->user()->role }}</span>
                    </p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}"
                class="profile-btn w-full text-left flex items-center px-3 py-2 rounded-xl text-white/60 hover:text-white hover:bg-white/10 transition-all duration-300 group menu-text">
                <i class="fas fa-cog mr-3 text-lg group-hover:scale-110 group-hover:rotate-12 transition-all"></i>
                <span class="text-sm font-medium">Pengaturan</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="menu-text">
                @csrf
                <button type="submit"
                    class="logout-btn w-full text-left flex items-center px-3 py-2 rounded-xl text-white/60 hover:text-white hover:bg-white/10 transition-all duration-300 group">
                    <i
                        class="fas fa-sign-out-alt mr-3 text-lg group-hover:scale-110 group-hover:rotate-12 transition-all"></i>
                    <span class="text-sm font-medium">Keluar</span>
                </button>
            </form>
        @endif
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const currentWidth = sidebar.style.width;

        if (currentWidth === '80px') {
            sidebar.style.width = '280px';
            sidebar.style.borderRadius = '28px';
            // Show all menu-text elements
            document.querySelectorAll('.menu-text').forEach(el => {
                el.style.display = 'block';
            });
            sidebar.classList.remove('sidebar-collapsed');
        } else {
            sidebar.style.width = '80px';
            sidebar.style.borderRadius = '32px';
            // Hide menu-text when collapsed
            document.querySelectorAll('.menu-text').forEach(el => {
                el.style.display = 'none';
            });
            sidebar.classList.add('sidebar-collapsed');
        }
    }

    // Optional: Add hover effect for collapsed mode
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('mouseenter', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.style.width === '80px') {
                this.style.transform = 'translateX(0)';
            }
        });
    });
</script>
