<x-app-layout>
    @section('title', 'Dashboard')
    <x-main-card>
        <div class="text-gray-900 dark:text-gray-100">
                <p>
                    Halo {{ Auth::user()->name }}👋, anda sekarang login sebagai Admin
                </p>
        </div>
    </x-main-card>
    <div class="grid grid-flow-row md:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-mini-card>
            <a href="/admin/kelola-mahasiswa">
                <div class="p-3 text-center">
                    <h1 class="font-bold text-xl">Verifikasi Akun Mahasiswa</h1>
                    <br>
                    <p class="font-bold text-4xl">{{ $users->count() }}</p>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/admin/sempro">
                <div class="p-3 text-center">
                    <h1 class="font-bold text-xl">Pendaftaran Seminar Proposal</h1>
                    <br>
                    <p class="font-bold text-4xl">{{ $sempro->count() }}</p>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/admin/semhas">
                <div class="p-3 text-center">
                    <h1 class="font-bold text-xl">Pendaftaran Seminar Hasil</h1>
                    <br>
                    <p class="font-bold text-4xl">{{ $semhas->count() }}</p>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/admin/sidang">
                <div class="p-3 text-center">
                    <h1 class="font-bold text-xl">Pendaftaran Sidang Akhir</h1>
                    <br>
                    <p class="font-bold text-4xl">{{ $sidang->count() }}</p>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/admin/sidang/transkrip">
                <div class="p-3 text-center">
                    <h1 class="font-bold text-xl">Transkrip Nilai Mahasiswa</h1>
                    <br>
                    <p class="font-bold text-4xl">{{ $transkrip->count() }}</p>
                </div>
            </a>
        </x-mini-card>
    </div>
</x-app-layout>
