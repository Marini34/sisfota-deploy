<x-app-layout>
    @section('title', 'Detail Seminar Proposal')
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <table class="table table-striped table-hover" style="width: 100%">
                <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                    Detail Seminar Proposal
                    <hr class="mt-2 border border-l border-slate-900">
                    @if ($sempro->status_pendaftaran === 'Dibatalkan')
                        <span class="text-red-500 text-sm font-semibold mt-1">Seminar Proposal ini </span>
                        <span class="text-red-500 text-sm font-semibold mt-1">
                            {{ $sempro->alasan_dibatalkan ? ' ' . $sempro->alasan_dibatalkan : '' }}
                        </span>
                    @else

                    @endif
                </caption>

                    @if ($sempro->file_lampiran)
                        @php
                            $lampiran = json_decode($sempro->file_lampiran);
                            // $lampiranArray = (object) $lampiranArray;

                            $nilai1 = $lampiran[1] ?? null;
                            $nilai2 = $lampiran[2] ?? null;
                            $nilai3 = $lampiran[3] ?? null;
                            for ($i = 4; $i < count($lampiran); $i++) {
                                $nilai[$i] = isset($lampiran[$i]) ? $lampiran[$i] . ', ' : null;
                            }
                        @endphp
                    @endif
                    <tr>
                        <th style="width: 28%">Nama</th>
                        <td class="font-bold" style="width: 1%">:</td>
                        <td>{{ $sempro->mahasiswa->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td class="font-bold">:</td>
                        <td class="whitespace-pre-wrap">{{ $sempro->tugas_akhir->no_hp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td class="font-bold">:</td>
                        <td class="whitespace-pre-wrap">{{ $sempro->tugas_akhir->judul }}</td>
                    </tr>
                    <tr>
                        <th>Studi Kasus</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->studi_kasus }}</td>
                    </tr>
                    <tr>
                        <th>Metode</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->metode }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing Akademik</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->dosen_PA->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing 1</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing 2</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Penguji 1</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->tugas_akhir->dosen_penguji_1_id === null)
                                -
                            @else
                                {{ $sempro->tugas_akhir->dosen_penguji_1->nama_dosen }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dosen Penguji 2</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->tugas_akhir->dosen_penguji_2_id === null)
                                -
                            @else
                                {{ $sempro->tugas_akhir->dosen_penguji_2->nama_dosen }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tempat Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->tempat_pelaksanaan === null)
                                -
                            @else
                                {{ $sempro->tempat_pelaksanaan }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->tanggal_pelaksanaan === null)
                                -
                            @else
                                {{ $sempro->tanggal_pelaksanaan }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Jam Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->jam_pelaksanaan === null)
                                -
                            @else
                                {{ $sempro->jam_pelaksanaan }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dokumen Proposal</th>
                        <td class="font-bold">:</td>
                        <td>
                            <x-new-modal :file="$sempro->dokumen_ta" />
                        </td>
                    </tr>
                    <tr>
                        <th>Bukti menghadiri seminar proposal</th>
                        <td class="font-bold">:</td>
                        <td><x-new-modal :file="$nilai1" /></td>
                    </tr>
                    <tr>
                        <th>Bukti telah menjadi notulensi</th>
                        <td class="font-bold">:</td>
                        <td><x-new-modal :file="$nilai2" /></td>
                    </tr>
                    <tr>
                        <th>LIRS semester berjalan</th>
                        <td class="font-bold">:</td>
                        <td><x-new-modal :file="$nilai3" />
                        </td>
                    </tr>
                    <tr>
                        <th>File Lainnya</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($lampiran !== null)
                                @for ($i = 4; $i < count($lampiran); $i++)
                                    <x-new-modal :file="$lampiran[$i]" />
                                @endfor
                            @else
                                -
                            @endif
                        </td>
                    </tr>

            </table>
        </div>
        <a href="/mahasiswa/sempro" type="button"
            class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
            Kembali
        </a>

    </x-main-card>
    @section('script')
        <script>
            function show(file) {
                var modal = document.getElementById('lampiran_' + file);
                modal.showModal();
            }

            function closeLampiran(file) {
                var modal = document.getElementById('lampiran_' + file);
                modal.close();
            }
        </script>
    @endsection
</x-app-layout>
