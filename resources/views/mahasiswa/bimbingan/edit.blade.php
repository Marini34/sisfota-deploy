<x-app-layout>
    @section('title', 'Edit Bimbingan')

    <x-main-card>
        <div>
            <h1 class="font-bold text-2xl mb-3">Edit Rekam Bimbingan Tugas Akhir</h1>
        </div>
        <form action="{{ route('view.bimbingan.edit.store', $rekamBimbingan->slug) }}" method="post"
            enctype="multipart/form-data" class="p-4 md:p-5">
            @csrf
            <label for="pembimbing">Dosen Pembimbing</label>
            <div class="my-2">
                <select name="pembimbing" id="pembimbing" class="w-full rounded-md border-slate-300" @readonly(true)>
                    <option value="" disabled selected hidden>Pilih Pembimbing</option>
                    <option value="{{ $rekamBimbingan->dosen_pembimbing_1_id }}"
                        @if (old('pembimbing', $rekamBimbingan->dosen_pembimbing_1_id) == $rekamBimbingan->pembimbing_id) selected @endif>
                        {{ $rekamBimbingan->dosen_pembimbing_1->nama_dosen }}</option>
                    <option value="{{ $rekamBimbingan->dosen_pembimbing_2_id }}"
                        @if (old('pembimbing', $rekamBimbingan->dosen_pembimbing_2_id) == $rekamBimbingan->pembimbing_id) selected @endif>
                        {{ $rekamBimbingan->dosen_pembimbing_2->nama_dosen }}</option>
                </select>
                @error('pembimbing')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <label for="tanggal">Tanggal Bimbingan</label>
            <div class="my-2">
                <input type="date" name="tanggal" id="tanggal" class="w-full rounded-md border-slate-300"
                    value="{{ $rekamBimbingan->tanggal_bimbingan }}" readonly>
                @error('tanggal')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <label for="kemajuan">Uraian Kemajuan Tugas Akhir</label>
            <div class="my-2">
                <textarea name="kemajuan" id="kemajuan" class="w-full rounded-md border-slate-300" required>{{ $rekamBimbingan->uraian_kemajuan_ta }}</textarea>
                @error('kemajuan')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <label for="pengerjaanSelanjutnya">Rencana Pengerjaan Selanjutnya</label>
            <div class="my-2">
                <textarea name="pengerjaanSelanjutnya" id="pengerjaanSelanjutnya" class="w-full rounded-md border-slate-300" required>{{ $rekamBimbingan->pengerjaan_selanjutnya }}</textarea>
                @error('pengerjaanSelanjutnya')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <label for="persentase_mhs">Persentase Progress %</label>
            <div class="my-2">
                <input type="number" name="persentase_mhs" id="persentase_mhs" min="0" max="100"
                    class="w-full rounded-md border-slate-300" value="{{ $rekamBimbingan->persentase_mhs }}" required>
                @error('persentase_mhs')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>


            <label for="komentar_dosen">Komentar Dosen</label>
            <div class="my-2">
                <textarea name="komentar_dosen" id="komentar_dosen" class="w-full rounded-md border-slate-300" readonly>{{ $rekamBimbingan->pengerjaan_selanjutnya }}</textarea>
                @error('komentar_dosen')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>

            <label for="dokumen_bimbingan_mhs">Dokumen Bimbingan</label>
            <div class="my-2">
                <input type="file" name="dokumen_bimbingan_mhs" id="dokumen_bimbingan_mhs"
                    class="w-full rounded-md border-slate-300">
                @error('dokumen_bimbingan_mhs')
                    <div>
                        <p class="text-red-500 text-base">{{ $message }}</p>
                    </div>
                @enderror
            </div>
            @if ($rekamBimbingan->dokumen_bimbingan_mhs)
                <div class="mb-2 text-sm">
                    Dokumen saat ini :
                    <a href="{{ Storage::url($rekamBimbingan->dokumen_bimbingan_mhs) }}" target="_blank"
                        class="text-blue-600 underline">
                        Lihat Dokumen
                    </a>
                </div>
            @endif

            <br>
            <div class="grid grid-cols-2 gap-10">
                <a href="/mahasiswa/bimbingan" type="button"
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
