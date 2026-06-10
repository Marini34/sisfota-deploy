<x-app-layout>
    @section('title', 'Pengumuman')
    @section('head')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <style>
            .trix-editor {
                width: 100%;
                min-height: 6.2rem;
            }

            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
            }

            @media (max-width: 640px) {
                .trix-editor {
                    width: 100%;
                    min-height: 18rem;
                }

            }
            iframe > img {
                    display: inline;
                    margin-left: auto;
                    margin-right: auto;
                }
        </style>
    @endsection
    {{-- Tambah Pengumuman Modal --}}
    <div class="modal-content">
        <dialog id="tambahPengumuman" class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 lg:w-3/5 h-screen">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                    Tambah Pengumuman
                </h3>
                <button onclick="closeModalTambahPengumuman()" type="button"
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
            <form action="{{ route('store.pengumuman') }}" method="post" enctype="multipart/form-data" class="p-2">
                @csrf
                <div class="mx-5 mb-3">
                    <label for="judul">Judul Pengumuman<span class="text-red-600">*</span></label>
                    <div class="my-2">
                        <input type="text" name="judul" id="judul" class="w-full rounded-md border-slate-300"
                            required></input>
                    </div>

                    <label for="roles">Tujuan<span class="text-red-600">*</span></label>
                    <div class="my-2">
                        <select name="roles" id="roles" class="w-full rounded-md border-slate-300">
                            <option value="" selected>Dosen & Mahasiswa</option>
                            <option value="3">Mahasiswa</option>
                            <option value="2">Dosen</option>
                        </select>
                    </div>

                    <label for="isi">Isi Pengumuman<span class="text-red-600">*</span></label>
                    <div class="border border-slate-300 rounded-md">
                        <input id="isi" type="hidden" name="isi">
                        <trix-editor input="isi" class="trix-editor"
                            placeholder="Masukkan isi pengumuman..."></trix-editor>
                    </div>

                    <div class="my-2">
                        <label for="fileLampiran"><span>File Lampiran</span></label>
                        <input id="fileLampiran" name="fileLampiran[]" type="file" multiple
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    </div>

                    <button type="submit"
                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2 mt-5">
                        Tambah
                    </button>
                </div>
            </form>
        </dialog>
    </div>
    {{-- End Tambah Pengumuman Modal --}}

    <div style="top: 5.5rem;" class="fixed right-4 flex flex-row-reverse items-center">
        <div class="ml-3">
            <button type="button" onclick="tambahPengumuman.showModal()"
                class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300  shadow-lg shadow-green-500/50  font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Tambah
            </button>
        </div>
        <div class="">
            <form class="max-w-lg mx-auto" action="/admin/pengumuman">
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">
                    Cari
                </label>
                <div class="relative">
                    <input type="search" id="default-search" name="search" value="{{ request('search') }}"
                        class="shadow-lg text-left block w-56  md:w-64 p-2.5 ps-4 text-sm text-gray-900 border border-gray-200 focus:outline-none focus:ring-transparent rounded-xl bg-gray-50"
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
    @if (session()->has('success'))
        <div id="alert-3"
            class="flex items-center p-3 mb-6 text-white rounded-lg bg-green-400 dark:bg-gray-800 dark:text-green-400"
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
        <div id="alert-2" class="flex items-center p-3 mb-6 text-white rounded-lg bg-red-700" role="alert">
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
    @if ($pengumumans->count())
        @foreach ($pengumumans as $pengumuman)
            {{-- Confirmation Cancel Modal --}}
            <dialog id="confirmDialog_{{ $pengumuman->id }}"
                class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                <div class="relative">
                    <button onclick="closeConfirmDialog('{{ $pengumuman->id }}')" type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                            Apakah kamu yakin untuk menghapus pengumuman ini?
                        </h3>

                        <form action="{{ route('delete.pengumuman', $pengumuman->id) }}" method="POST"
                            enctype="multipart/form-data" class="inline">
                            @method('delete')
                            @csrf
                            <button type="submit"
                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Ya, saya yakin
                            </button>
                        </form>
                        <button onclick="closeConfirmDialog('{{ $pengumuman->id }}')" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                            Tidak, batal
                        </button>
                    </div>
                </div>
            </dialog>
            {{-- End Confirmation Cancel Modal --}}

            <div class="bg-white text-slate-900 p-5 rounded-lg shadow-lg border-gray-200 text-justify mb-6">
                <div class="flex flex-col-reverse md:flex-row items-start justify-between">
                    <div class="float-start">
                        <h1 class="text-xl font-bold text-slate-800">{{ $pengumuman->judul_pengumuman }}</h1>
                        <p class="text-sm">{{ \Carbon\Carbon::parse($pengumuman->created_at)->format('d M Y') }}
                            @if ($pengumuman->updated_at != null)
                                (diedit pada {{ \Carbon\Carbon::parse($pengumuman->updated_at)->format('d M Y') }})
                            @endif
                        </p>
                        @if ($pengumuman->roles_id == null)
                            <p class="text-sm">Dosen & Mahasiswa</p>
                        @else
                            <p class="text-sm">{{ $pengumuman->roles->display_name }}</p>
                        @endif
                    </div>
                    <div class="float-end justify-self-end">
                        <a href="/admin/pengumuman/edit/{{ $pengumuman->id }}" type="button"
                            class="cursor-pointer text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300  font-medium rounded-lg text-sm px-3 py-2 text-center mr-3">
                            Edit
                        </a>
                        <button type="button" onclick="showConfirm('{{ $pengumuman->id }}')"
                            class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2 text-center">
                            Hapus
                        </button>
                    </div>
                </div>
                <p class="mb-5 mt-3">
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

                <a href="/admin/pengumuman" type="button"
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
            function closeModalTambahPengumuman() {
                var modal = document.getElementById('tambahPengumuman');
                modal.close();
            }

            function show(lampiran) {
                var modal = document.getElementById('lampiran_' + lampiran);
                modal.showModal();
            }

            function closeLampiran(lampiran) {
                var modal = document.getElementById('lampiran_' + lampiran);
                modal.close();
            }

            function showConfirm(id) {
                var modal = document.getElementById('confirmDialog_' + id);
                modal.showModal();
            }

            function closeConfirmDialog(id) {
                var modal = document.getElementById('confirmDialog_' + id);
                modal.close();
            }

            function showEditPengumuman(id) {
                var modal = document.getElementById('editPengumuman_' + id);
                modal.showModal();
            }

            function closeEditPengumuman(id) {
                var modal = document.getElementById('editPengumuman_' + id);
                modal.close();
            }
        </script>
    @endsection
</x-app-layout>
