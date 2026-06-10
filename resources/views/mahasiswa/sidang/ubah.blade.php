<x-app-layout>
    @section('title', 'ubah Sidang Akhir')
    <x-main-card>
            @if (session()->has('fail'))
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
            <form action="{{ route('store.sidang.ubah', $sidang->slug) }}" method="post" enctype="multipart/form-data"
                class="p-4 md:p-5">
                @csrf
                <label for="judul">Judul</label>
                <div class="my-2">
                    <select name="judul" id="judul" class="w-full rounded-md border-slate-300" required>
                        @foreach ($juduls as $judul)
                            <option value="{{ $judul->tugas_akhir->id }}"
                                @if ($sidang->tugas_akhir->judul == $judul->tugas_akhir->judul) selected @endif>{{ $judul->tugas_akhir->judul }}
                            </option>
                        @endforeach
                    </select>
                    @error('judul')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <div class="my-2">
                    <label for="dokumenSidang"><span>File Dokumen Sidang Akhir
                            ({{ $sidang->dokumen_ta }})</span></label>
                    <input id="dokumenSidang" name="dokumenSidang" type="file"
                        class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    <p class="text-sm">(.pdf)</p>
                    @error('dokumenSidang')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <hr class="text-slate-800 border-2 rounded-lg">
                @if ($sidang->file_lampiran)
                    @php
                        $lampiranSidang = $sidang ? json_decode($sidang->file_lampiran) : null;
                        $artikel = $lampiranSidang[1] ?? null;
                        $syaratSidang = $lampiranSidang[2] ?? null;
                        $accJadwalSidang = $lampiranSidang[3] ?? null;
                        // $bebasLabMipa = $lampiranSidang[2] ?? null;
                        // $bebasLabNonMipa = $lampiranSidang[3] ?? null;
                        // $bebasPerpusMipa = $lampiranSidang[4] ?? null;
                        // $bebasPerpusUntan = $lampiranSidang[5] ?? null;
                        // $bebasPerpusWilayah = $lampiranSidang[6] ?? null;
                        // $SKPembimbing = $lampiranSidang[7] ?? null;
                        // $SKPenguji = $lampiranSidang[8] ?? null;
                        // $tutep = $lampiranSidang[9] ?? null;
                        // $suratSidang = $lampiranSidang[10] ?? null;
                        for ($i = 4; $i < count($lampiranSidang); $i++) {
                            $nilai[$i] = isset($lampiranSidang[$i]) ? $lampiranSidang[$i] . ', ' : null;
                        }
                    @endphp

                    <div class="my-2">
                        <label for="artikel"><span>Dokumen Artikel Jurnal ({{ $artikel }})</span></label>
                        <input id="artikel" name="artikel" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                        @error('artikel')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">
                    
                    <div class="my-2">
                        <label for="syaratSidang"><span>Lembar Pemerikasaan Dokumen Persyaratan Sidang ({{ $syaratSidang }})</span></label>
                        <input id="syaratSidang" name="syaratSidang" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                        @error('syaratSidang')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">
                    
                    <div class="my-2">
                        <label for="accJadwalSidang"><span>Surat Acc Jadwal Sidang Akhir ({{ $accJadwalSidang }})</span></label>
                        <input id="accJadwalSidang" name="accJadwalSidang" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                        @error('accJadwalSidang')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    {{-- <div class="my-2">
                        <label for="bebasLabMipa"><span>Surat Keterangan Bebas Pinjam Alat Laboratorium di Lingkungan FMIPA UNTAN ({{ $bebasLabMipa }})</span></label>
                        <input id="bebasLabMipa" name="bebasLabMipa" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                        @error('bebasLabMipa')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="bebasLabNonMipa"><span>Surat Keterangan Bebas Pinjam Alat Laboratorium di Luar FMIPA UNTAN (Apabila melakukan penelitian di luar lingkungan FMIPA UNTAN) ({{ $bebasLabNonMipa }})</span></label>
                        <input id="bebasLabNonMipa" name="bebasLabNonMipa" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                        @error('bebasLabNonMipa')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="bebasPerpustakaanMipa"><span>Surat Keterangan Bebas Pinjam Buku di Ruang Baca FMIPA UNTAN ({{ $bebasPerpusMipa }})</span></label>
                        <input id="bebasPerpustakaanMipa" name="bebasPerpustakaanMipa" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                        @error('bebasPerpustakaanMipa')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="bebasPerpustakaanUntan"><span>Surat Keterangan Bebas Pinjam Buku di Perpustakaan UNTAN ({{ $bebasPerpusUntan }})</span></label>
                        <input id="bebasPerpustakaanUntan" name="bebasPerpustakaanUntan" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                        @error('bebasPerpustakaanUntan')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="bebasPerpustakaanWilayah"><span>Surat Keterangan Bebas Pinjam Buku di Perpustakaan Wilayah ({{ $bebasPerpusWilayah }})</span></label>
                        <input id="bebasPerpustakaanWilayah" name="bebasPerpustakaanWilayah" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                        @error('bebasPerpustakaanWilayah')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="SKPembimbing"><span>SK Pembimbing ({{ $SKPembimbing }})</span></label>
                        <input id="SKPembimbing" name="SKPembimbing" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                        @error('SKPembimbing')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="SKPenguji"><span>SK Penguji ({{ $SKPenguji }})</span></label>
                        <input id="SKPenguji" name="SKPenguji" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                        @error('SKPenguji')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="buktiTutep"><span>Sertifikat TOEFL yang dikeluarkan UPT Bahasa UNTAN ({{ $tutep }})</span></label>
                        <input id="buktiTutep" name="buktiTutep" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                        @error('buktiTutep')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="suratSidang"><span>Surat Permohonan Ujian Sidang Akhir ({{ $suratSidang }})</span></label>
                        <input id="suratSidang" name="suratSidang" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                        @error('suratSidang')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg"> --}}

                    <div class="my-2">
                        <label for="fileLainnya"><span>File lainnya (<?php
                        for ($i = 11; $i < count($lampiranSidang); $i++) {
                            echo $lampiranSidang[$i];
                        } ?>) </span></label>
                        <input id="fileLainnya" name="fileLainnya[]" type="file" multiple
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        @error('fileLainnya')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                @endif
                <br>
                <div class="float-end">
                    <a href="/mahasiswa/sidang" type="button"
                        class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                        Kembali
                    </a>
                    <button type="submit"
                        class="text-center text-white text-sm font-medium bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                        Simpan
                    </button>
                </div>
            </form>
        
    </x-main-card>
</x-app-layout>
