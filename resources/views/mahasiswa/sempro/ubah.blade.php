<x-app-layout>
    @section('title', 'Ubah Seminar Proposal')
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
            <form action="{{ route('store.sempro.ubah', $sempro->slug) }}" method="post" enctype="multipart/form-data"
                class="p-4 md:p-5">
                @csrf
                <label for="no_hp">No HP</label>
                <div class="my-2">
                    <textarea name="no_hp" id="no_hp" class="w-full rounded-md border-slate-300" required>{!! old('no_hp', htmlspecialchars($sempro->tugas_akhir->no_hp)) !!}</textarea>
                </div>
                <label for="judul">Judul Proposal</label>
                <div class="my-2">
                    <textarea name="judul" id="judul" class="w-full rounded-md border-slate-300" required>{!! old('judul', htmlspecialchars($sempro->tugas_akhir->judul)) !!}</textarea>
                </div>

                <label for="studi_kasus">Studi Kasus</label>
                <div class="my-2">
                    <input value="{{ old('studi_kasus', $sempro->tugas_akhir->studi_kasus) }}" type="text"
                        name="studi_kasus" id="studi_kasus" class="w-full rounded-md border-slate-300" required>
                </div>

                <label for="metode">Metode Penelitian</label>
                <div class="my-2">
                    <input type="text" name="metode" id="metode"
                        value="{{ old('metode', $sempro->tugas_akhir->metode) }}"
                        class="w-full rounded-md border-slate-300" required>
                </div>

                <label for="dosenPA">Dosen PA</label>
                <div class="my-2">
                    <select name="dosenPA" id="dosenPA" class="w-full rounded-md border-slate-300" required>
                        {{-- <option hidden value="{{ old('dosenPA', $sempro->tugas_akhir->dosen_PA_id)}}">{{old('dosenPA', $sempro->tugas_akhir->dosen_PA->nama_dosen)}}</option> --}}
                        @foreach ($dosen as $dsn)
                            <option value="{{ $dsn->id }}" @if (old('dosenPA', $sempro->tugas_akhir->dosen_PA->nama_dosen) ==  $dsn->nama_dosen) selected @endif>
                                {{ $dsn->nama_dosen }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <label for="dospem1">Dosen Pembimbing 1</label>
                <div class="my-2">
                    <select name="dospem1" id="dospem1" class="w-full rounded-md border-slate-300" required>
                        @foreach ($dosen as $dsn)
                            <option value="{{ $dsn->id }}" @if (old('dospem1', $sempro->tugas_akhir->dosen_pembimbing_1->nama_dosen) == $dsn->nama_dosen) selected @endif>
                                {{ $dsn->nama_dosen }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <label for="dospem2">Dosen Pembimbing 2</label>
                <div class="my-2">
                    <select name="dospem2" id="dospem2" class="w-full rounded-md border-slate-300" required>
                        @foreach ($dosen as $dsn)
                            <option value="{{ $dsn->id }}" @if (old('dospem2', $sempro->tugas_akhir->dosen_pembimbing_2->nama_dosen) == $dsn->nama_dosen) selected @endif>
                                {{ $dsn->nama_dosen }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="my-2">
                    <label for="proposal"><span>File Dokumen Proposal ({{ $sempro->dokumen_ta }})</span></label>
                    <input id="proposal" name="proposal" type="file"
                        class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 @error('proposal') border-red-600 @enderror rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                    <p class="text-sm">(.pdf)</p>
                    @error('proposal')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <hr class="text-slate-800 border-2 rounded-lg">
                @if ($sempro->file_lampiran)
                    @php
                        $lampiranArray = json_decode($sempro->file_lampiran);
                        $nilai1 = $lampiranArray[1] ?? null;
                        $nilai2 = $lampiranArray[2] ?? null;
                        $nilai3 = $lampiranArray[3] ?? null;
                        for ($i = 4; $i < count($lampiranArray); $i++) {
                            $nilai[$i] = isset($lampiranArray[$i]) ? $lampiranArray[$i] . ', ' : null;
                        }
                    @endphp

                    <div class="my-2">
                        <label for="hadirSeminar"><span>File bukti menghadiri seminar proposal
                                ({{ $nilai1 }})</span></label>
                        <input id="hadirSeminar" name="hadirSeminar" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 @error('proposal') border-red-600 @enderror rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.pdf)</p>
                        @error('hadirSeminar')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="buktiNotulensi"><span>File bukti telah menjadi notulensi
                                ({{ $nilai2 }})</span></label>
                        <input id="buktiNotulensi" name="buktiNotulensi" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 @error('proposal') border-red-600 @enderror rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.doc/.pdf)</p>
                        @error('buktiNotulensi')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="buktiLirs"><span>File LIRS semester berjalan bukti telah mengambil makul seminar
                                ({{ $nilai3 }})</span></label>
                        <input id="buktiLirs" name="buktiLirs" type="file"
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 @error('proposal') border-red-600 @enderror rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.pdf)</p>
                        @error('buktiLirs')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <hr class="text-slate-800 border-2 rounded-lg">

                    <div class="my-2">
                        <label for="lainnya"><span>File lainnya (<?php
                        for ($i = 4; $i < count($lampiranArray); $i++) {
                            echo $nilai[$i];
                        } ?>) </span></label>
                        <input id="lainnya" name="lainnya[]" type="file" multiple
                            class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 @error('proposal') border-red-600 @enderror rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                        <p class="text-sm">(.jpeg/.jpg/.png/.doc/.pdf)</p>
                        @error('buktiLirs')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                @endif

                <br>
                <div class="float-end">
                    <a href="/mahasiswa/sempro" type="button"
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
