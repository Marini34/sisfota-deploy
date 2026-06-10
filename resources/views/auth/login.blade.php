<x-guest-layout>
    @section('title', 'Login - SIMTA Sistem Informasi FMIPA Untan')
    @section('description', 'Login ke SIMTA untuk mengelola tugas akhir dengan mudah dan efisien di prodi Sistem Informasi FMIPA Untan.')

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <p class='hidden'>Login ke aplikasi SIMTA...</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="text-center mb-6">
            <b class="font-bold tracking-widest">LOGIN</b>
        </div>

        <div>
            <x-input-label for="loginField" :value="__('NIM/NIP/NIDK/Email')" />
            <x-text-input id="loginField"
                name="loginField"
                type="text"
                class="block mt-1 w-full"
                style="background-color: #08133E; color:white;"
                :value="old('loginField')"
                required autofocus />
            <x-input-error :messages="$errors->get('loginField')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                name="password"
                type="password"
                class="block mt-1 w-full"
                style="background-color: #08133E; color:white;"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4 text-center">
            <button type="submit"
                class="w-full text-sm font-bold px-4 py-2 rounded-md uppercase"
                style="color: #08133E; background-color: #FCB900;">
                MASUK
            </button>
        </div>
    </form>
</x-guest-layout>