@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="div mt-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="bg-white bg-opacity-20 rounded-lg p-3">
                    <i class="fas fa-user-cog text-white text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ __('Pengaturan Profil') }}</h1>
                    <p class="text-blue-100 mt-1">Kelola informasi akun dan pengaturan keamanan Anda</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Profile Info Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-id-card text-blue-600 mr-2"></i>
                            {{ __('Informasi Profil') }}
                        </h2>
                        <p class="text-gray-600 text-sm mt-1">Perbarui informasi dasar akun Anda</p>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    {{ __('Informasi Akun') }}
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-600 text-sm uppercase tracking-wide">Nama</p>
                        <p class="text-gray-900 font-semibold mt-1">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-gray-600 text-sm uppercase tracking-wide">Email</p>
                        <p class="text-gray-900 font-semibold mt-1 break-all">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-gray-600 text-sm uppercase tracking-wide">Status</p>
                        <div class="mt-1">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <i class="fas fa-user-check mr-1"></i>
                                {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Password Update Section -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="border-b border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-800">
                        <i class="fas fa-lock text-yellow-600 mr-2"></i>
                        {{ __('Keamanan Akun') }}
                    </h2>
                    <p class="text-gray-600 text-sm mt-1">Perbarui password untuk melindungi akun Anda</p>
                </div>
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
            <!-- Danger Zone -->
            <div class="bg-white border-2 border-red-200 rounded-lg shadow-lg overflow-hidden">
                <div class="border-b border-red-200 p-6 bg-red-50">
                    <h2 class="text-xl font-semibold text-red-800">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        {{ __('Zona Berbahaya') }}
                    </h2>
                    <p class="text-red-700 text-sm mt-1">Tindakan berikut bersifat permanen dan tidak dapat dibatalkan</p>
                </div>
                <div class="p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

    </div>
@endsection
