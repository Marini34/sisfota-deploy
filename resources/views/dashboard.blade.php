<x-app-layout>
    @section('title', 'Dashboard')
    <x-main-card>
        <div class="text-gray-900 dark:text-gray-100">
            @if (auth()->user()->hasRole('dosen') && auth()->user()->hasRole('kaprodi'))
                </p>
                Halo {{ Auth::user()->name }}👋, anda sekarang login sebagai Kaprodi
                <p>
                @else
                <p>
                    Halo {{ Auth::user()->name }}👋, anda sekarang login sebagai
                    {{ DB::table('role_user')->where('user_id', auth()->user()->id)->join('roles', 'role_user.role_id', '=', 'roles.id')->pluck('display_name')->first() }}
                </p>
            @endif
        </div>
    </x-main-card>
    @if (auth()->user()->hasRole('mahasiswa'))
        <x-main-card>
            <p class="text-gray-900 dark:text-gray-100">
                Disini nanti akan muncul timeline/histori perjalan tugas akhir user beserta kata-kata penyemangat dan
                motivasi
            </p>
        </x-main-card>
    @elseif (auth()->user()->hasRole('admin'))
        <x-main-card>
            Disini akan ada card-card kecil dengan informasi jumlah pendaftaran yang masuk per sesi seminar/sidang
        </x-main-card>
    @elseif (auth()->user()->hasRole('dosen'))
        <x-main-card>
            Disini akan ada card-card kecil dengan informasi jumlah mahasiswa yang akan dinilai per sesi seminar/sidang,
            dan card informasi jumlah kontrol bimbingan yang perlu konfirmasi
        </x-main-card>
    @endif

</x-app-layout>
