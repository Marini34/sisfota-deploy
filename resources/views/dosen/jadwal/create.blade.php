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

    <form action="{{ route('jadwal-bimbingan.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Tanggal --}}
        <div>
            <label class="block text-sm font-medium mb-1">
                Tanggal Bimbingan
            </label>
            <input type="date"
                   name="tanggal"
                   value="{{ old('tanggal') }}"
                   min="{{ date('Y-m-d') }}"
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
                       value="{{ old('jam_mulai') }}"
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
                       value="{{ old('jam_selesai') }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2
                              focus:ring-2 focus:ring-slate-400 outline-none">

                @error('jam_selesai')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

        </div>

        {{-- Kuota --}}
        <div>
            <label class="block text-sm font-medium mb-1">
                Kuota Mahasiswa
            </label>
            <input type="number"
                   name="kuota"
                   value="{{ old('kuota', 1) }}"
                   min="1"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2
                          focus:ring-2 focus:ring-slate-400 outline-none">

            @error('kuota')
                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- lokasi --}}
       <div>
            <label class="block text-sm font-medium mb-1">
                Lokasi / Platform
            </label>
            <input type="text"
                name="lokasi"
                value="{{ old('lokasi') }}"
                placeholder="Contoh: Ruang 203 / Zoom"
                class="w-full rounded-lg border border-slate-300 px-3 py-2
                        focus:ring-2 focus:ring-slate-400 outline-none">

            @error('lokasi')
                <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-2 pt-4">
            <a href="{{ route('jadwal-bimbingan.index') }}"
               class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 text-sm">
                Batal
            </a>

            <button type="submit"
                    class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-700 text-white text-sm">
                Simpan Jadwal
            </button>
        </div>

    </form>

</div>

</x-app-layout>