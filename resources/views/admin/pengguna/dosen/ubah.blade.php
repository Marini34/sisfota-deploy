<x-app-layout>
    @section('title', 'Edit Pengguna Dosen')
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
        <form action="{{ route('admin.kelola.dosen.edit.store', $dosen->id) }}" method="post" class="p-4 md:p-5">
            @csrf
            <label for="nama">Nama Dosen</label>
            <div class="my-2">
                <input type="text" name="nama" id="nama" value="{{ old('nama', $dosen->nama_dosen) }}"
                    class="w-full rounded-md border-slate-300" required></input>
            </div>
            @error('nama')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror

            <label for="nip">NIP/NIDK</label>
            <div class="my-2">
                <input type="text" name="nip" id="nip" value="{{ old('nip', $dosen->nip_nidk) }}"
                    class="w-full rounded-md border-slate-300" required>
            </div>
            @error('nip')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror

            <label for="status">Status Keaktifan</label>
            <div class="my-2">
                <select name="status" id="status" class="w-full rounded-md border-slate-300" required>
                    <option value="Aktif" @if ($dosen->user && (old('status', $dosen->user->status) == 'Aktif')) selected @endif>Aktif</option>
                    <option value="Tidak Aktif" @if ($dosen->user && (old('status', $dosen->user->status) == 'Tidak Aktif')) selected @endif>Tidak Aktif</option>
                </select>
            </div>
            @error('status')
                <div>
                    <p class="text-red-500 text-base">{{ $message }}</p>
                </div>
            @enderror

            <label for="email">Email</label>
            <div class="my-2">
                <input type="email" name="email" id="email" @if ($dosen->user) value="{{ old('email', $dosen->user->email) }}" @endif
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
                <a href="/admin/kelola-dosen" type="button"
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
