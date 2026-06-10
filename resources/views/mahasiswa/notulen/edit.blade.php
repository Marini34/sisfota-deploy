<x-app-layout>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $title }}</h2>

            <a href="{{ route('jadwal-notulen') }}" class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-sm">
                Kembali
            </a>
        </div>

        <div class="mb-5 text-sm text-slate-700 dark:text-slate-300 space-y-2">
            <div><b>Mahasiswa:</b> {{ $row->mahasiswa->nama_lengkap ?? '-' }} ({{ $row->mahasiswa->nim ?? '-' }})</div>
            <div><b>Judul TA:</b> {{ $row->tugasAkhir->judul ?? '-' }}</div>
            <div><b>Tahapan:</b> {{ $row->tahapan_ta ?? '-' }}</div>
            <div><b>Tanggal Pelaksanaan:</b>
                {{ $row->tanggal_pelaksanaan ? \Carbon\Carbon::parse($row->tanggal_pelaksanaan)->format('d-m-Y') : '-' }}
            </div>
        </div>

        <form action="{{ route('jadwal-notulen.update', $row->slug) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">
                    Catatan Tugas Akhir oleh Notulen
                </label>
                @php
                    $catatan = json_decode($row->catatan_ta_notulen_mahasiswa_id, true) ?? [];
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Catatan Penguji 1
                        </label>
                        @if ($catatan && $row->status_validasi_notulen_mhs !== 'Menunggu')
                            <textarea disabled name="catatan_penguji_1" rows="6"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-slate-400 outline-none" required>{{ old('catatan_penguji_1', $catatan['catatan_penguji_1'] ?? '') }}</textarea>
                        @else
                            <textarea name="catatan_penguji_1" rows="6"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-slate-400 outline-none" required>{{ old('catatan_penguji_1', $catatan['catatan_penguji_1'] ?? '') }}</textarea>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Catatan Penguji 2
                        </label>
                        @if ($catatan && $row->status_validasi_notulen_mhs !== 'Menunggu')
                            <textarea disabled name="catatan_penguji_2" rows="6"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-slate-400 outline-none" required>{{ old('catatan_penguji_2', $catatan['catatan_penguji_2'] ?? '') }}</textarea>
                        @else
                            <textarea name="catatan_penguji_2" rows="6"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-slate-400 outline-none" required>{{ old('catatan_penguji_2', $catatan['catatan_penguji_2'] ?? '') }}</textarea>
                        @endif
                    </div>

                </div>

                @error('catatan_ta_notulen_mahasiswa_id')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex justify-end gap-2">
                @if ($catatan && $row->status_validasi_notulen_mhs !== 'Menunggu')
                @else
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-700 text-white text-sm">
                        Simpan Catatan
                    </button>
                    <a href="{{ route('jadwal-notulen') }}"
                        class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-sm">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>
