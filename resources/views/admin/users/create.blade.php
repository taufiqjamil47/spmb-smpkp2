@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
    <div class="div mt-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-user-plus text-white text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ __('Tambah User Baru') }}</h1>
                    <p class="text-green-100 mt-1">Masukkan data user yang akan ditambahkan</p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <a href="{{ route('users.index') }}"
            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold mb-6">
            <i class="fas fa-arrow-left"></i>
            {{ __('Kembali') }}
        </a>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-blue-600 mr-1"></i>
                            {{ __('Nama Lengkap') }}
                        </label>
                        <input id="name" name="name" type="text"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                            value="{{ old('name') }}" required autofocus />
                        @if ($errors->has('name'))
                            <div class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-blue-600 mr-1"></i>
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" name="email" type="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                            value="{{ old('email') }}" required />
                        @if ($errors->has('email'))
                            <div class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-crown text-purple-600 mr-1"></i>
                            {{ __('Role') }}
                        </label>
                        <div class="relative">
                            <select id="role" name="role"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 appearance-none"
                                required>
                                <option value="">{{ __('Pilih Role') }}</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                        @if ($errors->has('role'))
                            <div class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('role') }}
                            </div>
                        @endif
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-yellow-600 mr-1"></i>
                            {{ __('Password') }}
                        </label>
                        <input id="password" name="password" type="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                            required />
                        <p class="mt-2 text-xs text-gray-600">
                            <i class="fas fa-info-circle mr-1 text-blue-600"></i>
                            Minimal 8 karakter, kombinasi huruf, angka, dan simbol
                        </p>
                        @if ($errors->has('password'))
                            <div class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock-open text-yellow-600 mr-1"></i>
                            {{ __('Konfirmasi Password') }}
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                            required />
                        @if ($errors->has('password_confirmation'))
                            <div class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $errors->first('password_confirmation') }}
                            </div>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('users.index') }}"
                            class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 text-center">
                            {{ __('Batal') }}
                        </a>
                        <button type="submit"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200">
                            <i class="fas fa-save mr-2"></i>
                            {{ __('Tambahkan User') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
