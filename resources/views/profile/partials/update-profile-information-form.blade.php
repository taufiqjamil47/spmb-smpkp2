<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <!-- Nama Lengkap -->
    <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-user text-blue-600 mr-1"></i>
            {{ __('Nama Lengkap') }}
        </label>
        <input id="name" name="name" type="text"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
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
            {{ __('Alamat Email') }}
        </label>
        <input id="email" name="email" type="email"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
            value="{{ old('email', $user->email) }}" required autocomplete="username" />
        @if ($errors->has('email'))
            <div class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $errors->first('email') }}
            </div>
        @endif

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <i class="fas fa-exclamation-triangle mr-1 text-yellow-600"></i>
                    {{ __('Email Anda belum terverifikasi.') }}
                </p>
                <button type="button" onclick="document.getElementById('send-verification').submit();"
                    class="mt-2 text-sm font-semibold text-yellow-600 hover:text-yellow-700 underline transition">
                    {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                </button>

                @if (session('status') === 'verification-link-sent')
                    <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded text-green-700 text-sm">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Tombol Submit -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
            <i class="fas fa-save"></i>
            {{ __('Simpan Perubahan') }}
        </button>

        @if (session('status') === 'profile-updated')
            <div class="flex items-center gap-2 text-green-600 font-medium">
                <i class="fas fa-check-circle"></i>
                <span>{{ __('Profil berhasil diperbarui!') }}</span>
            </div>
        @endif
    </div>
</form>

<!-- Form verifikasi email (hidden) -->
<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
    @csrf
</form>
