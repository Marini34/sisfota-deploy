<x-app-layout>
    @section('title', 'Detail Informasi Mahasiswa')
    <x-main-card>
        <table class="table table-striped table-hover table-auto" style="width: 100%">
            <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                Detail Informasi Mahasiswa
                <hr class="mt-2 border border-l border-slate-900">
            </caption>
            <tr>
                <th style="width: 28%">Nama</th>
                <td class="font-bold" style="width: 1%">:</td>
                <td>{{ $mahasiswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <th style="width: 28%">NIM</th>
                <td class="font-bold" style="width: 1%">:</td>
                <td>{{ $mahasiswa->nim }}</td>
            </tr>
            <tr>
                <th style="width: 28%">Judul Tugas Akhir</th>
                <td class="font-bold" style="width: 1%">:</td>
                <td>{{ $seminarSidang->tugas_akhir->judul }}</td>
            </tr>
            <tr>
                <th style="width: 28%">Pembimbing Akademik</th>
                <td class="font-bold" style="width: 1%">:</td>
                <td>{{ $seminarSidang->tugas_akhir->dosen_PA->nama_dosen }}</td>
            </tr>
            <tr>
                <th style="width: 28%">Pembimbing 1</th>
                <td class="font-bold" style="width: 1%">:</td>
                <td>{{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</td>
            </tr>
            <tr>
                <th style="width: 28%">Pembimbing 2</th>
                <td class="font-bold" style="width: 1%">:</td>
                <td>{{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</td>
            </tr>
            @if ($proposal)
                <tr>
                    <th style="width: 28%">Tanggal Seminar Proposal</th>
                    <td class="font-bold" style="width: 1%">:</td>
                    <td>{{\Carbon\Carbon::parse($proposal->tanggal_pelaksanaan)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th style="width: 28%">Tahapan Tugas Akhir</th>
                    <td class="font-bold" style="width: 1%">:</td>
                    <td>{{ $seminarSidang->tahapan_ta}}
                        @if ($seminarSidang->status_pendaftaran == 'Menunggu Verifikasi')
                            (Mendaftar)
                        @elseif ($seminarSidang->status_kelulusan != 'Menunggu Keputusan' )
                            ({{$seminarSidang->status_kelulusan}})
                        @endif
                    
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%">Waktu pengerjaan ditempuh</th>
                    <td class="font-bold" style="width: 1%">:</td>
                    <td>{{$duration}}</td>
                </tr>
            @endif
        </table>
    </x-main-card>
</x-app-layout>