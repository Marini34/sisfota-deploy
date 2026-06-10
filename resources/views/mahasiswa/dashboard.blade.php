<x-app-layout>
    @section('title', 'Dashboard')
    <x-main-card>
        <div class="text-gray-900 dark:text-gray-100">
            <p class="text-justify font-medium">
                <b>Halo {{ Auth::user()->name }}👋</b>
                Selamat datang di Sistem Informasi Tugas Akhir!
            </p>
            
            {{-- Lampiran Modal --}}
            @php
                $file = 'panduanTugasAkhir.pdf';
            @endphp
            <div class="modal-content">
                <dialog id="lampiran_{{ $file }}"
                    class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 h-screen">
                    <div class="flex items-center px-4 py-2 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                            Panduan Tugas Akhir Sisfo
                        </h3>
                        <button onclick="closeLampiran('{{ $file }}')" type="button"
                            class="float-end text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="timeline-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div>
                        <iframe src="{{ asset($file) }}" class="w-full" style="height:85vh;"></iframe>
                    </div>
                </dialog>
            </div>
            {{-- End Lampiran Modal --}}
        </div>
    </x-main-card>
    <div class="grid grid-flow-row md:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-mini-card>
            <a href="/mahasiswa/sempro">
                <div class="p-3 text-center min-h-full content-center">
                    <h1 class="font-bold text-xl">Seminar Proposal</h1>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/mahasiswa/semhas">
                <div class="p-3 text-center min-h-full content-center">
                    <h1 class="font-bold text-xl">Seminar Hasil</h1>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/mahasiswa/sidang">
                <div class="p-3 text-center min-h-full content-center">
                    <h1 class="font-bold text-xl">Sidang Akhir</h1>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/mahasiswa/bimbingan">
                <div class="p-3 text-center min-h-full content-center">
                    <h1 class="font-bold text-xl">Kontrol Bimbingan</h1>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a href="/mahasiswa/pengumuman">
                <div class="p-3 text-center min-h-full content-center">
                    <h1 class="font-bold text-xl">Pengumuman</h1>
                </div>
            </a>
        </x-mini-card>

        <x-mini-card>
            <a onclick="show('{{ $file }}')" class="cursor-pointer min-h-full">
                <div class="text-center min-h-full content-center">
                    <h1 class="font-bold text-xl">Panduan Tugas Akhir</h1>
                </div>
            </a>
        </x-mini-card>
    </div>
    
        @section('script')
        <script>
            function show(file) {
                var modal = document.getElementById('lampiran_' + file);
                modal.showModal();
            }

            function closeLampiran(file) {
                var modal = document.getElementById('lampiran_' + file);
                modal.close();
            }
        </script>
    @endsection
</x-app-layout>
