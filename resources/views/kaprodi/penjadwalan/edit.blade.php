<x-app-layout>
    @section('title', 'Penjadwalan Sempro')

    @section('head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* biar select2 nyatu sama tailwind input */
        .select2-container .select2-selection--single {
            height: 42px;
            border: 1px solid #cbd5e1;
            border-radius: .5rem;
            display: flex;
            align-items: center;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 12px;
        }
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
    </style>
@endsection

    <div class="text-gray-900 dark:text-gray-100">


        <div class="flex items-center justify-between mb-4">
            <div>
                <h5 class="text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                    {{ $title }}
                </h5>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">

                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Mahasiswa</div>
                            <div class="font-semibold text-base">
                                {{ $ss->mahasiswa?->nama_lengkap ?? '-' }}
                                <span class="text-slate-500">({{ $ss->mahasiswa?->nim ?? '-' }})</span>
                            </div>
                        </div>

                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Judul Tugas Akhir</div>
                            <div class="font-semibold">
                                {{ $ss->tugasAkhir?->judul ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Dosen Pembimbing 1</div>
                            <div class="font-semibold">
                                {{ $ss->tugasAkhir?->dosen_pembimbing_1->nama_dosen ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-slate-500 dark:text-slate-400">Dosen Pembimbing 2</div>
                            <div class="font-semibold">
                                {{ $ss->tugasAkhir?->dosen_pembimbing_2->nama_dosen ?? '-' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <form method="POST" action="{{ route('penjadwalan.update', $ss->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                {{-- Penguji --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Penguji 1</label>
                        <select name="dosen_penguji_1_id"
                            class="select2 w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                            <option value="">-- Pilih Penguji 1 --</option>
                            @foreach($dosenList as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('dosen_penguji_1_id', $ss->tugasAkhir?->dosen_penguji_1_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama_dosen }}
                                </option>
                            @endforeach
                        </select>
                        @error('dosen_penguji_1_id')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Penguji 2</label>
                        <select name="dosen_penguji_2_id"
                            class="select2 w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                            <option value="">-- Pilih Penguji 2 --</option>
                            @foreach($dosenList as $d)
                                <option value="{{ $d->id }}"
                                    {{ old('dosen_penguji_2_id', $ss->tugasAkhir?->dosen_penguji_2_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama_dosen }}
                                </option>
                            @endforeach
                        </select>
                        @error('dosen_penguji_2_id')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                    <br>

                {{-- Jadwal --}}
                {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_pelaksanaan"
                            value="{{ old('tanggal_pelaksanaan', $ss->tanggal_pelaksanaan) }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                        @error('tanggal_pelaksanaan')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Jam Pelaksanaan</label>
                        <input type="time" name="jam_pelaksanaan"
                            value="{{ old('jam_pelaksanaan', $ss->jam_pelaksanaan) }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                        @error('jam_pelaksanaan')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Tempat Pelaksanaan</label>
                        <input type="text" name="tempat_pelaksanaan"
                            value="{{ old('tempat_pelaksanaan', $ss->tempat_pelaksanaan) }}"
                            placeholder="Contoh: Ruang Sidang 1 / Zoom"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                        @error('tempat_pelaksanaan')
                            <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('penjadwalan.index') }}"
                       class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-sm">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-700 text-white text-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
   @section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Pilih dosen...',
                allowClear: true
            });
        });
    </script>
@endsection
</x-app-layout>
