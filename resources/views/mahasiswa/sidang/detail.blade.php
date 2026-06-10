<x-app-layout>
    @section('title', 'Detail Sidang Akhir')
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <table class="table table-striped table-hover" style="width: 100%">
                <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                    Detail Sidang Akhir
                    <hr class="mt-2 border border-l border-slate-900">
                </caption>
                @php
                    $lampiranSidang = json_decode($sidang->file_lampiran ?? '[]', true);

                    $artikel = $lampiranSidang[0] ?? null;
                    $syaratSidang = $lampiranSidang[1] ?? null;
                    $accJadwalSidang = $lampiranSidang[2] ?? null;
                @endphp
                @php
                    \Carbon\Carbon::setLocale('id');

                    $hari = \Carbon\Carbon::parse($sidang->tanggal_pelaksanaan)->translatedFormat('l');
                    $tanggal_pelaksanaan = \Carbon\Carbon::parse($sidang->tanggal_pelaksanaan)->translatedFormat(
                        'd F Y',
                    );
                    $jam_pelaksanaan = \Carbon\Carbon::parse($sidang->jam_pelaksanaan)->format('H:i');
                @endphp

                <tr>
                    <th style="width: 28%">Nama</th>
                    <td class="font-bold" style="width: 1%">:</td>
                    <td>{{ $sidang->mahasiswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sidang->mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <th>Judul</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sidang->tugas_akhir->judul }}</td>
                </tr>
                <tr>
                    <th>Studi Kasus</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sidang->tugas_akhir->studi_kasus }}</td>
                </tr>
                <tr>
                    <th>Metode</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sidang->tugas_akhir->metode }}</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing Akademik</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sidang->tugas_akhir->dosen_PA->nama_dosen }}</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing 1</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing 2</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</td>
                </tr>
                <tr>
                    <th>Dosen Penguji 1</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($sidang->tugas_akhir->dosen_penguji_1_id === null)
                            -
                        @else
                            {{ $sidang->tugas_akhir->dosen_penguji_1->nama_dosen }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Dosen Penguji 2</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($sidang->tugas_akhir->dosen_penguji_2_id === null)
                            -
                        @else
                            {{ $sidang->tugas_akhir->dosen_penguji_2->nama_dosen }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Tempat Pelaksanaan</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($sidang->tempat_pelaksanaan === null)
                            -
                        @else
                            {{ $sidang->tempat_pelaksanaan }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Pelaksanaan</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($sidang->tanggal_pelaksanaan === null)
                            -
                        @else
                            {{ $hari }}, {{ $tanggal_pelaksanaan }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Jam Pelaksanaan</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($sidang->jam_pelaksanaan === null)
                            -
                        @else
                            {{ $jam_pelaksanaan }} WIB
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Dokumen Sidang Akhir</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>@php $dokumenSidang = $sidang ? $sidang->dokumen_ta : null; @endphp
                        <x-new-modal :file="$dokumenSidang" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Dokumen Artikel Jurnal</th>
                    <td class="font-medium">:</td>
                    <td><x-new-modal :file="$artikel" /></td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Lembar Pemeriksaan Dokumen Persyaratan Sidang</th>
                    <td class="font-medium">:</td>
                    <td><x-new-modal :file="$syaratSidang" /></td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Surat ACC Jadwal Sidang</th>
                    <td class="font-medium">:</td>
                    <td><x-new-modal :file="$accJadwalSidang" /></td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">File Lainnya</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        @if (isset($lampiranSidang[3]))
                            @for ($i = 3; $i < count($lampiranSidang); $i++)
                                <x-new-modal :file="'sidang/lampiran/' . $lampiranSidang[$i]" />
                            @endfor
                        @else
                            -
                        @endif
                    </td>
                </tr>

            </table>
            <table class="table table-striped table-hover table-auto" style="width: 100%">
                <caption class="caption-top font-bold text-black">
                    File Tugas Akhir
                </caption>
                @php
                    $fileTA = $sidang ? json_decode($sidang->file_notulensi) : null;
                    $artikel = $fileTA[1] ?? null;
                    $skpl = $fileTA[2] ?? null;
                    $kode = $fileTA[3] ?? null;
                    $SKPembimbing = $fileTA[4] ?? null;
                    $SKPengujiSempro = $fileTA[5] ?? null;
                    $SKPengujiSemhas = $fileTA[6] ?? null;
                    $lembarPengesahan = $fileTA[7] ?? null;
                    $undanganSemhas = $fileTA[8] ?? null;
                    $undanganSidang = $fileTA[9] ?? null;
                @endphp
                <tr>
                    <th style="width: 28%" class="font-medium">Artikel Jurnal</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$artikel" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">SKPL, DPPL, PDHUPL</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$skpl" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Source Code Program</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <a href="{{ asset('storage/' . $kode) }}"><u class="text-blue-500">{{ $kode }}</u></a>
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">SK Pembimbing</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$SKPembimbing" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">SK Penguji Seminar Proposal</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$SKPengujiSempro" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">SK Penguji Seminar Hasil</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$SKPengujiSemhas" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Lembar Pengesahan Skripsi</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$lembarPengesahan" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Undangan Seminar Hasil</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$undanganSemhas" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Undangan Sidang Tugas Akhir</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$undanganSidang" />
                    </td>
                </tr>
            </table>
        </div>
        <a href="/mahasiswa/sidang" type="button"
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
