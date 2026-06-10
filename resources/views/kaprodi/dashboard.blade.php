<x-app-layout>
    @section('title', 'Dashboard')
    <x-main-card>
        <div class="text-gray-900 dark:text-gray-100">
            <p>
                Halo {{ Auth::user()->name }}👋, anda sekarang login sebagai Kaprodi
            </p>
        </div>
    </x-main-card>
    <div class="grid grid-flow-row md:grid-cols-2 lg:grid-cols-2 gap-4">

        <x-mini-card>
            <a href="/kaprodi/monitor/lama-pengerjaan-ta" class="block p-5 text-center">
                <div class="mx-auto mb-3 w-30 h-12 flex items-center justify-center rounded-full bg-blue-100">
                    <!-- Clock Icon -->
                   <b>{{ $hasilCountLamaTa }}</b>
                    
                </div>
                <h1 class="font-bold text-lg">
                    Rata-rata Waktu Pengerjaan TA
                </h1>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/kaprodi/monitor/mhs-aktif-ta" class="block p-5 text-center">
                <div class="mx-auto mb-3 w-30 h-12 flex items-center justify-center rounded-full bg-blue-100">
                    <!-- Clock Icon -->
                   <b>{{ $countMahasiswaAktifTa }} Mahasiswa</b>
                </div>
                <h1 class="font-bold text-lg">
                    Aktif Tugas Akhir 
                </h1>
            </a>
        </x-mini-card>
    
        <x-mini-card>
            <a href="/kaprodi/monitor/ktw" class="block p-5 text-center">
                <div class="mx-auto mb-3 w-30 h-12 flex items-center justify-center rounded-full bg-blue-100">
                    <!-- Clock Icon -->
                   <b>{{ $countKTW }} Mahasiswa</b>
                </div>
                <h1 class="font-bold text-lg">
                    Lulus Tepat Waktu
                </h1>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/kaprodi/monitor/lms" class="block p-5 text-center">
                <div class="mx-auto mb-3 w-30 h-12 flex items-center justify-center rounded-full bg-blue-100">
                    <!-- Clock Icon -->
                   <b>{{ $rataLMS }} </b>
                </div>
                <h1 class="font-bold text-lg">
                    Lama Masa Studi (LMS) 
                </h1>
            </a>
        </x-mini-card> 

    </div>

</x-app-layout>
