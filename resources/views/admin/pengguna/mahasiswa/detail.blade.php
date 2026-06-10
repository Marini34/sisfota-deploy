<x-app-layout>
    @section('title', 'Detail Pengguna Mahasiswa')
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <table class="table table-striped table-hover" style="width: 100%">
                <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                    Detail Mahasiswa
                    <hr class="mt-2 border border-l border-slate-900">
                </caption>
                    <tr>
                        <th style="width: 28%">Nama</th>
                        <td class="font-bold" style="width: 1%">:</td>
                        <td>{{ $mahasiswa->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td class="font-bold">:</td>
                        <td>{{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td class="font-bold">:</td>
                        <td class="whitespace-pre-wrap">{{ $mahasiswa->user->email }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td class="font-bold">:</td>
                        <td>{{ $mahasiswa->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td class="font-bold">:</td>
                        <td>{{ $mahasiswa->alamat }}</td>
                    </tr>
            </table>
        </div>
        <a href="/admin/kelola-mahasiswa" type="button"
            class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
            Kembali
        </a>

    </x-main-card>
</x-app-layout>
