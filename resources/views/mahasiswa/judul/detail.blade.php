<x-app-layout>
    @section('title', 'Detail Seminar Proposal')
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <table class="table table-striped table-hover" style="width: 100%">
                <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                    Detail Tugas Akhir
                    <hr class="mt-2 border border-l border-slate-900">
                </caption>
                <tr>
                    <th>Judul</th>
                    <td class="font-bold">:</td>
                    <td>{{ $tugasAkhir->judul }}</td>
                </tr>
                <tr>
                    <th>Studi Kasus</th>
                    <td class="font-bold">:</td>
                    <td>{{ $tugasAkhir->studi_kasus }}</td>
                </tr>
                <tr>
                    <th>Metode</th>
                    <td class="font-bold">:</td>
                    <td>{{ $tugasAkhir->metode }}</td>
                </tr>
                <tr>
                    <th style="width: 28%">Nama Penulis</th>
                    <td class="font-bold" style="width: 1%">:</td>
                    <td>{{ $tugasAkhir->mahasiswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td class="font-bold">:</td>
                    <td>{{ $tugasAkhir->mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing Akademik</th>
                    <td class="font-bold">:</td>
                    <td>{{ $tugasAkhir->dosen_PA->nama_dosen }}</td>
                </tr>
            </table>
        </div>
        <a href="/judul" type="button"
            class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
            Kembali
        </a>

    </x-main-card>
</x-app-layout>
