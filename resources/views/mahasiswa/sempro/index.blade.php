<x-app-layout>
    @section('title', 'Pendaftaran Seminar Proposal')
    @section('head')
        @include('styles.datatables')

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container {
                width: 100% !important;
            }

            .select2-container .select2-selection--single {
                height: 42px;
                border: 1px solid #cbd5e1 !important;
                border-radius: .375rem !important;
                display: flex;
                align-items: center;
            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                line-height: 40px !important;
                padding-left: 12px !important;
                color: #111827;
            }

            .select2-container .select2-selection--single .select2-selection__arrow {
                height: 40px !important;
            }

            .select2-dropdown {
                z-index: 99999 !important;
            }
        </style>
    @endsection
    <x-main-card>
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
        @if ($notulensi)

            {{-- Modal --}}
            <dialog id="modalForm" class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                        Pendaftaran Seminar Proposal
                    </h3>
                    <button onclick="closeDialog()" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="timeline-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('store.sempro') }}" method="post" enctype="multipart/form-data"
                    class="p-4 md:p-5">
                    @csrf
                    <label for="judul">Judul Proposal</label>
                    <div class="my-2">
                        <textarea name="judul" id="judul" rows="5" class="w-full rounded-md border-slate-300"
                            placeholder="Isi dengan judul lengkap sesuai dengan yang ada di cover proposal beserta studi kasusnya...(Contoh : Evaluasi Usability Pada Website Badan Pusat Statistik Menggunakan Metode WEBUSE dan IPA (Studi Kasus : Badan Pusat Statistik Kota Pontianak))"
                            required></textarea>
                    </div>

                    <label for="no_hp">No HP</label>
                    <div class="my-2">
                        <input type="number" name="no_hp" id="no_hp" class="w-full rounded-md border-slate-300"
                            required>
                    </div>

                    <label for="notulen_mahasiswa_id">Notulen Mahasiswa</label>
                    <div class="my-2">
                        <select name="notulen_mahasiswa_id" id="notulen_mahasiswa_id"
                            class="w-full rounded-md border-slate-300 select2" required>
                            <option value=""></option>
                            @foreach ($dataMhsNotulen as $mhsData)
                                <option value="{{ $mhsData->id }}">
                                    {{ $mhsData->nim }} - {{ $mhsData->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <label for="studi_kasus">Studi Kasus</label>
                    <div class="my-2">
                        <input type="text" name="studi_kasus" id="studi_kasus"
                            class="w-full rounded-md border-slate-300" required>
                    </div>

                    <label for="metode">Metode Penelitian</label>
                    <div class="my-2">
                        <input type="text" name="metode" id="metode" class="w-full rounded-md border-slate-300"
                            required>
                    </div>

                    <label for="dosenPA">Dosen PA</label>
                    <div class="my-2">
                        <select name="dosenPA" id="dosenPA" class="w-full rounded-md border-slate-300" required>
                            <option value="" disabled selected hidden>Pilih Dosen</option>
                            @foreach ($dosen as $dsn)
                                <option value="{{ $dsn->id }}">{{ $dsn->nama_dosen }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label for="dospem1">Dosen Pembimbing 1</label>
                    <div class="my-2">
                        <select name="dospem1" id="dospem1" class="w-full rounded-md border-slate-300" required>
                            <option value="" disabled selected hidden>Pilih Dosen</option>
                            @foreach ($dosen1 as $dsn1)
                                <option value="{{ $dsn1->id }}">{{ $dsn1->nama_dosen }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label for="dospem2">Dosen Pembimbing 2</label>
                    <div class="my-2">
                        <select name="dospem2" id="dospem2" class="w-full rounded-md border-slate-300" required>
                            <option value="" disabled selected hidden>Pilih Dosen</option>
                            @foreach ($dosen as $dsn)
                                <option value="{{ $dsn->id }}">{{ $dsn->nama_dosen }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="dokumenProposal"><span>File Dokumen Proposal</span></label>
                        <input required id="dokumenProposal" name="dokumenProposal" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="buktiHadir"><span>File bukti menghadiri seminar proposal</span></label>
                        <input required id="buktiHadir" name="buktiHadir" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="buktiNotulen"><span>File bukti telah menjadi notulensi</span></label>
                        <input required id="buktiNotulen" name="buktiNotulen" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.doc/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="lirs"><span>File LIRS semester berjalan bukti telah mengambil makul
                                seminar</span></label>
                        <input required id="lirs" name="lirs" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="fileLainnya"><span>File lainnya (optional)</span></label>
                        <input id="fileLainnya" name="fileLainnya[]" type="file" multiple
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    </div>

                    <br>
                    <button type="submit"
                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                        DAFTAR
                    </button>
                </form>
            </dialog>
            {{-- End Modal --}}


            <div class="text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">Seminar Proposal
                    </h5>
                    <div class="float-end text-gray-900 dark:text-gray-100">
                        <button type="button" onclick="modalForm.showModal()"
                            class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Daftar
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-hover">
                        <thead class="text-base uppercase text-slate-800">
                            <tr class="bg-slate-300 rounded-2xl">
                                <th>No</th>
                                <th>Judul</th>
                                <th>Status Pendaftaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($seminarSidang as $index => $sempro)
                                {{-- Confirmation Cancel Modal --}}
                                <dialog id="confirmDialog_{{ $sempro->slug }}"
                                    class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                                    <div class="relative">
                                        <button onclick="closeConfirmDialog('{{ $sempro->slug }}')" type="button"
                                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                            <svg class="w-3 h-3" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                        <div class="p-4 md:p-5 text-center">
                                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                                Apakah kamu yakin untuk menghapus pendaftaran ini?
                                            </h3>

                                            <form action="{{ route('view.sempro.delete', $sempro->slug) }}"
                                                method="POST" enctype="multipart/form-data" class="inline">
                                                @method('delete')
                                                @csrf
                                                <button type="submit"
                                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                    Ya, saya yakin
                                                </button>
                                            </form>
                                            <button onclick="closeConfirmDialog('{{ $sempro->slug }}')"
                                                type="button"
                                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                                Tidak, batal
                                            </button>
                                        </div>
                                    </div>
                                </dialog>
                                {{-- End Confirmation Cancel Modal --}}
                                {{-- Link Video Modal --}}
                                <div class="modal-content">
                                    <dialog id="modalVideo_{{ $sempro->slug }}"
                                        class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-2/5">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                                Link Video Youtube Presentasi Seminar Proposal
                                            </h3>
                                            <button onclick="closeDialogVideo('{{ $sempro->slug }}')" type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
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
                                        <form action="{{ route('store.video.sempro', $sempro->slug) }}"
                                            method="post" enctype="multipart/form-data" class="p-2">
                                            @csrf
                                            <div class="mx-5 mb-3">
                                                <label for="video">Link Video Youtube Presentasi Seminar
                                                    Proposal</label>
                                                <div class="my-2">
                                                    @if ($sempro->status_pendaftaran === 'Diterima' && $sempro->link_video != null)
                                                        <textarea name="video" id="video" class="w-full rounded-md border-slate-300" required>{{ $sempro->link_video }}</textarea>
                                                    @else
                                                        <textarea name="video" id="video" class="w-full rounded-md border-slate-300" required></textarea>
                                                    @endif
                                                </div>
                                                <button type="submit"
                                                    class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                                                    SIMPAN
                                                </button>
                                            </div>
                                        </form>
                                    </dialog>
                                </div>
                                {{-- End Link Video Modal --}}

                                {{-- Upload Revisi Modal --}}
                                <div class="modal-content">
                                    <dialog id="modalRevisi_{{ $sempro->slug }}"
                                        class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-2/5">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                                File Revisi Seminar Proposal
                                            </h3>
                                            <button onclick="closeDialogRevisi('{{ $sempro->slug }}')" type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
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
                                        @if ($sempro->status_pendaftaran === 'Diterima' && $sempro->file_revisi != null)
                                            <form action="{{ route('ubah.revisi.sempro', $sempro->slug) }}"
                                                method="post" enctype="multipart/form-data" class="p-2">
                                                @csrf
                                                <div class="mx-5 mb-3">
                                                    <div class="my-2">
                                                        <div class="my-2">
                                                            <label for="fileRevisi"><span>Ubah File Revisi
                                                                    ({{ $sempro->file_revisi }})
                                                                </span></label>
                                                            <input required id="fileRevisi" name="fileRevisi"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                                                        SIMPAN
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <form action="{{ route('store.revisi.sempro', $sempro->slug) }}"
                                                method="post" enctype="multipart/form-data" class="p-2">
                                                @csrf
                                                <div class="mx-5 mb-3">
                                                    <div class="my-2">
                                                        <div class="my-2">
                                                            <label for="fileRevisi"><span>Upload File
                                                                    Revisi</span></label>
                                                            <input required id="fileRevisi" name="fileRevisi"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                    </div>
                                                    <button type="submit"
                                                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                                                        SIMPAN
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </dialog>
                                </div>
                                {{-- End Upload Revisi Modal --}}

                                {{-- Alasan Penolakan Modal --}}
                                <dialog id="modalDitolak_{{ $sempro->slug }}"
                                    class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-2/5">
                                    <div
                                        class="flex items-center justify-between p-3 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-white uppercase">
                                            Alasan Pendaftaran Ditolak
                                        </h3>
                                        <button onclick="closeDialogAlasan('{{ $sempro->slug }}')" type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-toggle="timeline-modal">
                                            <i class="bi bi-x-lg"></i>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <div class="flex items-start gap-2.5 px-5 border-gray-200 bg-red-100">
                                        <div class="flex flex-col w-full leading-1.5 p-4">
                                            <div class="py-2.5 text-gray-900">
                                                @php
                                                    // Parsing konten dari variabel $sempro->alasan_penolakan
                                                    $alasan_penolakan = $sempro->alasan_penolakan;
                                                    // Mengecek apakah konten merupakan list (dengan tag <ol>)
                                                    if (strpos($alasan_penolakan, '<ol>') !== false) {
                                                        // Jika iya, tambahkan kelas list-decimal
                                                        $alasan_penolakan = str_replace(
                                                            '<ol>',
                                                            '<ol class="list-decimal px-7">',
                                                            $alasan_penolakan,
                                                        );
                                                    }

                                                    if (strpos($alasan_penolakan, '<ul>') !== false) {
                                                        $alasan_penolakan = str_replace(
                                                            '<ul>',
                                                            '<ul class="list-disc px-7">',
                                                            $alasan_penolakan,
                                                        );
                                                    }
                                                    // Tampilkan konten yang sudah dimodifikasi
                                                    echo $alasan_penolakan;
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </dialog>
                                {{-- End Alasan Penolakan Modal --}}

                                <tr>
                                    <td class="text-center">{{ $index + 1 }}.</td>
                                    <td>
                                        <div class="min-w-48">
                                            {{ $sempro->tugas_akhir->judul }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($sempro->status_pendaftaran === 'Menunggu Verifikasi')
                                            <div
                                                class="p-2 w-auto text-sm text-blue-800 text-center border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800">
                                            @elseif ($sempro->status_pendaftaran === 'Diterima')
                                                <div
                                                    class="p-2 w-auto text-sm text-green-500 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800">
                                                @elseif ($sempro->status_pendaftaran === 'Ditolak')
                                                    <div
                                                        class="p-2 w-auto text-sm text-red-600 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800">
                                                    @else
                                                        <div
                                                            class="p-2 w-auto text-sm text-gray-800 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                                        @endif

                                        @if ($sempro->status_kelulusan === 'LULUS' || $sempro->status_kelulusan === 'TIDAK LULUS')
                                            <span class="font-medium">{{ $sempro->status_pendaftaran }}
                                                ({{ $sempro->status_kelulusan }})</span>
                                        @else
                                            <span class="font-medium">{{ $sempro->status_pendaftaran }}</span>
                                        @endif
                </div>
                </td>
                <td class="whitespace-nowrap text-center">
                    <a href="/mahasiswa/sempro/detail/{{ $sempro->slug }}"
                        data-tooltip-target="detail_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                        type="button"
                        class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                        <i class="bi bi-eye"></i>
                    </a>

                    <div id="detail_{{ $sempro->slug }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Detail
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    @if ($sempro->status_pendaftaran === 'Menunggu Verifikasi')
                        <a href="/mahasiswa/sempro/ubah/{{ $sempro->slug }}"
                            data-tooltip-target="ubah_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                            type="button"
                            class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <div id="ubah_{{ $sempro->slug }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>


                        <button data-tooltip-target="hapus_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                            type="button" onclick="showConfirm('{{ $sempro->slug }}')"
                            class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                            <i class="bi bi-trash-fill"></i>
                        </button>

                        <div id="hapus_{{ $sempro->slug }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Hapus
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    @elseif ($sempro->status_pendaftaran === 'Diterima' && $sempro->link_video == null)
                        <button data-tooltip-target="upload_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                            type="button" onclick="showModal('{{ $sempro->slug }}')"
                            class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                            <i class="bi bi-upload"></i>
                        </button>

                        <div id="upload_{{ $sempro->slug }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Upload link video
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    @elseif (
                        $sempro->status_pendaftaran === 'Diterima' &&
                            $sempro->link_video != null &&
                            $sempro->status_kelulusan === 'Menunggu Keputusan')
                        <button data-tooltip-target="ubahUpload_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                            type="button" onclick="showModal('{{ $sempro->slug }}')"
                            class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                            <i class="bi bi-upload"></i>
                        </button>

                        <div id="ubahUpload_{{ $sempro->slug }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah link video
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    @elseif ($sempro->status_pendaftaran === 'Ditolak')
                        <button data-tooltip-target="ditolak_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                            type="button" onclick="showModalAlasan('{{ $sempro->slug }}')"
                            class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                            <i class="bi bi-question-circle"></i>
                        </button>

                        <div id="ditolak_{{ $sempro->slug }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Alasan Ditolak
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>

                        <a href="/mahasiswa/sempro/ubah/{{ $sempro->slug }}"
                            data-tooltip-target="ubah_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                            type="button"
                            class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <div id="ubah_{{ $sempro->slug }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>

                        <button data-tooltip-target="hapus_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                            type="button" onclick="showConfirm('{{ $sempro->slug }}')"
                            class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                            <i class="bi bi-trash-fill"></i>
                        </button>

                        <div id="hapus_{{ $sempro->slug }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Hapus
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    @elseif ($sempro->status_kelulusan === 'LULUS' || $sempro->status_kelulusan === 'TIDAK LULUS')
                        <a href="/mahasiswa/sempro/hasil/{{ $sempro->slug }}"
                            data-tooltip-target="hasil_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                            type="button"
                            class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                            <i class="bi bi-envelope-check"></i>
                        </a>

                        <div id="hasil_{{ $sempro->slug }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Hasil Sempro
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>

                        @if ($sempro->status_pendaftaran === 'Diterima' && $sempro->file_revisi != null)
                            <button data-tooltip-target="revisi_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                                type="button" onclick="showModalRevisi('{{ $sempro->slug }}')"
                                class="cursor-pointer text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <div id="revisi_{{ $sempro->slug }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Ubah File Revisi Proposal
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        @else
                            <button data-tooltip-target="revisi_{{ $sempro->slug }}" data-tooltip-placement="bottom"
                                type="button" onclick="showModalRevisi('{{ $sempro->slug }}')"
                                class="cursor-pointer text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <div id="revisi_{{ $sempro->slug }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Upload Revisi Proposal
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        @endif
                    @endif
                </td>
                </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        </div>
    @else
        <div id="alert-additional-content-1"
            class="p-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
            role="alert">
            <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <h3 class="text-lg font-medium">Pendaftaran Belum Bisa Dilakukan</h3>
            </div>

            <div class="mt-2 mb-4 text-sm">
                Untuk dapat melakukan pendaftaran, Minimal kamu harus menjadi notulen pada 1 seminar proposal atau
                seminar hasil agar dapat melakukan pendaftaran seminar proposal.
            </div>

            <div class="flex">
                <a href="/mahasiswa/jadwal-notulen" type="button"
                    class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 14">
                        <path
                            d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                    </svg>
                    Jadwal Notulensi
                </a>
            </div>
        </div>
        {{-- Tampilan Belum Bisa Melakukan Pendaftarah --}}
        @endif
    </x-main-card>
    @section('script')
        <script>
            // close modal
            const dialog = document.getElementById("modalForm");

            function closeDialog() {
                dialog.close();
            }

            // Function to show modal for a specific seminar proposal
            function showModal(slug) {
                var modal = document.getElementById('modalVideo_' + slug);
                modal.showModal();
            }

            // Function to close modal for a specific seminar proposal
            function closeDialogVideo(slug) {
                var modal = document.getElementById('modalVideo_' + slug);
                modal.close();
            }

            // Modal Alasan
            function showModalAlasan(slug) {
                var modal = document.getElementById('modalDitolak_' + slug);
                modal.showModal();
            }

            function closeDialogAlasan(slug) {
                var modal = document.getElementById('modalDitolak_' + slug);
                modal.close();
            }

            // Modal Revisi
            function showModalRevisi(slug) {
                var modal = document.getElementById('modalRevisi_' + slug);
                modal.showModal();
            }

            function closeDialogRevisi(slug) {
                var modal = document.getElementById('modalRevisi_' + slug);
                modal.close();
            }

            function showConfirm(slug) {
                var modal = document.getElementById('confirmDialog_' + slug);
                modal.showModal();
            }

            function closeConfirmDialog(slug) {
                var modal = document.getElementById('confirmDialog_' + slug);
                modal.close();
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
            let table = new DataTable('#myTable', {
                responsive: true,
                autoWidth: false,
                paging: true,
                searching: true,
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                    emptyTable: "Data pendaftaran tidak ada, lakukan pendaftaran terlebih dahulu"
                },
                columnDefs: [{
                        targets: [-1],
                        orderable: false,
                        className: "dt-head-center"
                    },
                    {
                        targets: [0, 1, 2],
                        className: "dt-head-center"
                    },
                    {
                        width: "5%",
                        targets: 0
                    },
                    {
                        width: "58%",
                        targets: 1
                    },
                    {
                        width: "25%",
                        targets: 2
                    },
                    {
                        width: "10%",
                        targets: 3
                    },
                ],
                search: {
                    search: ""
                }
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#notulen_mahasiswa_id').select2({
                    width: '100%',
                    placeholder: 'Pilih Mahasiswa...',
                    allowClear: true,
                    dropdownParent: $('#modalForm')
                });
            });
        </script>
    @endsection
</x-app-layout>
