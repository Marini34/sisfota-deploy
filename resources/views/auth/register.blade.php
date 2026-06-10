<x-guest-layout>
    @section('title', 'Sign Up - SIMTA Sistem Informasi FMIPA Untan')
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" class="block h-9 w-full" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div class="mt-2">
            <x-input-label for="nim_nip" :value="__('NIM')" />
            <x-text-input id="nim_nip" class="block h-9 w-full" type="text" name="nim_nip" :value="old('nim_nip')"
                required autofocus autocomplete="nim_nip" />
            <x-input-error :messages="$errors->get('nim_nip')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="mt-2">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block h-9 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mt-2">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block h-9 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-2">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block h-9 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already have an account?') }}
            </a>

            <x-primary-button style="color: #08133E; background-color: #FCB900;" class="ms-4 text-sm font-bold">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <x-slot:link>
    </x-slot:link>
</x-guest-layout>
