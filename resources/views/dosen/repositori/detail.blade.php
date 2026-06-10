<x-app-layout>
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <h1 class="font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                Repositori File Tugas Akhir {{ $tugasAkhir->mahasiswa->nama_lengkap }}
            </h1>
            <hr class="mt-2 border border-l border-slate-900">
            <table class="table table-striped table-hover table-auto" style="width: 100%">
                <caption class="caption-top font-bold text-black">
                    File Seminar Proposal
                </caption>
                @php
                    $lampiran = $sempro ? json_decode($sempro->file_lampiran) : null;
                    // $lampiranArray = (object) $lampiranArray;

                    $nilai1 = $lampiran[1] ?? null;
                    $nilai2 = $lampiran[2] ?? null;
                    $nilai3 = $lampiran[3] ?? null;
                @endphp
                <tr>
                    <th style="width: 28%" class="font-medium">Dokumen Proposal</th>
                    <td class="font-medium" style="width: 1%">:</td>
                    <td class="text-left">
                        @if ($sempro->dokumen_ta)
                            <x-new-modal :file="$sempro->dokumen_ta" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Revisi Proposal</th>
                    <td class="font-medium" style="width: 1%">:</td>
                    <td>
                        @if ($sempro->file_revisi)
                            <x-new-modal :file="$sempro->file_revisi" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Notulensi Seminar Proposal</th>
                    <td class="font-medium" style="width: 1%">:</td>
                    <td>
                        @if ($sempro->file_notulensi)
                            <x-new-modal :file="json_decode($sempro->file_notulensi)" />
                        @endif
                    </td>
                </tr>
                @if ($sempro && ($sempro->status_kelulusan == 'LULUS' || $sempro->status_kelulusan == 'TIDAK LULUS'))
                    <tr>
                        <th style="width: 28%" class="font-medium">Berita Acara Seminar Proposal</th>
                        <td class="font-medium" style="width: 1%">:</td>
                        <td>
                            <a href="{{ route('dosen.cetak', $sempro->slug) }}" target="_blank"
                                class="text-blue-600 underline">
                                Berita Acara Seminar Proposal.pdf
                            </a>
                        </td>
                    </tr>
                    <tr>
                    @else
                @endif
                <th class="font-medium">Bukti menghadiri seminar proposal</th>
                <td class="font-medium">:</td>
                <td>
                    <x-new-modal :file="$nilai1" />
                </td>
                </tr>
                <tr>
                    <th class="font-medium">Bukti telah menjadi notulensi</th>
                    <td class="font-medium">:</td>
                    <td><x-new-modal :file="$nilai2" /></td>
                </tr>
                <tr>
                    <th class="font-medium">LIRS semester berjalan</th>
                    <td class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$nilai3" />
                    </td>
                </tr>
                <tr>
                    <th class="font-medium">File Lainnya</th>
                    <td class="font-medium">:</td>
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
            <table class="table table-striped table-hover table-auto" style="width: 100%">
                <caption class="caption-top font-bold text-black">
                    File Seminar Hasil
                </caption>
                @php
                    $lampiranSemhas = $semhas ? json_decode($semhas->file_lampiran) : null;
                @endphp
                <tr>
                    <th style="width: 28%" class="font-medium">Dokumen Sidang</th>
                    <td class="font-medium" style="width: 1%">:</td>
                    <td>
                        @php $dokumenSemhas = $semhas ? $semhas->dokumen_ta : null; @endphp
                        <x-new-modal :file="$dokumenSemhas" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Notensi Seminar Hasil</th>
                    <td class="font-medium" style="width: 1%">:</td>
                    <td>@php $notulensiSemhas = $semhas ? json_decode($semhas->file_notulensi) : null; @endphp
                        <x-new-modal :file="$notulensiSemhas" />
                    </td>
                </tr>
                @if ($semhas && ($semhas->status_kelulusan == 'LULUS' || $semhas->status_kelulusan == 'TIDAK LULUS'))
                    <tr>
                        <th style="width: 28%" class="font-medium">Berita Acara Seminar Hasil</th>
                        <td class="font-medium" style="width: 1%">:</td>
                        <td>
                            <a href="{{ route('dosen.cetak', $semhas->slug) }}" class="text-blue-600 underline"
                                target="_blank">
                                Berita Acara Seminar Hasil.pdf
                            </a>
                        </td>
                    </tr>
                    <tr>
                @endif
                <tr>
                    <th style="width: 28%" class="font-medium">Surat ACC Jadwal Semhas</th>
                    <td class="font-medium" style="width: 1%">:</td>
                    <td>
                        @if ($lampiranSemhas !== null)
                            <x-new-modal :file="$lampiranSemhas[1]" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">File Lainnya</th>
                    <td class="font-medium" style="width: 1%">:</td>
                    <td>
                        @if ($lampiranSemhas !== null)
                            @for ($i = 2; $i < count($lampiranSemhas); $i++)
                                <x-new-modal :file="$lampiranSemhas[$i]" />
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
                    $dokTA = $fileTA[1] ?? null;
                    $artikel = $fileTA[2] ?? null;
                    $skpl = $fileTA[3] ?? null;
                    $kode = $fileTA[4] ?? null;
                @endphp
                <tr>
                    <th style="width: 28%" class="font-medium">Dokumen inti Tugas Akhir</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$dokTA" />
                    </td>
                </tr>
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
                        <x-new-modal :file="$kode" />
                    </td>
                </tr>
            </table>
            <table class="table table-striped table-hover table-auto" style="width: 100%">
                <caption class="caption-top font-bold text-black">
                    File Sidang Akhir
                </caption>
                @php
                    $lampiranSidang = $sidang ? json_decode($sidang->file_lampiran) : null;
                    $accJadwalSidang = $lampiranSidang[1] ?? null;
                    $transkripAkademik = $lampiranSidang[2] ?? null;
                    $bebasLabMipa = $lampiranSidang[3] ?? null;
                    $bebasLabNonMipa = $lampiranSidang[4] ?? null;
                    $bebasPerpusMipa = $lampiranSidang[5] ?? null;
                    $bebasPerpusUntan = $lampiranSidang[6] ?? null;
                    $bebasPerpusWilayah = $lampiranSidang[7] ?? null;
                    $SKPembimbing = $lampiranSidang[8] ?? null;
                    $SKPenguji = $lampiranSidang[9] ?? null;
                    $tutep = $lampiranSidang[10] ?? null;
                    $suratSidang = $lampiranSidang[11] ?? null;
                @endphp
                @if ($sidang)
                    {{-- @if ($sidang->status_kelulusan == 'LULUS' || $sidang->status_kelulusan == 'TIDAK LULUS') --}}
                    <tr>
                        <th style="width: 28%" class="font-medium">Berita Acara Sidang Sarjana</th>
                        <td class="font-medium" style="width: 1%">:</td>
                        <td>
                            <a href="{{ route('dosen.cetak', $sidang->slug) }}" target="_blank"
                                class="text-blue-600 underline">
                                Berita Acara Sidang Sarjana.pdf
                            </a>
                        </td>
                    </tr>
                    {{-- @endif --}}
                @endif
                <tr>
                    <th style="width: 28%" class="font-medium">Dokumen Sidang Akhir</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>@php $dokumenSidang = $sidang ? $sidang->dokumen_ta : null; @endphp
                        <x-new-modal :file="$dokumenSidang" />
                    </td>
                </tr>

                <tr>
                    <th style="width: 28%" class="font-medium">Transkrip Akademik</th>
                    <td class="font-medium">:</td>
                    <td><x-new-modal :file="$transkripAkademik" /></td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Surat ACC Jadwal Sidang</th>
                    <td class="font-medium">:</td>
                    <td><x-new-modal :file="$accJadwalSidang" /></td>
                </tr>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Bebas Lab FMIPA</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td><x-new-modal :file="$bebasLabMipa" /></td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Bebas Lab di Luar FMIPA</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td><x-new-modal :file="$bebasLabNonMipa" /></td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Bebas Perpustakaan FMIPA</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$bebasPerpusMipa" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Bebas Perpustakaan Untan</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$bebasPerpusUntan" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Bebas Perpustakaan Wilayah</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$bebasPerpusWilayah" />
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
                    <th style="width: 28%" class="font-medium">SK Penguji</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$SKPenguji" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Sertifikat TUTEP</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$tutep" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Surat Sidang</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$suratSidang" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">File Lainnya</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        @if ($lampiranSidang !== null)
                            @for ($i = 12; $i < count($lampiranSidang); $i++)
                                <x-new-modal :file="$lampiranSidang[$i]" />
                            @endfor
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>
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
