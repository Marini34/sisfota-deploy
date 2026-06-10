<x-app-layout>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 max-w-3xl mx-auto">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ $title }}
            </h2>

            <a href="{{ route('jadwal-bimbingan.index') }}"
               class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-sm">
                Kembali
            </a>
        </div>

        <form action="{{ route('jadwal-bimbingan.update', $row->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Tanggal --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Tanggal Bimbingan
                </label>
                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal', optional($row->tanggal)->format('Y-m-d') ?? $row->tanggal) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2
                              focus:ring-2 focus:ring-slate-400 outline-none">

                @error('tanggal')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jam --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Jam Mulai
                    </label>
                    <input type="time"
                           name="jam_mulai"
                           value="{{ old('jam_mulai', substr($row->jam_mulai,0,5)) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2
                                  focus:ring-2 focus:ring-slate-400 outline-none">

                    @error('jam_mulai')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Jam Selesai
                    </label>
                    <input type="time"
                           name="jam_selesai"
                           value="{{ old('jam_selesai', substr($row->jam_selesai,0,5)) }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2
                                  focus:ring-2 focus:ring-slate-400 outline-none">

                    @error('jam_selesai')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Lokasi --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Lokasi / Platform
                </label>
                <input type="text"
                       name="lokasi"
                       value="{{ old('lokasi', $row->lokasi) }}"
                       placeholder="Contoh: Ruang 203 / Zoom"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2
                              focus:ring-2 focus:ring-slate-400 outline-none">

                @error('lokasi')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Kuota --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Kuota Mahasiswa
                </label>
                <input type="number"
                       name="kuota"
                       value="{{ old('kuota', $row->kuota) }}"
                       min="1"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2
                              focus:ring-2 focus:ring-slate-400 outline-none">

                <div class="text-xs text-slate-500 mt-1">
                    Terisi saat ini: <b>{{ $row->terisi }}</b>
                </div>

                @error('kuota')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-2 pt-4">
                <a href="{{ route('jadwal-bimbingan.index') }}"
                   class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-sm">
                    Batal
                </a>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-700 text-white text-sm">
                    Update Jadwal
                </button>
            </div>

        </form>

    </div>

</x-app-layout>