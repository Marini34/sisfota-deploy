<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>
        BA_Sempro_{{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}_{{ ucwords(strtolower($seminarSidang->mahasiswa->nim)) }}
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
                        style="font-size: 13pt; line-height: 1.25rem; padding-top:1.25rem; padding-bottom:1.25rem; border-top: 4px solid black;">
                        <b>BERITA ACARA<br>SEMINAR PROPOSAL TUGAS AKHIR
                    </td>
                </tr>

                <tr>
                    <td class="pt-4 pb-2">
                        Berdasarkan hasil Seminar Proposal Tugas Akhir yang telah dilaksanakan oleh:
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
                    <td colspan="3" class="text-justify">
                        yang dilaksanakan pada hari
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('l') }}, tanggal
                        <b>{{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}</b>
                        pada pukul <b>{{ Carbon\Carbon::parse($seminarSidang->jam_pelaksanaan)->format('H:i') }} s.d.
                            {{ Carbon\Carbon::parse($seminarSidang->jam_pelaksanaan)->addHours(2)->format('H:i') }}</b>
                        WIB secara daring
                        pada FMIPA Universitas Tanjungpura.
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">
                        Pembimbing
                    </td>
                    <td style="width: 2%">
                        :
                    </td>
                    <td>
                        1. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%">

                    </td>
                    <td style="width: 2%">

                    </td>
                    <td>
                        2. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        Adapun Proposal Penelitian Tugas Akhir tersebut : <b
                            style="text-transform: lowercase;">{{ $seminarSidang->total_nilai_akhir < $passingGrade ? 'DITOLAK' : 'DITERIMA' }}.</b>
                        <br>
                        dengan catatan : **Lampiran
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                        {{-- Pontianak, {{ Carbon\Carbon::now()->translatedFormat('d F Y') }} --}}
                    </td>
                </tr>
            </tbody>
        </table>
        <table>
            <tbody>
                <tr>
                    <td colspan="2" class="text-center align-top " style="height: 40px">
                        Tim Penguji
                    </td>
                </tr>
                <tr>
                    <td class="align-top " style=" height: 70px">
                        Ketua,
                    </td>
                    <td class="align-top ">
                        Sekretaris,
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
                        Penguji Pertama,
                    </td>
                    <td class="align-top ">
                        Penguji Kedua,
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
                    <td colspan="2" class="text-center align-top leading-5 " style="height: 80px">
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
                <tr>
                    <td colspan="2" style="font-size: 10pt;">
                        **) : Notulensi Seminar Proposal TA
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
                        style="font-size: 13pt; line-height: 1.25rem; padding-top:1.25rem; padding-bottom:1.25rem; border-top: 4px solid black;">
                        <b>FORM REKAPITULASI NILAI<br>SEMINAR PROPOSAL TUGAS AKHIR
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
                        <b>Nilai Seminar Proposal Penelitian</b>
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
        <table style="margin-top: 30px;">
            <tbody>
                <tr>
                    <td style="text-align: right;">

                    </td>
                    <td style="text-align: left;">
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                        {{-- Pontianak, {{ Carbon\Carbon::now()->translatedFormat('d F Y') }} --}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top: 10px;">
                        Mengetahui,
                    </td>
                </tr>
                <tr>
                    <td style="width: 65%; height:80px;" class="align-top">
                        Ketua Jurusan Sistem Informasi
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
    </div>
</body>

</html>
