<x-app-layout>
    @section('title', 'Edit Pengumuman')
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
        </style>
    @endsection
    <x-main-card>
        <form action="{{ route('edit.pengumuman.store', $pengumuman->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="judul">Judul Pengumuman<span class="text-red-600">*</span></label>
                <div class="my-2">
                    <input type="text" name="judul" id="judul"
                        value="{{ old('judul', $pengumuman->judul_pengumuman) }}"
                        class="w-full rounded-md border-slate-300" required></input>
                </div>

                <label for="roles">Tujuan<span class="text-red-600">*</span></label>
                <div class="my-2">
                    <select name="roles" id="roles" class="w-full rounded-md border-slate-300">
                        <option value="" @if (old('roles', $pengumuman->roles_id) == $pengumuman->roles_id) selected @endif>Dosen &
                            Mahasiswa</option>
                        <option value="3" @if (old('roles', $pengumuman->roles_id) == $pengumuman->roles_id) selected @endif>Mahasiswa
                        </option>
                        <option value="2" @if (old('roles', $pengumuman->roles_id) == $pengumuman->roles_id) selected @endif>Dosen
                        </option>
                    </select>
                </div>

                <label for="isiPengumuman">Isi Pengumuman<span class="text-red-600">*</span></label>
                <div class="border border-slate-300 rounded-md">
                    <input id="isiPengumuman" type="hidden" name="isiPengumuman" value="">
                    <trix-editor input="isiPengumuman" class="trix-editor"
                        placeholder="Masukkan isi pengumuman...">{!! $pengumuman->isi_pengumuman !!}</trix-editor>
                </div>             
                @php
                    if ($pengumuman->lampiran != null) {
                        $lampiranArray = json_decode($pengumuman->lampiran);
                        for ($i = 0; $i < count($lampiranArray); $i++) {
                            $nilai[$i] = isset($lampiranArray[$i]) ? $lampiranArray[$i] . ', ' : null;
                        }
                    }
                @endphp
                <div class="my-2">
                    <label for="fileLampiran"><span>File Lampiran (<?php
                    if ($pengumuman->lampiran != null) {
                        for ($i = 0; $i < count($lampiranArray); $i++) {
                            echo $nilai[$i];
                        }
                    }
                    ?>)</span></label>
                    <input id="fileLampiran" name="fileLampiran[]" type="file" multiple
                        class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                </div>
                <div class="grid grid-cols-2 gap-10 mt-10">
                    <a href="/admin/pengumuman" type="button"
                        class="cursor-pointer text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                        Kembali
                    </a>
                    <button type="submit" 
                        class="cursor-pointer text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                        Edit
                    </button>
                </div>
            </div>
        </form>
    </x-main-card>
    @section('script')
    
    @endsection
</x-app-layout>
