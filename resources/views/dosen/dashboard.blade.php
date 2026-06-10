<x-app-layout>
    @section('title', 'Dashboard')
    <x-main-card>
        <div class="text-gray-900 dark:text-gray-100">
            <p>
                Halo {{ Auth::user()->name }}👋, anda sekarang login sebagai Dosen
            </p>
        </div>
    </x-main-card>
    @if (session()->has('success'))
        <div id="alert-3"
            class="flex items-center p-3 mb-1 text-white rounded-lg bg-green-400 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <span class="text-3xl"><i class="bi bi-check2-circle"></i></span>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                {{ session('success') }}
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-green-400 text-white rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-800 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-3" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @elseif (session()->has('fail'))
        <div id="alert-2" class="flex items-center p-3 mb-1 text-white rounded-lg bg-red-700" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                {{ session('fail') }}
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-red-700 text-white rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-800 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif
    <div class="grid grid-flow-row md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- SEMPRO --}}
        <x-mini-card>
            <a href="{{ url('/dosen/penilaian-sempro') }}" class="block">
                <div class="p-4 text-center hover:bg-gray-50 transition rounded-xl">
                    <h1 class="font-semibold text-gray-700">Penilaian Seminar Proposal</h1>
                    <hr class="my-2">
                    <p class="font-bold text-4xl text-gray-900">{{ $sempro->count() }}</p>
                    <span class="text-blue-600 text-sm hover:underline">Lihat Selengkapnya</span>
                </div>
            </a>
        </x-mini-card>

        {{-- SEMHAS --}}
        <x-mini-card>
            <a href="{{ url('/dosen/penilaian-semhas') }}" class="block">
                <div class="p-4 text-center hover:bg-gray-50 transition rounded-xl">
                    <h1 class="font-semibold text-gray-700">Penilaian Seminar Hasil</h1>
                    <hr class="my-2">
                    <p class="font-bold text-4xl text-gray-900">{{ $semhas->count() }}</p>
                    <span class="text-blue-600 text-sm hover:underline">Lihat Selengkapnya</span>
                </div>
            </a>
        </x-mini-card>

        {{-- SIDANG --}}
        <x-mini-card>
            <a href="{{ url('/dosen/penilaian-sidang') }}" class="block">
                <div class="p-4 text-center hover:bg-gray-50 transition rounded-xl">
                    <h1 class="font-semibold text-gray-700">Penilaian Sidang Akhir</h1>
                    <hr class="my-2">
                    <p class="font-bold text-4xl text-gray-900">{{ $sidang->count() }}</p>
                    <span class="text-blue-600 text-sm hover:underline">Lihat Selengkapnya</span>
                </div>
            </a>
        </x-mini-card>

        {{-- BIMBINGAN --}}
        <x-mini-card>
            <a href="{{ url('/dosen/kelola-bimbingan') }}" class="block">
                <div class="p-4 text-center hover:bg-gray-50 transition rounded-xl">
                    <h1 class="font-semibold text-gray-700">Kontrol Bimbingan</h1>
                    <hr class="my-2">
                    <p class="font-bold text-4xl text-gray-900">{{ $bimbingan->count() }}</p>
                    <span class="text-blue-600 text-sm hover:underline">Lihat Selengkapnya</span>
                </div>
            </a>
        </x-mini-card>

        {{-- BIMBINGAN AKTIF --}}
        <x-mini-card>
            <a href="{{ url('/dosen/monitor/bimbingan-aktif') }}" class="block">
                <div class="p-4 text-center hover:bg-gray-50 transition rounded-xl">
                    <h1 class="font-semibold text-gray-700">Jumlah Bimbingan Aktif</h1>
                    <hr class="my-2">
                    <p class="font-bold text-4xl text-gray-900">{{ $BimbinganAktif ?? 0 }}</p>
                    <span class="text-blue-600 text-sm hover:underline">Lihat Selengkapnya</span>
                </div>
            </a>
        </x-mini-card>

        {{-- BIMBINGAN LULUS --}}
        <x-mini-card>
            <a href="{{ url('/dosen/monitor/bimbingan-lulus') }}" class="block">
                <div class="p-4 text-center hover:bg-gray-50 transition rounded-xl">
                    <h1 class="font-semibold text-gray-700">Jumlah Bimbingan Lulus</h1>
                    <hr class="my-2">
                    <p class="font-bold text-4xl text-gray-900">{{ $BimbinganLulus ?? 0 }}</p>
                    <span class="text-blue-600 text-sm hover:underline">Lihat Selengkapnya</span>
                </div>
            </a>
        </x-mini-card>

        {{-- DIUJI AKTIF --}}
        <x-mini-card>
            <a href="{{ url('/dosen/monitor/diuji-aktif') }}" class="block">
                <div class="p-4 text-center hover:bg-gray-50 transition rounded-xl">
                    <h1 class="font-semibold text-gray-700">Jumlah Diuji Aktif</h1>
                    <hr class="my-2">
                    <p class="font-bold text-4xl text-gray-900">{{ $diujiAktif ?? 0 }}</p>
                    <span class="text-blue-600 text-sm hover:underline">Lihat Selengkapnya</span>
                </div>
            </a>
        </x-mini-card>

        {{-- DIUJI LULUS --}}
        <x-mini-card>
            <a href="{{ url('/dosen/monitor/diuji-lulus') }}" class="block">
                <div class="p-4 text-center hover:bg-gray-50 transition rounded-xl">
                    <h1 class="font-semibold text-gray-700">Jumlah Diuji Lulus</h1>
                    <hr class="my-2">
                    <p class="font-bold text-4xl text-gray-900">{{ $diujiLulus ?? 0 }}</p>
                    <span class="text-blue-600 text-sm hover:underline">Lihat Selengkapnya</span>
                </div>
            </a>
        </x-mini-card>

    </div>
</x-app-layout>
