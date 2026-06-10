<x-app-layout>
<div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
        <h2 class="text-2xl font-semibold leading-tight mb-6">Edit Data Tugas Akhir</h2>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 border border-gray-200">
            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">NIM</label>
                    <p class="text-gray-900">{{ $data->nim }}</p>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Mahasiswa</label>
                    <p class="text-gray-900">{{ $data->nama_lengkap }}</p>
                </div>
            </div>

            <form action="{{ route('update-data-ta.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="judul">
                        Judul Tugas Akhir
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('judul') border-red-500 @enderror" 
                           id="judul" name="judul" type="text" value="{{ old('judul', $data->judul) }}">
                    @error('judul')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="abstrak">
                        Abstrak
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('abstrak') border-red-500 @enderror" 
                              id="abstrak" name="abstrak" rows="5">{{ old('abstrak', $data->abstrak) }}</textarea>
                    @error('abstrak')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="file_ta">
                        File Tugas Akhir (PDF)
                    </label>
                    @if($data->dokumen_ta)
                        <div class="mb-2">
                            <a href="{{ asset('storage/'.$data->dokumen_ta) }}" target="_blank" class="text-blue-500 hover:underline text-sm">
                                <i class="bi bi-file-earmark-pdf"></i> Lihat File Saat Ini
                            </a>
                        </div>
                    @else
                        <div class="mb-2 text-sm text-gray-500 italic">Belum ada file.</div>
                    @endif
                    
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('file_ta') border-red-500 @enderror" 
                           id="file_ta" name="file_ta" type="file" accept=".pdf">
                    <p class="text-gray-600 text-xs italic mt-1">Biarkan kosong jika tidak ingin mengubah file.</p>
                    @error('file_ta')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('update-data-ta.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
