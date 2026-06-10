<x-app-layout>
    @section('title', 'Edit Pengguna Mahasiswa')
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
        <form action="{{ route('admin.kelola.mahasiswa.edit.store', $mahasiswa->id) }}" method="post" class="p-4 md:p-5">
            @csrf
            <label for="nama">Nama Mahasiswa</label>
            <div class="my-2">
                <input type="text" name="nama" id="nama" value="{{ old('nama', $mahasiswa->nama_lengkap) }}"
                    class="w-full rounded-md border-slate-300" required></input>
            </div>
            @error('nama')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror

            <label for="nim">NIM</label>
            <div class="my-2">
                <input type="text" name="nim" id="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                    class="w-full rounded-md border-slate-300" required>
            </div>
            @error('nim')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror

            <label for="status">Status Keaktifan</label>
            <div class="my-2">
                <select name="status" id="status" class="w-full rounded-md border-slate-300" required>
                    <option value="Aktif" @if (old('status', $mahasiswa->user->status) == 'Aktif') selected @endif>Aktif</option>
                    <option value="Tidak Aktif" @if (old('status', $mahasiswa->user->status) == 'Tidak Aktif') selected @endif>Tidak Aktif</option>
                </select>
            </div>
            @error('jenis_kelamin')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror
            
            <label for="jenis_kelamin">Jenis Kelamin</label>
            <div class="my-2">
                <select name="jenis_kelamin" id="jenis_kelamin" class="w-full rounded-md border-slate-300" required>
                    <option value="Laki-laki" @if (old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Laki-laki') selected @endif>Laki-laki</option>
                    <option value="Perempuan" @if (old('jenis_kelamin', $mahasiswa->jenis_kelamin) === 'Perempuan') selected @endif>Perempuan</option>
                </select>
            </div>
            @error('jenis_kelamin')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror

            <label for="alamat">Alamat Mahasiswa</label>
            <div class="my-2">
                <textarea name="alamat" id="alamat" class="w-full rounded-md border-slate-300">@if ($mahasiswa->alamat){{ $mahasiswa->alamat }}@endif
                </textarea>
            </div>

            <label for="email">Email</label>
            <div class="my-2">
                <input type="email" name="email" id="email" value="{{ old('email', $mahasiswa->user->email) }}"
                    class="w-full rounded-md border-slate-300" required>
            </div>
            @error('email')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror

            <label for="password">Password</label>
            <div class="my-2">
                <input type="text" name="password" id="password" class="w-full rounded-md border-slate-300">
            </div>
            @error('password')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror

            <br>
            <div class="grid grid-cols-2 gap-10 mt-2">
                <a href="/admin/kelola-mahasiswa" type="button"
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
