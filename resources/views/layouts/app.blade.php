<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB SMP - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold">PPDB SMP</h2>
                <p class="text-sm opacity-75">Tahun Ajaran {{ date('Y') }}</p>
            </div>

            <nav class="mt-8">
                <a href="{{ route('dashboard') }}"
                    class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('dashboard') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>

                <a href="{{ route('pendaftaran.index') }}"
                    class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('pendaftaran.*') ? 'bg-blue-700' : '' }}">
                    <i class="fas fa-users mr-2"></i> Data Pendaftar
                </a>

                <a href="{{ route('pendaftaran.create') }}" class="block py-3 px-4 hover:bg-blue-700">
                    <i class="fas fa-user-plus mr-2"></i> Pendaftaran Baru
                </a>

                @if (auth()->check() && auth()->user()->role === 'admin')
                    <div class="border-t border-blue-700 my-4"></div>

                    <a href="{{ route('tahun-ajaran.index') }}"
                        class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('tahun-ajaran.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-calendar mr-2"></i> Kelola Kuota
                    </a>

                    <a href="{{ route('users.index') }}"
                        class="block py-3 px-4 hover:bg-blue-700 {{ request()->routeIs('users.*') ? 'bg-blue-700' : '' }}">
                        <i class="fas fa-user-cog mr-2"></i> Kelola User
                    </a>
                @endif
            </nav>

            <div class="absolute bottom-0 w-64 p-4 border-t border-blue-700">
                @if (auth()->check())
                    <p class="text-sm">{{ auth()->user()->name }}</p>
                    <p class="text-xs opacity-75">{{ ucfirst(auth()->user()->role) }}</p>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="text-sm hover:underline">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
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
</body>

</html>
