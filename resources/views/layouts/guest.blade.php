<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Default Title')</title>
    <meta name="description" content="@yield('description', 'Default Description')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased overflow-hidden customBody" style="background-color: #08133E;">

    <div class="flex flex-col md:flex-row justify-between items-center my-auto {{ Route::currentRouteName() === 'register' ? '-mt-10' : '-mt-6' }}">

        <div style="background-color: #08133E; margin:auto;" class="custom-vw min-h-screen flex flex-col sm:justify-center items-center xl:items-start">

            <div class="mx-10">
                <a href="/">
                    <img src="{{ asset('img/LOGO SIMTA-whites.png') }}" class="logo" style="max-width: 24rem;">
                </a>
            </div>

            @if (session()->has('pesan'))
                <div class="w-11/12 sm:max-w-sm mx-10 mt-6 px-6 py-2 bg-white rounded-lg">
                    {{ session('pesan') }}
                </div>
            @endif

            <!-- SLOT -->
            <div class="w-11/12 sm:max-w-sm mx-10 mt-6 px-6 py-4 bg-white rounded-lg">
                {{ $slot }}
            </div>

            <!-- LINK (SUDAH TANPA SLOT, FIX ERROR) -->
            <div class="w-11/12 sm:max-w-sm mx-10 px-6 overflow-hidden rounded-lg">
                <div class="mt-3 text-center text-sm text-white">
                    <p>Belum punya akun?
                        <a href="/register" class="underline text-yellow-300">Daftar</a>
                    </p>
                </div>
            </div>

        </div>

        <div class="hidden-sm">
            <div class="rounded-full -mt-20 -mr-72" style="background-color: #FCB900; width: 50rem; height:50rem;">
                <div class="pt-52">
                    <img src="{{ asset('img/toga.png') }}">
                </div>
            </div>
        </div>

    </div>

</body>
</html>