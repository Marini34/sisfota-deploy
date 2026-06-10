<x-app-layout>
    @section('title', 'Pengumuman')
    @section('head')
        @include('styles.datatables')
    @endsection
    <div style="top: 5.5rem;" class="fixed right-4 flex flex-row-reverse items-center">
        <div class="">
            <form class="max-w-lg mx-auto" action="/dosen/pengumuman">
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">
                    Cari
                </label>
                <div class="relative">
                    <input type="search" id="default-search" name="search" value="{{ request('search') }}" class="shadow-lg text-left block min-w-60 p-2.5 ps-4 text-sm text-gray-900 border border-gray-200 focus:outline-none focus:ring-transparent rounded-xl bg-gray-50"
                        placeholder="Cari Pengumuman..." />
                    <button type="submit"
                        class="text-gray-900 absolute end-0 bottom-0 border border-gray-200 bg-gray-50 hover:bg-slate-300 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-r-lg text-sm px-4 py-2.5">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <br><br>
    @if ($pengumumans->count())
        @foreach ($pengumumans as $pengumuman)
            <div class="bg-white text-slate-900 p-5 rounded-lg shadow-lg border-gray-200 text-justify mb-6">
                <h1 class="text-xl font-bold text-slate-800">{{ $pengumuman->judul_pengumuman }}</h1>
                <p class="text-sm">{{ \Carbon\Carbon::parse($pengumuman->created_at)->format('d M Y') }}@if ($pengumuman->updated_at != null) (diedit pada {{\Carbon\Carbon::parse($pengumuman->updated_at)->format('d M Y')}}) @endif</p>
                <p class="mb-5">
                    @php
                        $formattedPengumuman = $pengumuman->isi_pengumuman;
                        if (strpos($formattedPengumuman, '<ol>') !== false) {
                            $formattedPengumuman = str_replace(
                                '<ol>',
                                '<ol class="list-decimal pl-7">',
                                $formattedPengumuman,
                            );
                        }
                        if (strpos($formattedPengumuman, '<ul>') !== false) {
                            $formattedPengumuman = str_replace(
                                '<ul>',
                                '<ul class="list-disc pl-7">',
                                $formattedPengumuman,
                            );
                        }
                        echo $formattedPengumuman;
                    @endphp
                </p>
                @if ($pengumuman->lampiran)
                    @php
                        $lampirans = json_decode($pengumuman->lampiran);
                    @endphp
                    <div class="flex justify-start flex-wrap">
                        @foreach ($lampirans as $lampiran)
                            {{-- Lampiran Modal --}}
                            <div class="modal-content">
                                <dialog id="lampiran_{{ $lampiran }}"
                                    class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 h-screen">
                                    <div class="flex items-center px-4 py-2 border-b rounded-t dark:border-gray-600">
                                        <h3
                                            class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                                            Lampiran
                                        </h3>
                                        <button onclick="closeLampiran('{{ $lampiran }}')" type="button"
                                            class="float-end text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-toggle="timeline-modal">
                                            <svg class="w-3 h-3" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <div>
                                        {{-- <iframe src="https://docs.google.com/viewer?url={{ asset('storage/public/' . $lampiran) }}&embedded=true"
                                            class="w-full" style="height:85vh;"></iframe> --}}
                                        <iframe src="{{ asset('storage/' . $lampiran) }}" class="w-full" style="height:85vh;"></iframe>
                                    </div>
                                </dialog>
                            </div>
                            {{-- End Lampiran Modal --}}
                            <button onclick="show('{{ $lampiran }}')" type="button"
                                class="p-2 mr-4 mt-3 w-60 h-14 rounded-lg text-left flex items-center justify-center border border-slate-300 overflow-hidden"
                                data-tooltip-target="{{ $lampiran }}" data-tooltip-placement="bottom">
                                <div class="truncate">
                                    {{ $lampiran }}
                                </div>
                            </button>
                            <div id="{{ $lampiran }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                {{ $lampiran }}
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
        @elseif( request()->has('search') && !empty(request('search')) && $pengumumans->isEmpty() )
        <x-main-card>
            <div class="text-center text-5xl text-red-600">
                <i class="bi bi-exclamation-triangle"></i>
                <h1 class="text-center font-bold text-lg mt-3">
                    Tidak ada pengumuman ditemukan untuk "{{ request('search') }}"
                </h1>

                <a href="/dosen/pengumuman" type="button"
                    class="mt-0 text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  font-medium rounded-lg text-sm px-3 py-2 text-center">
                    Kembali
                </a>
            </div>
        </x-main-card>
    @elseif($pengumumans->count() == 0)
        <x-main-card>
            <div class="text-center text-5xl text-red-600">
                <h1 class="text-center font-bold text-lg mt-3">
                    Belum Ada Pengumuman Ditambahkan
                </h1>
            </div>
        </x-main-card>
    @endif
    @section('script')
    <script>
        function show(lampiran) {
                var modal = document.getElementById('lampiran_' + lampiran);
                modal.showModal();
            }

            function closeLampiran(lampiran) {
                var modal = document.getElementById('lampiran_' + lampiran);
                modal.close();
            }
    </script>
    @endsection
</x-app-layout>
