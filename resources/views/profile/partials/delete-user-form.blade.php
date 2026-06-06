<div class="space-y-6">
    <div>
        <p class="text-gray-700 mb-4">
            Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.
            Pastikan Anda telah mengunduh data yang ingin disimpan sebelum menghapus akun.
        </p>
    </div>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200">
        <i class="fas fa-trash-alt"></i>
        {{ __('Hapus Akun') }}
    </button>

    <!-- Modal Konfirmasi Penghapusan -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900 mb-2">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                {{ __('Yakin ingin menghapus akun?') }}
            </h2>

            <p class="text-sm text-gray-600 mt-3">
                {{ __('Tindakan ini bersifat permanen. Semua data akun Anda akan dihapus dan tidak dapat dipulihkan.') }}
            </p>

            <p class="text-sm text-gray-600 mt-2">
                {{ __('Masukkan password Anda untuk mengkonfirmasi penghapusan.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    {{ __('Password') }}
                </label>
                <input id="password" name="password" type="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                    placeholder="{{ __('Masukkan password Anda') }}" />
                @if ($errors->userDeletion->has('password'))
                    <div class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $errors->userDeletion->first('password') }}
                    </div>
                @endif
            </div>

            <div class="mt-6 flex gap-3 justify-end">
                <button type="button" x-on:click="show = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200">
                    {{ __('Batal') }}
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-lg transition duration-200">
                    <i class="fas fa-trash-alt mr-1"></i>
                    {{ __('Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
