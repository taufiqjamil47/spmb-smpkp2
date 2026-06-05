<div class="sidebar bg-blue-800 text-white flex flex-col fixed h-screen sidebar-transition" style="z-index: 50;"
    id="sidebar">

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

        {{-- <a href="{{ route('pendaftaran.create') }}"
                    class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('pendaftaran.create') ? 'bg-blue-700' : '' }} flex items-center">
                    <i class="fas fa-user-plus mr-3 w-5"></i>
                    <span class="menu-text whitespace-nowrap">Pendaftaran Baru</span>
                </a> --}}

        @if (auth()->check() && auth()->user()->role === 'admin')
            <div class="border-t border-blue-700 my-4"></div>

            <a href="{{ route('tahun-ajaran.index') }}"
                class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('tahun-ajaran.*') ? 'bg-blue-700' : '' }} flex items-center">
                <i class="fas fa-calendar mr-3 w-5"></i>
                <span class="menu-text whitespace-nowrap">Kelola Kuota</span>
            </a>

            <a href="{{ route('groupings.index') }}"
                class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('groupings.*') ? 'bg-blue-700' : '' }} flex items-center">
                <i class="fas fa-sitemap mr-3 w-5"></i>
                <span class="menu-text whitespace-nowrap mr-1">Manajemen Request</span>
                @php
                    $pendingCount = \App\Models\GroupingRequest::where('status', 'pending')->count();
                @endphp
                @if ($pendingCount > 0)
                    <span class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>

            {{-- <a href="{{ route('users.index') }}"
                        class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('users.*') ? 'bg-blue-700' : '' }} flex items-center">
                        <i class="fas fa-user-cog mr-3 w-5"></i>
                        <span class="menu-text whitespace-nowrap">Kelola User</span>
                    </a> --}}
        @endif
        <a href="{{ route('statistik.index') }}"
            class="nav-link block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('statistik.*') ? 'bg-blue-700' : '' }} flex items-center">
            <i class="fas fa-chart-pie mr-3 w-5"></i>
            <span class="menu-text whitespace-nowrap">Statistik</span>
        </a>
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
