<x-app-layout>
    @section('title', 'Detail Seminar Hasil')
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <table class="table table-striped table-hover" style="width: 100%">
                <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                    Detail Seminar Hasil
                    <hr class="mt-2 border border-l border-slate-900">
                </caption>
                
                    @if ($semhas->file_lampiran)
                        @php
                            $lampiran = json_decode($semhas->file_lampiran);
                            for ($i = 1; $i < count($lampiran); $i++) {
                                $nilai[$i] = isset($lampiran[$i]) ? $lampiran[$i] . ', ' : null;
                            }
                        @endphp
                    @endif
                    <tr>
                        <th style="width: 28%">Nama</th>
                        <td class="font-bold" style="width: 1%">:</td>
                        <td>{{ $semhas->mahasiswa->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td class="font-bold">:</td>
                        <td>{{ $semhas->mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td class="font-bold">:</td>
                        <td>{{ $semhas->tugas_akhir->judul }}</td>
                    </tr>
                    <tr>
                        <th>Studi Kasus</th>
                        <td class="font-bold">:</td>
                        <td>{{ $semhas->tugas_akhir->studi_kasus }}</td>
                    </tr>
                    <tr>
                        <th>Metode</th>
                        <td class="font-bold">:</td>
                        <td>{{ $semhas->tugas_akhir->metode }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing Akademik</th>
                        <td class="font-bold">:</td>
                        <td>{{ $semhas->tugas_akhir->dosen_PA->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing 1</th>
                        <td class="font-bold">:</td>
                        <td>{{ $semhas->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing 2</th>
                        <td class="font-bold">:</td>
                        <td>{{ $semhas->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</td>
                    </tr>
                    <tr>
                        <th>Dosen Penguji 1</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($semhas->tugas_akhir->dosen_penguji_1_id === null)
                                -
                            @else
                                {{ $semhas->tugas_akhir->dosen_penguji_1->nama_dosen }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dosen Penguji 2</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($semhas->tugas_akhir->dosen_penguji_2_id === null)
                                -
                            @else
                                {{ $semhas->tugas_akhir->dosen_penguji_2->nama_dosen }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tempat Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($semhas->tempat_pelaksanaan === null)
                                -
                            @else
                                {{ $semhas->tempat_pelaksanaan }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($semhas->tanggal_pelaksanaan === null)
                                -
                            @else
                                {{ $semhas->tanggal_pelaksanaan }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Jam Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($semhas->jam_pelaksanaan === null)
                                -
                            @else
                                {{ $semhas->jam_pelaksanaan }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dokumen Seminar Hasil</th>
                        <td class="font-bold">:</td>
                        <td>
                            <x-new-modal :file="$semhas->dokumen_ta" />
                        </td>
                    </tr>
                    <tr>
                        <th>Surat ACC Jadwal Seminar</th>
                        <td class="font-bold">:</td>
                        <td>
                            <x-new-modal :file="$lampiran[1]" />
                        </td>
                    </tr>
                    <tr>
                        <th>File Lainnya</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($lampiran !== null)
                                @for ($i = 2; $i < count($lampiran); $i++)
                                    <x-new-modal :file="$lampiran[$i]" />
                                @endfor
                            @else
                                -
                            @endif
                        </td>
                    </tr>
        
            </table>
        </div>
        <a href="/mahasiswa/semhas" type="button"
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
