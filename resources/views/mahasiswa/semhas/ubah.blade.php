<x-app-layout>
    @section('title', 'ubah Seminar Hasil')
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
            @php
                $lampiranArray = json_decode($semhas->file_lampiran);
                for ($i = 1; $i < count($lampiranArray); $i++) {
                    $nilai[$i] = isset($lampiranArray[$i]) ? $lampiranArray[$i] . ', ' : null;
                }

            @endphp
            <form action="{{ route('store.semhas.ubah', $semhas->slug) }}" method="post" enctype="multipart/form-data"
                class="p-4 md:p-5">
                @csrf
                <label for="judul">Judul</label>
                <div class="my-2">
                    <select name="judul" id="judul" class="w-full rounded-md border-slate-300" required>
                        @foreach ($juduls as $judul)
                            <option value="{{ $judul->tugas_akhir->id }}"
                                @if ($semhas->tugas_akhir->judul == $judul->tugas_akhir->judul) 
                                    selected 
                                @endif>{{ $judul->tugas_akhir->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="my-2">
                    <label for="dokumenSemhas"><span>File Dokumen Seminar Hasil
                            ({{ $semhas->dokumen_ta }})</span></label>
                    <input id="dokumenSemhas" name="dokumenSemhas" type="file"
                        class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    <p class="text-sm">(.pdf)</p>
                    @error('dokumenSemhas')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <hr class="text-slate-800 border-2 rounded-lg">

                <div class="my-2">
                    <label for="accJadwal"><span>Surat ACC Jadwal Seminar ({{ $nilai[1] }})</span></label>
                    <input required id="accJadwal" name="accJadwal" type="file"
                        class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    <p class="text-sm">(.pdf)</p>
                    @error('accJadwal')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <hr class="text-slate-800 border-2 rounded-lg">

                <div class="my-2">
                    <label for="fileLainnya"><span>File lainnya (<?php
                    for ($i = 2; $i < count($lampiranArray); $i++) {
                        echo $nilai[$i];
                    } ?>) </span></label>
                    <input id="fileLainnya" name="fileLainnya[]" type="file" multiple class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    @error('lainnya')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <br>
                <div class="float-end">
                    <a href="/mahasiswa/semhas" type="button"
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
