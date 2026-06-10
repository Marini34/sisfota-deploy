<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>
        BA_Semhas_{{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}_{{ ucwords(strtolower($seminarSidang->mahasiswa->nim)) }}
    </title>

    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
        }

        table {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .align-top {
            vertical-align: top;
        }

        .text-justify {
            text-align: justify;
        }

        .leading-5 {
            line-height: 1.2rem;
        }

        .content {
            padding: 0 0.75cm 0 0.75cm;
        }

        .pb-2 {
            padding-bottom: 0.5rem;
        }

        .default-border {
            border: 0.5px solid black;
        }

        .py-3 {
            padding-top: 0.8rem;
            padding-bottom: 0.8rem;
        }

        .parameter-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .parameter-list li {
            /* margin: 5px 0; */
            position: relative;
            padding-left: 12px;
        }

        .parameter-list li:before {
            content: "-";
            position: absolute;
            left: 2;
            top: 0;
        }
    </style>
</head>

<body style="font-family: 'Times New Roman', Times, serif">
    <div class="content">
        <table style="">
            <tbody>
                <tr>
                    <td style="vertical-align: top;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/Universitas-Tanjungpura-Pontianak-bw.png'))) }}"
                            width="130px" style="min-width: 130px; height:auto;">
                    </td>
                    <td class="text-center leading-5" style="font-size: 14pt;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,
                        <br>DAN TEKNOLOGI
                        <br>
                        <b style="font-size: 12pt" class="font-bold">UNIVERSITAS TANJUNGPURA
                            <br>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM
                            <br>JURUSAN SISTEM INFORMASI
                        </b>
                        <br><span style="font-size: 10pt;">Jalan Prof. Dr. H. Hadari Nawawi, Pontianak 78124
                            <br>Telepon/Fax (0561) 577963, e-mail : <span
                                style="color: blue;"><u>sisfo@untan.ac.id</u></span></span>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-center"
                        style="font-size: 14pt; line-height: 1.25rem; padding-top:1.25rem; padding-bottom:1.25rem; border-top: 4px solid black;">
                        <b>BERITA ACARA<br>SEMINAR HASIL TUGAS AKHIR
                    </td>
                </tr>

                <tr>
                    <td class="pt-4 pb-2">
                        Berdasarkan hasil Seminar Hasil Tugas Akhir yang telah dilaksanakan oleh:
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 10%" class="pb-2">
                        Nama
                    </td>
                    <td class=" pb-2" style="width: 2%">
                        :
                    </td>
                    <td class="pb-2">
                        {{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 10%" class="pb-2">
                        NIM
                    </td>
                    <td class=" pb-2" style="width: 2%">
                        :
                    </td>
                    <td class=" pb-2">
                        {{ $seminarSidang->mahasiswa->nim }}
                    </td>
                </tr>
                <tr>
                    <td class="align-top" style="width: 10%;">
                        Judul
                    </td>
                    <td class=" align-top" style="width: 2%">
                        :
                    </td>
                    <td class=" align-top text-justify">
                        {{ $seminarSidang->tugas_akhir->judul }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td colspan="3" class="text-justify pb-2">
                        yang dilaksanakan pada hari
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('l') }}, tanggal
                        <b>{{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}</b>
                        pada pukul <b>{{ Carbon\Carbon::parse($seminarSidang->jam_pelaksanaan)->format('H:i') }} s.d.
                            {{ Carbon\Carbon::parse($seminarSidang->jam_pelaksanaan)->addHours(2)->format('H:i') }}</b>
                        WIB secara tatap muka
                        pada FMIPA Universitas Tanjungpura.
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="pb-2">
                        Nilai Seminar Hasil Penelitian Skripsi adalah bernilai {{ $seminarSidang->total_nilai_akhir }}.
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="pb-2">
                        Dinyatakan bahwa, Tugas Akhir tersebut di atas
                        {{ $seminarSidang->total_nilai_akhir > $passingGrade ? 'diterima' : 'ditolak' }} *)
                        <br>
                        Dengan catatan : Sesuai notulen seminar hasil
                    </td>
                </tr>
                <tr>
                    <td colspan="2">

                    </td>
                    <td style="padding-top:20px; width:255px;">
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td class="align-top " style=" height: 70px">
                        Dosen Pembimbing I
                    </td>
                    <td class="align-top ">
                        Dosen Pembimbing II
                    </td>
                </tr>
                <tr>
                    <td class="align-top  leading-5" style="width: 60%; height: 60px">
                        <span><u>{{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</u></span>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk }}</span>
                        @endif
                    </td>
                    <td class="align-top  leading-5" style=" height: 50px">
                        <span><u>{{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</u></span>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_pembimbing_2->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nip_nidk }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="align-top " style="height: 70px">
                        Dosen Penguji I
                    </td>
                    <td class="align-top ">
                        Dosen Penguji II
                    </td>
                </tr>
                <tr>
                    <td class="align-top  leading-5" style=" height: 50px">
                        <span><u>{{ $seminarSidang->tugas_akhir->dosen_penguji_1->nama_dosen }}</u></span>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk }}</span>
                        @endif
                    </td>
                    <td class="align-top  leading-5" style=" height: 50px">
                        <span><u>{{ $seminarSidang->tugas_akhir->dosen_penguji_2->nama_dosen }}</u></span>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_penguji_2->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_penguji_2->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_penguji_2->nip_nidk }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center align-top leading-5 " style="height: 80px; padding-top:20px;">
                        Mengetahui<br>Ketua Jurusan Sistem Informasi
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center align-top  leading-5" style="height: 50px;">
                        <u>{{ $kaprodi->nama_dosen }}</u>
                        <br>
                        @if (substr($kaprodi->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $kaprodi->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $kaprodi->nip_nidk }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="page-break-after: always;"></div>
    <div class="content">
        <table>
            <tbody>
                <tr>
                    <td style="vertical-align: top;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/Universitas-Tanjungpura-Pontianak-bw.png'))) }}"
                            width="130px" style="min-width: 130px; height:auto;">
                    </td>
                    <td class="text-center leading-5" style="font-size: 14pt;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,
                        <br>DAN TEKNOLOGI
                        <br>
                        <b style="font-size: 12pt" class="font-bold">UNIVERSITAS TANJUNGPURA
                            <br>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM
                            <br>JURUSAN SISTEM INFORMASI
                        </b>
                        <br><span style="font-size: 10pt;">Jalan Prof. Dr. H. Hadari Nawawi, Pontianak 78124
                            <br>Telepon/Fax (0561) 577963, e-mail : <span
                                style="color: blue;"><u>sisfo@untan.ac.id</u></span></span>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-center"
                        style="font-size: 14pt; line-height: 1.25rem; padding-top:1.25rem; padding-bottom:1.25rem; border-top: 4px solid black;">
                        <b>FORM REKAPITULASI NILAI<br>SEMINAR HASIL PENELITIAN TUGAS AKHIR
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 10%" class="pb-2">
                        Nama
                    </td>
                    <td class="pb-2" style="width: 2%">
                        :
                    </td>
                    <td class="pb-2">
                        {{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 10%" class="pb-2">
                        NIM
                    </td>
                    <td class="pb-2" style="width: 2%">
                        :
                    </td>
                    <td class="pb-2">
                        {{ $seminarSidang->mahasiswa->nim }}
                    </td>
                </tr>
                <tr>
                    <td class="align-top" style="width: 10%;">
                        Judul
                    </td>
                    <td class="align-top" style="width: 2%">
                        :
                    </td>
                    <td class="align-top text-justify">
                        {{ $seminarSidang->tugas_akhir->judul }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="border-collapse: collapse; margin-top:30px; padding-right:200px;">
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width: 60%;">
                        <b>Penilai</b>
                    </th>
                    <th class="default-border">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1.5rem;">
                        <b>Nilai Seminar Hasil Penelitian</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="default-border py-3" style="padding-left: 0.3rem;">
                        {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}
                    </td>
                    <td class="default-border text-center">
                        {{ $seminarSidang->total_nilai_pembimbing_1 }}
                    </td>
                    <td class="default-border text-center" rowspan="4">
                        {{ $seminarSidang->total_nilai_akhir }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border py-3" style="padding-left: 0.3rem;">
                        {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}
                    </td>
                    <td class="default-border text-center">
                        {{ $seminarSidang->total_nilai_pembimbing_2 }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border py-3" style="padding-left: 0.3rem;">
                        {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nama_dosen }}
                    </td>
                    <td class="default-border text-center">
                        {{ $seminarSidang->total_nilai_penguji_1 }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border py-3" style="padding-left: 0.3rem;">
                        {{ $seminarSidang->tugas_akhir->dosen_penguji_2->nama_dosen }}
                    </td>
                    <td class="default-border text-center">
                        {{ $seminarSidang->total_nilai_penguji_2 }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 30px; padding-right:80px; margin-left:30px;">
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: right;">
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Mengetahui,
                    </td>
                </tr>
                <tr>
                    <td style="width: 65%; height:80px;" class="align-top">
                        Ketua Jurusan/Program Studi Sistem Informasi
                    </td>
                    <td class="align-top">
                        Ketua Tim Penguji,
                    </td>
                </tr>
                <tr>
                    <td>
                        <u>{{ $kaprodi->nama_dosen }}</u>
                        <br>
                        @if (substr($kaprodi->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $kaprodi->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $kaprodi->nip_nidk }}</span>
                        @endif
                    </td>
                    <td>
                        <u>{{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</u>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td colspan="2" style="padding-top: 50px;">
                        <i>Catatan : *) Nilai Pembimbing 50% dan Nilai Penguji 50%</i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if ($seminarSidang->total_nilai_pembimbing_1 !== null)
        <div style="page-break-after: always;"></div>
        <div class="content">
            <table>
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/Universitas-Tanjungpura-Pontianak-bw.png'))) }}"
                                width="130px" style="min-width: 130px; height:auto;">
                        </td>
                        <td class="text-center leading-5" style="font-size: 14pt;">KEMENTERIAN PENDIDIKAN TINGGI,
                            SAINS,
                            <br>DAN TEKNOLOGI
                            <br>
                            <b style="font-size: 12pt" class="font-bold">UNIVERSITAS TANJUNGPURA
                                <br>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM
                                <br>JURUSAN SISTEM INFORMASI
                            </b>
                            <br><span style="font-size: 10pt;">Jalan Prof. Dr. H. Hadari Nawawi, Pontianak 78124
                                <br>Telepon/Fax (0561) 577963, e-mail : <span
                                    style="color: blue;"><u>sisfo@untan.ac.id</u></span></span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td class="text-center"
                            style="font-size: 12pt; line-height: 1.25rem; padding-top:0.5rem; padding-bottom:0.2rem; border-top: 4px solid black;">
                            <b>Form Penilaian Seminar Hasil Penelitian Tugas Akhir</b>
                        </td>
                    </tr>

                    <tr>
                        <td style="">
                            Berdasarkan hasil Seminar Hasil Tugas Akhir yang telah dilaksanakan oleh:
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td style="width: 17%" class="">
                            Nama
                        </td>
                        <td class=" " style="width: 2%">
                            :
                        </td>
                        <td class="">
                            {{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 17%">
                            NIM
                        </td>
                        <td style="width: 2%">
                            :
                        </td>
                        <td>
                            {{ $seminarSidang->mahasiswa->nim }}
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top" style="width: 17%;">
                            Judul
                        </td>
                        <td class="align-top" style="width: 2%">
                            :
                        </td>
                        <td class="align-top text-justify">
                            {{ $seminarSidang->tugas_akhir->judul }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 17%">
                            Pembimbing I
                        </td>
                        <td style="width: 2%">
                            :
                        </td>
                        <td>
                            {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}
                        </td>
                    </tr>
                    <tr>
                        <td style="">
                            Nilai :
                        </td>
                        <td style="width: 2%">

                        </td>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="border-collapse: collapse; margin-top:5px; font-size:10pt;">
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:5%">
                            <b>No</b>
                        </th>
                        <th class="default-border" style="width:69%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:6%">
                            <b>Bobot<br>(%)</b>
                        </th>
                        <th class="default-border" style="width:5%">
                            <b>Nilai</b>
                        </th>
                        <th class="default-border" style="line-height: 1rem; width:10%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembimbing1nilais as $index => $pembimbing1nilai)
                        <tr>
                            <td class="default-border text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="default-border" style="padding: 3px 0 3px 3px;">
                                {{ $pembimbing1nilai->parameter_penilaian->nama_parameter }}, meliputi:
                                @php
                                    $deskripsi = $pembimbing1nilai->parameter_penilaian->deskripsi_parameter;
                                    if (strpos($deskripsi, '<ol>') !== false) {
                                        $deskripsi = str_replace('<ol>', '<ol class="">', $deskripsi);
                                    }
                                    if (strpos($deskripsi, '<ul>') !== false) {
                                        $deskripsi = str_replace('<ul>', '<ul class="parameter-list">', $deskripsi);
                                    }
                                    echo $deskripsi;
                                @endphp
                            </td>
                            <td class="default-border text-center">
                                {{ $pembimbing1nilai->parameter_penilaian->persentase }}%
                            </td>
                            <td class="default-border text-center">
                                {{ $pembimbing1nilai->nilai }}
                            </td>
                            <td class="default-border text-center">
                                @php
                                    $bobot = $pembimbing1nilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing1nilai->nilai;
                                    $total = $nilai * ($bobot / 100);
                                    echo $total;
                                @endphp
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="default-border text-center" style="height: 1.5rem;">
                            <b>TOTAL</b>
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalPersentasePembimbing1 = 0;
                                for ($i = 0; $i < count($pembimbing1nilais); $i++) {
                                    $totalPersentasePembimbing1 +=
                                        $pembimbing1nilais[$i]->parameter_penilaian->persentase;
                                }
                                echo $totalPersentasePembimbing1;
                            @endphp
                            %
                        </td>
                        <td style="background-color: black">
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalNilaiPembimbing1 = 0;
                                foreach ($pembimbing1nilais as $pembimbing1nilai) {
                                    $bobot = $pembimbing1nilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing1nilai->nilai;
                                    $totalNilaiPembimbing1 += $nilai * ($bobot / 100);
                                }
                                echo $totalNilaiPembimbing1;
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>

            <table style="margin-top: 10px; padding-left:65%; float: right;">
                <tbody>
                    <tr>
                        <td>
                            Pontianak,
                            {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td style="height:30px;" class="align-top">

                        </td>
                    </tr>
                    <tr>
                        <td style="white-space: nowrap;">
                            <u>{{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</u>
                            <br>
                            @if (substr($seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk, 0, 2) === '88')
                                <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk }}</span>
                            @else
                                <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    @if ($seminarSidang->total_nilai_pembimbing_2 !== null)
        <div style="page-break-after: always;"></div>
        <div class="content">
            <table>
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/Universitas-Tanjungpura-Pontianak-bw.png'))) }}"
                                width="130px" style="min-width: 130px; height:auto;">
                        </td>
                        <td class="text-center leading-5" style="font-size: 14pt;">KEMENTERIAN PENDIDIKAN TINGGI,
                            SAINS,
                            <br>DAN TEKNOLOGI
                            <br>
                            <b style="font-size: 12pt" class="font-bold">UNIVERSITAS TANJUNGPURA
                                <br>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM
                                <br>JURUSAN SISTEM INFORMASI
                            </b>
                            <br><span style="font-size: 10pt;">Jalan Prof. Dr. H. Hadari Nawawi, Pontianak 78124
                                <br>Telepon/Fax (0561) 577963, e-mail : <span
                                    style="color: blue;"><u>sisfo@untan.ac.id</u></span></span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td class="text-center"
                            style="font-size: 12pt; line-height: 1.25rem; padding-top:0.5rem; padding-bottom:0.2rem; border-top: 4px solid black;">
                            <b>Form Penilaian Seminar Hasil Penelitian Tugas Akhir</b>
                        </td>
                    </tr>

                    <tr>
                        <td style="">
                            Berdasarkan hasil Seminar Hasil Tugas Akhir yang telah dilaksanakan oleh:
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td style="width: 17%" class="">
                            Nama
                        </td>
                        <td class=" " style="width: 2%">
                            :
                        </td>
                        <td class="">
                            {{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 17%">
                            NIM
                        </td>
                        <td style="width: 2%">
                            :
                        </td>
                        <td>
                            {{ $seminarSidang->mahasiswa->nim }}
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top" style="width: 17%;">
                            Judul
                        </td>
                        <td class="align-top" style="width: 2%">
                            :
                        </td>
                        <td class="align-top text-justify">
                            {{ $seminarSidang->tugas_akhir->judul }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 17%">
                            Pembimbing II
                        </td>
                        <td style="width: 2%">
                            :
                        </td>
                        <td>
                            {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}
                        </td>
                    </tr>
                    <tr>
                        <td style="">
                            Nilai :
                        </td>
                        <td style="width: 2%">

                        </td>
                        <td>

                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="border-collapse: collapse; margin-top:10px; font-size:10pt;">
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:5%">
                            <b>No</b>
                        </th>
                        <th class="default-border" style="width:69%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:6%">
                            <b>Bobot<br>(%)</b>
                        </th>
                        <th class="default-border" style="width:5%">
                            <b>Nilai</b>
                        </th>
                        <th class="default-border" style="line-height: 1rem; width:10%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembimbing2nilais as $index => $pembimbing2nilai)
                        <tr>
                            <td class="default-border text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="default-border" style="padding: 3px 0 3px 3px;">
                                {{ $pembimbing2nilai->parameter_penilaian->nama_parameter }}, meliputi:
                                {{-- {!! $pembimbing2nilai->parameter_penilaian->deskripsi_parameter !!} --}}
                                @php
                                    $deskripsi = $pembimbing2nilai->parameter_penilaian->deskripsi_parameter;
                                    if (strpos($deskripsi, '<ol>') !== false) {
                                        $deskripsi = str_replace('<ol>', '<ol class="">', $deskripsi);
                                    }
                                    if (strpos($deskripsi, '<ul>') !== false) {
                                        $deskripsi = str_replace('<ul>', '<ul class="parameter-list">', $deskripsi);
                                    }
                                    echo $deskripsi;
                                @endphp
                            </td>
                            <td class="default-border text-center">
                                {{ $pembimbing2nilai->parameter_penilaian->persentase }}%
                            </td>
                            <td class="default-border text-center">
                                {{ $pembimbing2nilai->nilai }}
                            </td>
                            <td class="default-border text-center">
                                @php
                                    $bobot = $pembimbing2nilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing2nilai->nilai;
                                    $total = $nilai * ($bobot / 100);
                                    echo $total;
                                @endphp
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="default-border text-center" style="height: 1.5rem;">
                            <b>TOTAL</b>
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalPersentasePembimbing2 = 0;
                                for ($i = 0; $i < count($pembimbing2nilais); $i++) {
                                    $totalPersentasePembimbing2 +=
                                        $pembimbing2nilais[$i]->parameter_penilaian->persentase;
                                }
                                echo $totalPersentasePembimbing2;
                            @endphp
                            %
                        </td>
                        <td style="background-color: black">
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalNilaiPembimbing2 = 0;
                                foreach ($pembimbing2nilais as $pembimbing2nilai) {
                                    $bobot = $pembimbing2nilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing2nilai->nilai;
                                    $totalNilaiPembimbing2 += $nilai * ($bobot / 100);
                                }
                                echo $totalNilaiPembimbing2;
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>

            <table style="margin-top: 10px; padding-left:65%; float: right;">
                <tbody>
                    <tr>
                        <td>
                            Pontianak,
                            {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td style="height:30px;" class="align-top">

                        </td>
                    </tr>
                    <tr>
                        <td style="white-space: nowrap;">
                            <u>{{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</u>
                            <br>
                            @if (substr($seminarSidang->tugas_akhir->dosen_pembimbing_2->nip_nidk, 0, 2) === '88')
                                <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nip_nidk }}</span>
                            @else
                                <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nip_nidk }}</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    <div style="page-break-after: always;"></div>

    <div class="content">
        <table>
            <tbody>
                <tr>
                    <td style="vertical-align: top;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/Universitas-Tanjungpura-Pontianak-bw.png'))) }}"
                            width="130px" style="min-width: 130px; height:auto;">
                    </td>
                    <td class="text-center leading-5" style="font-size: 14pt;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,
                        <br>DAN TEKNOLOGI
                        <br>
                        <b style="font-size: 12pt" class="font-bold">UNIVERSITAS TANJUNGPURA
                            <br>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM
                            <br>JURUSAN SISTEM INFORMASI
                        </b>
                        <br><span style="font-size: 10pt;">Jalan Prof. Dr. H. Hadari Nawawi, Pontianak 78124
                            <br>Telepon/Fax (0561) 577963, e-mail : <span
                                style="color: blue;"><u>sisfo@untan.ac.id</u></span></span>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-center"
                        style="font-size: 12pt; line-height: 1.25rem; padding-top:0.5rem; padding-bottom:0.2rem; border-top: 4px solid black;">
                        <b>Form Penilaian Seminar Hasil Penelitian Tugas Akhir</b>
                    </td>
                </tr>

                <tr>
                    <td style="">
                        Berdasarkan hasil Seminar Hasil Tugas Akhir yang telah dilaksanakan oleh:
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 17%" class="">
                        Nama
                    </td>
                    <td class=" " style="width: 2%">
                        :
                    </td>
                    <td class="">
                        {{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 17%">
                        NIM
                    </td>
                    <td style="width: 2%">
                        :
                    </td>
                    <td>
                        {{ $seminarSidang->mahasiswa->nim }}
                    </td>
                </tr>
                <tr>
                    <td class="align-top" style="width: 17%;">
                        Judul
                    </td>
                    <td class="align-top" style="width: 2%">
                        :
                    </td>
                    <td class="align-top text-justify">
                        {{ $seminarSidang->tugas_akhir->judul }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 17%">
                        Penguji I
                    </td>
                    <td style="width: 2%">
                        :
                    </td>
                    <td>
                        {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nama_dosen }}
                    </td>
                </tr>
                <tr>
                    <td style="">
                        Nilai :
                    </td>
                    <td style="width: 2%">

                    </td>
                    <td>

                    </td>
                </tr>
            </tbody>
        </table>

        <table style="border-collapse: collapse; margin-top:10px; font-size:10pt;">
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:5%">
                        <b>No</b>
                    </th>
                    <th class="default-border" style="width:69%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:6%">
                        <b>Bobot<br>(%)</b>
                    </th>
                    <th class="default-border" style="width:5%">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1rem; width:10%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penguji1nilais as $index => $penguji1nilai)
                    <tr>
                        <td class="default-border text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="default-border" style="padding: 3px 0 3px 3px;">
                            {{ $penguji1nilai->parameter_penilaian->nama_parameter }}, meliputi:
                            {{-- {!! $penguji1nilai->parameter_penilaian->deskripsi_parameter !!} --}}
                            @php
                                $deskripsi = $penguji1nilai->parameter_penilaian->deskripsi_parameter;
                                if (strpos($deskripsi, '<ol>') !== false) {
                                    $deskripsi = str_replace('<ol>', '<ol class="">', $deskripsi);
                                }
                                if (strpos($deskripsi, '<ul>') !== false) {
                                    $deskripsi = str_replace('<ul>', '<ul class="parameter-list">', $deskripsi);
                                }
                                echo $deskripsi;
                            @endphp
                        </td>
                        <td class="default-border text-center">
                            {{ $penguji1nilai->parameter_penilaian->persentase }}%
                        </td>
                        <td class="default-border text-center">
                            {{ $penguji1nilai->nilai }}
                        </td>
                        <td class="default-border text-center">
                            @php
                                $bobot = $penguji1nilai->parameter_penilaian->persentase;
                                $nilai = $penguji1nilai->nilai;
                                $total = $nilai * ($bobot / 100);
                                echo $total;
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="default-border text-center" style="height: 1.5rem;">
                        <b>TOTAL</b>
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalPersentasePenguji1 = 0;
                            for ($i = 0; $i < count($penguji1nilais); $i++) {
                                $totalPersentasePenguji1 += $penguji1nilais[$i]->parameter_penilaian->persentase;
                            }
                            echo $totalPersentasePenguji1;
                        @endphp
                        %
                    </td>
                    <td style="background-color: black">
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalNilaiPenguji1 = 0;
                            foreach ($penguji1nilais as $penguji1nilai) {
                                $bobot = $penguji1nilai->parameter_penilaian->persentase;
                                $nilai = $penguji1nilai->nilai;
                                $totalNilaiPenguji1 += $nilai * ($bobot / 100);
                            }
                            echo $totalNilaiPenguji1;
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>

        <table style="margin-top: 10px; padding-left:65%; float: right;">
            <tbody>
                <tr>
                    <td>
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td style="height:30px;" class="align-top">

                    </td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">
                        <u>{{ $seminarSidang->tugas_akhir->dosen_penguji_1->nama_dosen }}</u>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="page-break-after: always;"></div>
    <div class="content">
        <table>
            <tbody>
                <tr>
                    <td style="vertical-align: top;">
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/Universitas-Tanjungpura-Pontianak-bw.png'))) }}"
                            width="130px" style="min-width: 130px; height:auto;">
                    </td>
                    <td class="text-center leading-5" style="font-size: 14pt;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,
                        <br>DAN TEKNOLOGI
                        <br>
                        <b style="font-size: 12pt" class="font-bold">UNIVERSITAS TANJUNGPURA
                            <br>FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM
                            <br>JURUSAN SISTEM INFORMASI
                        </b>
                        <br><span style="font-size: 10pt;">Jalan Prof. Dr. H. Hadari Nawawi, Pontianak 78124
                            <br>Telepon/Fax (0561) 577963, e-mail : <span
                                style="color: blue;"><u>sisfo@untan.ac.id</u></span></span>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-center"
                        style="font-size: 12pt; line-height: 1.25rem; padding-top:0.5rem; padding-bottom:0.2rem; border-top: 4px solid black;">
                        <b>Form Penilaian Seminar Hasil Penelitian Tugas Akhir</b>
                    </td>
                </tr>

                <tr>
                    <td style="">
                        Berdasarkan hasil Seminar Hasil Tugas Akhir yang telah dilaksanakan oleh:
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 17%" class="">
                        Nama
                    </td>
                    <td class=" " style="width: 2%">
                        :
                    </td>
                    <td class="">
                        {{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 17%">
                        NIM
                    </td>
                    <td style="width: 2%">
                        :
                    </td>
                    <td>
                        {{ $seminarSidang->mahasiswa->nim }}
                    </td>
                </tr>
                <tr>
                    <td class="align-top" style="width: 17%;">
                        Judul
                    </td>
                    <td class="align-top" style="width: 2%">
                        :
                    </td>
                    <td class="align-top text-justify">
                        {{ $seminarSidang->tugas_akhir->judul }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 17%">
                        Penguji II
                    </td>
                    <td style="width: 2%">
                        :
                    </td>
                    <td>
                        {{ $seminarSidang->tugas_akhir->dosen_penguji_2->nama_dosen }}
                    </td>
                </tr>
                <tr>
                    <td style="">
                        Nilai :
                    </td>
                    <td style="width: 2%">

                    </td>
                    <td>

                    </td>
                </tr>
            </tbody>
        </table>

        <table style="border-collapse: collapse; margin-top:10px; font-size:10pt;">
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:5%">
                        <b>No</b>
                    </th>
                    <th class="default-border" style="width:69%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:6%">
                        <b>Bobot<br>(%)</b>
                    </th>
                    <th class="default-border" style="width:5%">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1rem; width:10%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penguji2nilais as $index => $penguji2nilai)
                    <tr>
                        <td class="default-border text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="default-border" style="padding: 3px 0 3px 3px;">
                            {{ $penguji2nilai->parameter_penilaian->nama_parameter }}, meliputi:
                            {{-- {!! $penguji2nilai->parameter_penilaian->deskripsi_parameter !!} --}}
                            @php
                                $deskripsi = $penguji2nilai->parameter_penilaian->deskripsi_parameter;
                                if (strpos($deskripsi, '<ol>') !== false) {
                                    $deskripsi = str_replace('<ol>', '<ol class="">', $deskripsi);
                                }
                                if (strpos($deskripsi, '<ul>') !== false) {
                                    $deskripsi = str_replace('<ul>', '<ul class="parameter-list">', $deskripsi);
                                }
                                echo $deskripsi;
                            @endphp
                        </td>
                        <td class="default-border text-center">
                            {{ $penguji2nilai->parameter_penilaian->persentase }}%
                        </td>
                        <td class="default-border text-center">
                            {{ $penguji2nilai->nilai }}
                        </td>
                        <td class="default-border text-center">
                            @php
                                $bobot = $penguji2nilai->parameter_penilaian->persentase;
                                $nilai = $penguji2nilai->nilai;
                                $total = $nilai * ($bobot / 100);
                                echo $total;
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="default-border text-center" style="height: 1.5rem;">
                        <b>TOTAL</b>
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalPersentasePenguji2 = 0;
                            for ($i = 0; $i < count($penguji2nilais); $i++) {
                                $totalPersentasePenguji2 += $penguji2nilais[$i]->parameter_penilaian->persentase;
                            }
                            echo $totalPersentasePenguji2;
                        @endphp
                        %
                    </td>
                    <td style="background-color: black">
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalNilaiPenguji2 = 0;
                            foreach ($penguji2nilais as $penguji2nilai) {
                                $bobot = $penguji2nilai->parameter_penilaian->persentase;
                                $nilai = $penguji2nilai->nilai;
                                $totalNilaiPenguji2 += $nilai * ($bobot / 100);
                            }
                            echo $totalNilaiPenguji2;
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>

        <table style="margin-top: 10px; padding-left:65%; float: right;">
            <tbody>
                <tr>
                    <td>
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td style="height:30px;" class="align-top">

                    </td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">
                        <u>{{ $seminarSidang->tugas_akhir->dosen_penguji_2->nama_dosen }}</u>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_penguji_2->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_penguji_2->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_penguji_2->nip_nidk }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
