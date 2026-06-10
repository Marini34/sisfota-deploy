<x-app-layout>
    @section('title', 'Pendaftaran Sidang Akhir')
    @section('head')
        @include('styles.datatables')
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

        @if (
            $seminarSidang &&
                $rekamBimbingans1 >= 8 &&
                $rekamBimbingans2 >= 8 &&
                (($seminarSidang->tahapan_ta === 'Seminar Hasil' && $seminarSidang->status_kelulusan === 'LULUS') ||
                    $seminarSidang->tahapan_ta === 'Sidang Akhir'))
            {{-- Modal --}}
            <dialog id="modalForm" class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-3/5">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                        Pendaftaran Sidang Akhir
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
                <form action="{{ route('store.sidang') }}" method="post" enctype="multipart/form-data"
                    class="p-4 md:p-5">
                    @csrf
                    <label for="judul">Judul</label>
                    <div class="my-2">
                        <select name="judul" id="judul" class="w-full rounded-md border-slate-300" required>
                            <option value="" disabled selected hidden>Pilih Judul</option>
                            @foreach ($juduls as $judul)
                                <option value="{{ $judul->tugas_akhir->id }}">{{ $judul->tugas_akhir->judul }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="my-2">
                        <label for="dokumenSidang"><span>File Dokumen Sidang Akhir</span></label>
                        <input required id="dokumenSidang" name="dokumenSidang" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="artikel"><span>File Artikel Jurnal</span></label>
                        <input required id="artikel" name="artikel" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="syaratSidang"><span>Lembar Pemeriksaan Dokumen Persyaratan Sidang</span></label>
                        <input required id="syaratSidang" name="syaratSidang" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="accJadwalSidang"><span>Surat Acc Jadwal Sidang Akhir</span></label>
                        <input required id="accJadwalSidang" name="accJadwalSidang" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    {{-- <div class="my-2">
                        <label for="bebasLabMipa"><span>Surat Keterangan Bebas Pinjam Alat Laboratorium di Lingkungan FMIPA UNTAN</span></label>
                        <input required id="bebasLabMipa" name="bebasLabMipa" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="bebasLabNonMipa"><span>Surat Keterangan Bebas Pinjam Alat Laboratorium di Luar FMIPA UNTAN (Apabila melakukan penelitian di luar lingkungan FMIPA UNTAN)(optional)</span></label>
                        <input id="bebasLabNonMipa" name="bebasLabNonMipa" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="bebasPerpustakaanMipa"><span>Surat Keterangan Bebas Pinjam Buku di Ruang Baca FMIPA UNTAN</span></label>
                        <input required id="bebasPerpustakaanMipa" name="bebasPerpustakaanMipa" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="bebasPerpustakaanUntan"><span>Surat Keterangan Bebas Pinjam Buku di Perpustakaan UNTAN</span></label>
                        <input required id="bebasPerpustakaanUntan" name="bebasPerpustakaanUntan" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="bebasPerpustakaanWilayah"><span>Surat Keterangan Bebas Pinjam Buku di Perpustakaan Wilayah</span></label>
                        <input required id="bebasPerpustakaanWilayah" name="bebasPerpustakaanWilayah" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="SKPembimbing"><span>SK Pembimbing</span></label>
                        <input required id="SKPembimbing" name="SKPembimbing" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="SKPenguji"><span>SK Penguji</span></label>
                        <input required id="SKPenguji" name="SKPenguji" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="buktiTutep"><span>Sertifikat TOEFL yang dikeluarkan UPT Bahasa UNTAN</span></label>
                        <input required id="buktiTutep" name="buktiTutep" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="suratSidang"><span>Surat Permohonan Ujian Sidang Akhir</span></label>
                        <input required id="suratSidang" name="suratSidang" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg"> --}}

                    <div class="my-2">
                        <label for="fileLainnya"><span>File lainnya(opsional)</span></label>
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
                    <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                        Sidang Akhir
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
                            @foreach ($seminarSidangs as $index => $sidang)
                                {{-- Confirmation Delete Modal --}}
                                <dialog id="confirmDialog_{{ $sidang->slug }}"
                                    class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                                    <div class="relative">
                                        <button onclick="closeConfirmDialog('{{ $sidang->slug }}')" type="button"
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

                                            <form action="{{ route('sidang.delete', $sidang->slug) }}" method="POST"
                                                enctype="multipart/form-data" class="inline">
                                                @method('delete')
                                                @csrf
                                                <button type="submit"
                                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                    Ya, saya yakin
                                                </button>
                                            </form>
                                            <button onclick="closeConfirmDialog('{{ $sidang->slug }}')" type="button"
                                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                                Tidak, batal
                                            </button>
                                        </div>
                                    </div>
                                </dialog>
                                {{-- End Confirmation Delete Modal --}}
                                {{-- Upload Revisi Modal --}}
                                <div class="modal-content">
                                    <dialog id="modalRevisi_{{ $sidang->slug }}"
                                        class="overflow-x-hidden miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-2/5">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                                File Final Tugas Akhir
                                            </h3>
                                            <button onclick="closeDialogRevisi('{{ $sidang->slug }}')" type="button"
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
                                        @if ($sidang->status_pendaftaran === 'Diterima' && $sidang->file_notulensi != null)
                                            @php
                                                $fileAkhir = json_decode($sidang->file_notulensi, true) ?? [];
                                                $artikel = $fileAkhir[1] ?? null;
                                                $skpl = $fileAkhir[2] ?? null;
                                                $code = $fileAkhir[3] ?? null;
                                                $SKPembimbing = $fileAkhir[4] ?? null;
                                                $SKPengujiSempro = $fileAkhir[5] ?? null;
                                                $SKPengujiSemhas = $fileAkhir[6] ?? null;
                                                $lembarPengesahan = $fileAkhir[7] ?? null;
                                                $undanganSemhas = $fileAkhir[8] ?? null;
                                                $undanganSidang = $fileAkhir[9] ?? null;
                                                $buktiSubmitJurnal = $fileAkhir[10] ?? null;
                                            @endphp
                                            <form action="{{ route('ubah.revisi.sidang', $sidang->slug) }}"
                                                method="post" enctype="multipart/form-data" class="p-2">
                                                @csrf
                                                <div class="mx-5 mb-3">
                                                    <div class="my-2">

                                                        <div class="my-2">
                                                            <label for="ubahRevisiArtikel"><span
                                                                    class="text-nowrap">Artikel Jurnal (Revisi)
                                                                    <x-new-modal :file="$artikel" /></span></label>
                                                            <input id="ubahRevisiArtikel" name="ubahRevisiArtikel"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="ubahSkpl"><span>SKPL, DPPL, PDHUPL (Jika
                                                                    Rancang Bangun) <x-new-modal
                                                                        :file="$skpl" /></span></label>
                                                            <input id="ubahSkpl" name="ubahSkpl" type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="ubahCode"><span>Source Code Program (Jika
                                                                    Rancang Bangun) <x-new-modal
                                                                        :file="$code" /></span></label>
                                                            <input id="ubahCode" name="ubahCode" type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.zip)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="ubahSKPembimbing"><span>SK Pembimbing
                                                                    <x-new-modal :file="$SKPembimbing" /></span></label>
                                                            <input id="ubahSKPembimbing" name="ubahSKPembimbing"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="ubahSKPengujiSempro"><span>SK Penguji Seminar
                                                                    Proposal <x-new-modal
                                                                        :file="$SKPengujiSempro" /></span></label>
                                                            <input id="ubahSKPengujiSempro" name="ubahSKPengujiSempro"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="ubahSKPengujiSemhas"><span>SK Penguji Seminar
                                                                    Hasil <x-new-modal
                                                                        :file="$SKPengujiSemhas" /></span></label>
                                                            <input id="ubahSKPengujiSemhas" name="ubahSKPengujiSemhas"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="ubahLembarPengesahan"><span>Lembar Pengesahan
                                                                    Skripsi <x-new-modal
                                                                        :file="$lembarPengesahan" /></span></label>
                                                            <input id="ubahLembarPengesahan"
                                                                name="ubahLembarPengesahan" type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="ubahUndanganSemhas"><span>Undangan Seminar
                                                                    Hasil <x-new-modal
                                                                        :file="$undanganSemhas" /></span></label>
                                                            <input id="ubahUndanganSemhas" name="ubahUndanganSemhas"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="ubahUndanganSidang"><span>Undangan Sidang Akhir
                                                                    <x-new-modal :file="$undanganSidang" /></span></label>
                                                            <input id="ubahUndanganSidang" name="ubahUndanganSidang"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">

                                                        <div class="my-2">
                                                            <label for="ubahRevisibuktiSubmitJurnal"><span
                                                                    class="text-nowrap">Bukti Submit Jurnal
                                                                    <x-new-modal :file="$buktiSubmitJurnal" /></span></label>
                                                            <input id="ubahRevisibuktiSubmitJurnal"
                                                                name="ubahRevisibuktiSubmitJurnal" type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                    </div>
                                                    <button type="submit"
                                                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                                                        SIMPAN
                                                    </button>
                                            </form>
                                        @else
                                            <form action="{{ route('store.revisi.sidang', $sidang->slug) }}"
                                                method="post" enctype="multipart/form-data" class="p-2">
                                                @csrf
                                                <div class="mx-5 mb-3">
                                                    <div class="my-2">
                                                        {{-- <div class="my-2">
                                                            <label for="dokumenInti"><span>Dokumen Inti Tugas Akhir<span class="text-red-500 font-bold">*</span></span></label>
                                                            <input required id="dokumenInti" name="dokumenInti" type="file" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div> --}}

                                                        <div class="my-2">
                                                            <label for="revisiArtikel"><span>Artikel Jurnal
                                                                    (Revisi)
                                                                </span></label>
                                                            <input id="revisiArtikel" name="revisiArtikel"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="skpl"><span>SKPL, DPPL, PDHUPL (Jika
                                                                    Rancang Bangun)</span></label>
                                                            <input id="skpl" name="skpl" type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="code"><span>Source Code Program (Jika
                                                                    Rancang Bangun)</span></label>
                                                            <input id="code" name="code" type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.zip)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="SKPembimbing"><span>SK
                                                                    Pembimbing</span></label>
                                                            <input id="SKPembimbing" name="SKPembimbing"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="SKPengujiSempro"><span>SK Penguji Seminar
                                                                    Proposal</span></label>
                                                            <input id="SKPengujiSempro" name="SKPengujiSempro"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="SKPengujiSemhas"><span>SK Penguji Seminar
                                                                    Hasil</span></label>
                                                            <input id="SKPengujiSemhas" name="SKPengujiSemhas"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="lembarPengesahan"><span>Scan Lembar Pengesahan
                                                                    Skripsi</span></label>
                                                            <input id="lembarPengesahan" name="lembarPengesahan"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="undanganSemhas"><span>Undangan Seminar
                                                                    Hasil</span></label>
                                                            <input id="undanganSemhas" name="undanganSemhas"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="undanganSidang"><span>Undangan Sidang
                                                                    Akhir</span></label>
                                                            <input id="undanganSidang" name="undanganSidang"
                                                                type="file"
                                                                class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                                            <p class="text-sm">(.pdf)</p>
                                                        </div>
                                                        <hr class="text-slate-800 border-2 rounded-lg">
                                                        <div class="my-2">
                                                            <label for="buktiSubmitJurnal"><span>Bukti Submit
                                                                    Jurnal</span></label>
                                                            <input id="buktiSubmitJurnal" name="buktiSubmitJurnal"
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
                                <dialog id="modalDitolak_{{ $sidang->slug }}"
                                    class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-2/5">
                                    <div
                                        class="flex items-center justify-between p-3 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-white uppercase">
                                            Alasan Pendaftaran Ditolak
                                        </h3>
                                        <button onclick="closeDialogAlasan('{{ $sidang->slug }}')" type="button"
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
                                                    $alasan_penolakan = $sidang->alasan_penolakan;
                                                    // Mengecek apakah konten merupakan list (dengan tag <ol>)
                                                    if (strpos($alasan_penolakan, '<ol>') !== false) {
                                                        // Jika iya, tambahkan kelas list-decimal
                                                        $alasan_penolakan = str_replace(
                                                            '<ol>',
                                                            '<ol class="list-decimal">',
                                                            $alasan_penolakan,
                                                        );
                                                    }

                                                    if (strpos($alasan_penolakan, '<ul>') !== false) {
                                                        $alasan_penolakan = str_replace(
                                                            '<ul>',
                                                            '<ul class="list-disc">',
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
                                            {{ $sidang->tugas_akhir->judul }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($sidang->status_pendaftaran === 'Menunggu Verifikasi')
                                            <div
                                                class="p-2 w-auto text-sm text-blue-800 text-center border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800">
                                            @elseif ($sidang->status_pendaftaran === 'Diterima')
                                                <div
                                                    class="p-2 w-auto text-sm text-green-500 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800">
                                                @elseif ($sidang->status_pendaftaran === 'Ditolak')
                                                    <div
                                                        class="p-2 w-auto text-sm text-red-600 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800">
                                                    @else
                                                        <div
                                                            class="p-2 w-auto text-sm text-gray-800 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                                        @endif

                                        <div>
                                            @if ($sidang->status_kelulusan === 'LULUS' || $sidang->status_kelulusan === 'TIDAK LULUS')
                                                <span class="font-medium">{{ $sidang->status_pendaftaran }}
                                                    ({{ $sidang->status_kelulusan }})
                                                </span>
                                            @else
                                                <span class="font-medium">{{ $sidang->status_pendaftaran }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap text-center">
                                        <a href="/mahasiswa/sidang/detail/{{ $sidang->slug }}"
                                            data-tooltip-target="detail_{{ $sidang->slug }}"
                                            data-tooltip-placement="bottom" type="button"
                                            class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <div id="detail_{{ $sidang->slug }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Detail
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                        @if ($sidang->status_pendaftaran === 'Menunggu Verifikasi' || $sidang->status_pendaftaran === 'Ditolak')
                                            <a href="/mahasiswa/sidang/ubah/{{ $sidang->slug }}"
                                                data-tooltip-target="ubah_{{ $sidang->slug }}"
                                                data-tooltip-placement="bottom" type="button"
                                                onclick="modalVideo.showModal()"
                                                class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <div id="ubah_{{ $sidang->slug }}" role="tooltip"
                                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                Ubah
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                            @if ($sidang->status_pendaftaran === 'Ditolak')
                                                <button data-tooltip-target="ditolak_{{ $sidang->slug }}"
                                                    data-tooltip-placement="bottom" type="button"
                                                    onclick="showModalAlasan('{{ $sidang->slug }}')"
                                                    class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                                    <i class="bi bi-question-circle"></i>
                                                </button>

                                                <div id="ditolak_{{ $sidang->slug }}" role="tooltip"
                                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                    Alasan Ditolak
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            @endif
                                            <button data-tooltip-target="hapus_{{ $sidang->slug }}"
                                                data-tooltip-placement="bottom" type="button"
                                                onclick="showConfirm('{{ $sidang->slug }}')"
                                                class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                            <div id="hapus_{{ $sidang->slug }}" role="tooltip"
                                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                Hapus
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                        @elseif ($sidang->status_kelulusan === 'LULUS' || $sidang->status_kelulusan === 'TIDAK LULUS')
                                            <a href="/mahasiswa/sidang/hasil/{{ $sidang->slug }}"
                                                data-tooltip-target="hasil_{{ $sidang->slug }}"
                                                data-tooltip-placement="bottom" type="button"
                                                class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                                <i class="bi bi-envelope-check"></i>
                                            </a>

                                            <div id="hasil_{{ $sidang->slug }}" role="tooltip"
                                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                Hasil Sidang Akhir
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>

                                            @if ($sidang->status_pendaftaran === 'Diterima' && $sidang->file_revisi != null)
                                                <button data-tooltip-target="revisi_{{ $sidang->slug }}"
                                                    data-tooltip-placement="bottom" type="button"
                                                    onclick="showModalRevisi('{{ $sidang->slug }}')"
                                                    class="cursor-pointer text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                                    {{-- <i class="bi bi-pencil-square"></i> --}}
                                                    <i class="bi bi-upload"></i>
                                                </button>

                                                <div id="revisi_{{ $sidang->slug }}" role="tooltip"
                                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                    Upload Berkas Kelengkapan Tugas Akhir
                                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                                </div>
                                            @else
                                                <button data-tooltip-target="revisi_{{ $sidang->slug }}"
                                                    data-tooltip-placement="bottom" type="button"
                                                    onclick="showModalRevisi('{{ $sidang->slug }}')"
                                                    class="cursor-pointer text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                                    <i class="bi bi-upload"></i>
                                                </button>

                                                <div id="revisi_{{ $sidang->slug }}" role="tooltip"
                                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                    Upload Berkas Kelengkapan Tugas Akhir
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
        @elseif (
            $seminarSidang &&
                $seminarSidang->tahapan_ta !== null &&
                ($rekamBimbingans1 < 8 || $rekamBimbingans2 < 8) &&
                (($seminarSidang->tahapan_ta === 'Seminar Hasil' && $seminarSidang->status_kelulusan === 'LULUS') ||
                    $seminarSidang->tahapan_ta === 'Sidang Akhir'))
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
                    Untuk dapat melakukan pendaftaran Sidang Akhir, kamu perlu melakukan rekam bimbingan tugas akhir
                    kepada masing-masing dosen pembimbing kamu sebanyak minimal delapan kali
                </div>

                <div class="flex">
                    <a href="/mahasiswa/bimbingan" type="button"
                        class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 14">
                            <path
                                d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                        </svg>
                        Kontrol Bimbingan
                    </a>
                </div>
            </div>
            {{-- Tampilan Belum Bisa Melakukan Pendaftarah --}}
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
                    Untuk dapat melakukan pendaftaran Sidang Akhir, kamu perlu menyelesaikan Seminar Hasil yang terakhir
                    kamu daftarkan terlebih
                    dahulu.
                </div>

                <div class="flex">
                    <a href="/mahasiswa/semhas" type="button"
                        class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 14">
                            <path
                                d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                        </svg>
                        Seminar Hasil
                    </a>
                </div>
            </div>
        @endif
    </x-main-card>
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
            // Modal Alasan
            function showModalAlasan(slug) {
                var modal = document.getElementById('modalDitolak_' + slug);
                modal.showModal();
            }

            // Function to close modal for a specific seminar proposal
            function closeDialogVideo(slug) {
                var modal = document.getElementById('modalVideo_' + slug);
                modal.close();
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
    @endsection
</x-app-layout>
