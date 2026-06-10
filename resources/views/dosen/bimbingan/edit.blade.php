<x-app-layout>
    @section('title', 'Edit Kontrol Bimbingan Mahasiswa')
    <x-main-card>
        <div>
            <h1 class="font-bold text-2xl mb-3">Edit Rekam Bimbingan Tugas Akhir {{$rekamBimbingan->mahasiswa->nama_lengkap}}</h1>
            <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('dashboard') }}"
            class="mb-4 inline-flex items-center gap-2 text-white bg-gray-500 hover:bg-gray-600 
            rounded-lg text-sm px-4 py-2">
            
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
        </div>
        
        <form action="{{ route('dosen.daftar.bimbingan.edit.store', $rekamBimbingan->slug) }}" method="post" class="p-4 md:p-5">
            @csrf
            <label for="status">Status Rekam Bimbingan</label>
            <div class="my-2">
                <select name="status" id="status" class="w-full rounded-md border-slate-300" required>
                    <option value="" disabled selected hidden>Status Rekam Bimbingan</option>
                    <option value="Diterima" @if (old('status', $rekamBimbingan->status_rekam_bimbingan ) == 'Diterima') selected @endif>Terima</option>
                    <option value="Ditolak" @if (old('status', $rekamBimbingan->status_rekam_bimbingan ) == 'Ditolak') selected @endif>Tolak</option>
                </select>
                @error('status')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>
            <label for="tanggal">Tanggal Bimbingan</label>
            <div class="my-2">
                <input type="date" name="tanggal" id="tanggal" class="w-full rounded-md border-slate-300"
                    value="{{ $rekamBimbingan->tanggal_bimbingan }}" required>
                @error('tanggal')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>
    
            <label for="kemajuan">Uraian Kemajuan Tugas Akhir</label>
            <div class="my-2">
                <textarea name="kemajuan" id="kemajuan" class="w-full rounded-md border-slate-300" rows="5" required>{{ $rekamBimbingan->uraian_kemajuan_ta }}</textarea>
                @error('kemajuan')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>
    
            <label for="pengerjaanSelanjutnya">Rencana Pengerjaan Selanjutnya</label>
            <div class="my-2">
                <textarea name="pengerjaanSelanjutnya" id="pengerjaanSelanjutnya" rows="5" class="w-full rounded-md border-slate-300" required>{{ $rekamBimbingan->pengerjaan_selanjutnya }}</textarea>
                @error('pengerjaanSelanjutnya')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>
    
            <br>
            <div class="grid grid-cols-2 gap-10">
                <a href="/dosen/daftar-bimbingan/{{ $rekamBimbingan->mahasiswa->slug }}" type="button"
                    class="cursor-pointer text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                    Kembali
                </a>
                <button type="submit"
                    class="cursor-pointer text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                    Edit
                </button>
            </div>
        </form>
    </x-main-card>
</x-app-layout>
