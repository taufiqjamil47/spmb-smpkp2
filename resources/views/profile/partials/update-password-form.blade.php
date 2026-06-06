<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <!-- Current Password -->
    <div>
        <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-key text-yellow-600 mr-1"></i>
            {{ __('Password Saat Ini') }}
        </label>
        <input id="update_password_current_password" name="current_password" type="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
            autocomplete="current-password" />
        @if ($errors->updatePassword->has('current_password'))
            <div class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $errors->updatePassword->first('current_password') }}
            </div>
        @endif
    </div>

    <!-- New Password -->
    <div>
        <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-lock text-yellow-600 mr-1"></i>
            {{ __('Password Baru') }}
        </label>
        <input id="update_password_password" name="password" type="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
            autocomplete="new-password" />
        @if ($errors->updatePassword->has('password'))
            <div class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $errors->updatePassword->first('password') }}
            </div>
        @endif
        <p class="mt-2 text-xs text-gray-600">
            <i class="fas fa-info-circle mr-1 text-blue-600"></i>
            Password harus minimal 8 karakter dengan kombinasi huruf, angka, dan simbol.
        </p>
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
            <i class="fas fa-lock-open text-yellow-600 mr-1"></i>
            {{ __('Konfirmasi Password') }}
        </label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition duration-200"
            autocomplete="new-password" />
        @if ($errors->updatePassword->has('password_confirmation'))
            <div class="mt-2 text-sm text-red-600 flex items-center">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $errors->updatePassword->first('password_confirmation') }}
            </div>
        @endif
    </div>

    <!-- Tombol Submit -->
    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
        <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition duration-200">
            <i class="fas fa-save"></i>
            {{ __('Perbarui Password') }}
        </button>

        @if (session('status') === 'password-updated')
            <div class="flex items-center gap-2 text-green-600 font-medium">
                <i class="fas fa-check-circle"></i>
                <span>{{ __('Password berhasil diperbarui!') }}</span>
            </div>
        @endif
    </div>
</form>
