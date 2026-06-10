<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>BA_Sidang
        Sarjana_{{ ucwords(strtolower($seminarSidang->mahasiswa->nama_lengkap)) }}_{{ ucwords(strtolower($seminarSidang->mahasiswa->nim)) }}
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

<body>
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
                        <u><b>BERITA ACARA SIDANG SARJANA</b></u><br>
                        <b>Nomor:
                            {{ $seminarSidang->no_surat }}/DST/UN22.8/PK.03.05/{{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('Y') }}</b>
                    </td>
                </tr>

                <tr>
                    <td class="pt-4 pb-2 text-justify" style="line-height: 1.7;">
                        Pada hari ini
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('l') }},
                        tanggal
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }} telah
                        dilaksanakan Sidang Sarjana
                        oleh Tim Penguji terhadap mahasiswa :
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 20%" class="pb-2">
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
                    <td style="width: 10%" class="pb-2">
                        Program Studi
                    </td>
                    <td class=" pb-2" style="width: 2%">
                        :
                    </td>
                    <td class=" pb-2">
                        Sistem Informasi
                    </td>
                </tr>
                <tr>
                    <td class="align-top" style="width: 10%;">
                        Judul TA
                    </td>
                    <td class=" align-top" style="width: 2%">
                        :
                    </td>
                    <td class=" align-top text-justify">
                        {{ $seminarSidang->tugas_akhir->judul }}
                    </td>
                </tr>
                <tr>
                    <td class="align-top" style="width: 10%;">
                        Nilai Ujian
                    </td>
                    <td class=" align-top" style="width: 2%">
                        :
                    </td>
                    <td class=" align-top text-justify">
                        @php
                            $nilaiMakulTA =
                                $semhas->total_nilai_akhir * (40 / 100) +
                                $seminarSidang->total_nilai_akhir * (60 / 100);
                        @endphp

                        {{ number_format($nilaiMakulTA, 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="" style="height: 2rem;">
                        Mahasiswa yang bersangkutan dinyatakan
                        {{ $seminarSidang->total_nilai_akhir > $passingGrade ? 'LULUS' : 'TIDAK LULUS' }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td colspan="2" class="text-center" style="height: 4rem;">
                        TIM PENGUJI
                    </td>
                </tr>
                <tr>
                    <td class="align-top" style=" height: 70px; padding-left:3rem;">
                        Ketua,
                    </td>
                    <td class="align-top">
                        Sekretaris,
                    </td>
                </tr>
                <tr>
                    <td class="align-top leading-5" style="width: 60%; height: 60px; padding-left:3rem;">
                        <span><u>{{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</u></span>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nip_nidk }}</span>
                        @endif
                    </td>
                    <td class="align-top leading-5" style=" height: 50px">
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
                    <td class="align-top" style="height: 70px; padding-left:3rem;">
                        Penguji Pertama,
                    </td>
                    <td class="align-top">
                        Penguji Kedua,
                    </td>
                </tr>
                <tr>
                    <td class="align-top leading-5" style=" height: 50px; padding-left:3rem;">
                        <span><u>{{ $seminarSidang->tugas_akhir->dosen_penguji_1->nama_dosen }}</u></span>
                        <br>
                        @if (substr($seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nip_nidk }}</span>
                        @endif
                    </td>
                    <td class="align-top leading-5" style=" height: 50px">
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
                    <td colspan="2" class="text-center align-top leading-5" style="height: 80px; padding-top:20px;">
                        Mengetahui<br>Dekan Fakultas MIPA
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center align-top leading-5" style="height: 50px;">
                        <u>{{ $dekan->nama_dosen }}</u>
                        <br>
                        @if (substr($dekan->nip_nidk, 0, 2) === '88')
                            <span>NIDK. {{ $dekan->nip_nidk }}</span>
                        @else
                            <span>NIP. {{ $dekan->nip_nidk }}</span>
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
                        style="font-size: 13pt; line-height: 1.25rem; padding-top:1.25rem; padding-bottom:1.25rem; border-top: 4px solid black;">
                        <b>REKAPITULASI NILAI SIDANG SARJANA</b>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <tbody>
                <tr>
                    <td style="width: 20%" class="pb-2">
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
                    <td style="width: 10%" class="pb-2">
                        Jurusan/Prodi
                    </td>
                    <td class=" pb-2" style="width: 2%">
                        :
                    </td>
                    <td class=" pb-2">
                        Sistem Informasi/Sistem Informsi
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
                <tr>
                    <td class="align-top" style="width: 10%;">
                        Tim Penguji
                    </td>
                    <td class=" align-top" style="width: 2%">
                        :
                    </td>
                    <td class="align-top text-justify">
                        <table style="width: auto;">
                            <tbody>
                                <tr>
                                    <td style="padding-right: 1.5rem;">
                                        1. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}
                                    </td>
                                    <td>
                                        (Ketua)
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 1.5rem;">
                                        2. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}
                                    </td>
                                    <td>
                                        (Sekretaris)
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 1.5rem;">
                                        3. {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nama_dosen }}
                                    </td>
                                    <td>
                                        (Penguji Pertama)
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-right: 1.5rem;">
                                        4. {{ $seminarSidang->tugas_akhir->dosen_penguji_2->nama_dosen }}
                                    </td>
                                    <td>
                                        (Penguji Kedua)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <p><b>Nilai Sidang Sarjana :</b></p>
        <table style="border-collapse: collapse; margin-top:5px; padding-right:200px;">
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width: 40%;">
                        <b>Penilai</b>
                    </th>
                    <th class="default-border">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border">
                        <b>Total Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1.5rem;">
                        <b>Nilai Rata-rata</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="default-border py-3" style="padding-left: 0.3rem;">
                        Ketua
                    </td>
                    <td class="default-border text-center">
                        {{ $seminarSidang->total_nilai_pembimbing_1 }}
                    </td>
                    <td class="default-border text-center" rowspan="4">
                        @php
                            $nilaiPembimbing1 = $seminarSidang->total_nilai_pembimbing_1;
                            $nilaiPembimbing2 = $seminarSidang->total_nilai_pembimbing_2;
                            $nilaiPenguji1 = $seminarSidang->total_nilai_penguji_1;
                            $nilaiPenguji2 = $seminarSidang->total_nilai_penguji_2;
                            $totalNilai = $nilaiPembimbing1 + $nilaiPembimbing2 + $nilaiPenguji1 + $nilaiPenguji2;
                            echo $totalNilai;
                        @endphp
                    </td>
                    <td class="default-border text-center" rowspan="4">
                        {{ $seminarSidang->total_nilai_akhir }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border py-3" style="padding-left: 0.3rem;">
                        Sekretaris
                    </td>
                    <td class="default-border text-center">
                        {{ $seminarSidang->total_nilai_pembimbing_2 }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border py-3" style="padding-left: 0.3rem;">
                        Penguji Pertama
                    </td>
                    <td class="default-border text-center">
                        {{ $seminarSidang->total_nilai_penguji_1 }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border py-3" style="padding-left: 0.3rem;">
                        Penguji Kedua
                    </td>
                    <td class="default-border text-center">
                        {{ $seminarSidang->total_nilai_penguji_2 }}
                    </td>
                </tr>
            </tbody>
        </table>
        <p><b>Nilai Seminar Hasil Penelitian : {{ $semhas->total_nilai_akhir }}</b></p>
        <p><b>Nilai Mata Kuliah Tugas Akhir :
                @php
                    $nilaiSemhas = $semhas->total_nilai_akhir;
                    $nilaiSidang = $seminarSidang->total_nilai_akhir;
                    $nilaiMakulTA = $nilaiSemhas * (40 / 100) + $nilaiSidang * (60 / 100);
                    echo number_format($nilaiMakulTA, 2);
                @endphp
            </b></p>
        <p><b>Huruf Mutu =
                @php
                    if (0 <= $nilaiMakulTA && $nilaiMakulTA < 50) {
                        $mutu = 'E';
                    } elseif (50 <= $nilaiMakulTA && $nilaiMakulTA < 55) {
                        $mutu = 'D';
                    } elseif (55 <= $nilaiMakulTA && $nilaiMakulTA < 60) {
                        $mutu = 'D+';
                    } elseif (60 <= $nilaiMakulTA && $nilaiMakulTA < 65) {
                        $mutu = 'C';
                    } elseif (65 <= $nilaiMakulTA && $nilaiMakulTA < 70) {
                        $mutu = 'C+';
                    } elseif (70 <= $nilaiMakulTA && $nilaiMakulTA < 75) {
                        $mutu = 'B';
                    } elseif (75 <= $nilaiMakulTA && $nilaiMakulTA < 80) {
                        $mutu = 'B+';
                    } elseif (80 <= $nilaiMakulTA && $nilaiMakulTA <= 100) {
                        $mutu = 'A';
                    } else {
                        $mutu = 'NaN';
                    }

                    echo $mutu;
                @endphp
            </b></p>
        <table>
            <tbody>
                <tr>
                    <td>
			
                    </td>
                    <td>
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 60%; height:80px;" class="align-top">
			
                    </td>
                    <td class="align-top">
                        Ketua Tim Penguji,
                    </td>
                </tr>
                <tr>
                    <td>
		
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

    <div style="page-break-after: always;"></div>

    <div class="content">
        @if ($statusTranskrip)
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
                            style="font-size: 13pt; line-height: 1.25rem; padding-top:1.25rem; padding-bottom:1.25rem; border-top: 4px solid black;">
                            <b>BERITA ACARA YUDISIUM SARJANA</b>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td style="width: 20%" class="pb-2">
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
                        <td style="width: 10%" class="pb-2">
                            Program Studi
                        </td>
                        <td class=" pb-2" style="width: 2%">
                            :
                        </td>
                        <td class=" pb-2">
                            Sistem Informasi
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top" style="width: 10%;">
                            Judul TA
                        </td>
                        <td class=" align-top" style="width: 2%">
                            :
                        </td>
                        <td class=" align-top text-justify">
                            {{ $seminarSidang->tugas_akhir->judul }}
                        </td>
                    </tr>
                    <tr>
                        <td class="align-top" style="width: 10%;">
                            Nilai Ujian
                        </td>
                        <td class=" align-top" style="width: 2%">
                            :
                        </td>
                        <td class="align-top text-justify">
                            {{ number_format($nilaiMakulTA, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <p style="margin-top: -0rem;">Hasil Ujian yang diperoleh</p>
            <table style="border-collapse: collapse; margin-top:5px; padding-right:200px;">
                <tr>
                    <td class="pb-2">
                        A. Jumlah Sebelum Tugas Akhir
                    </td>
                    <td>
                        {{ $statusTranskrip->jumlah_sks }}
                    </td>
                </tr>
                <tr>
                    <td class="pb-2" style="width: 70%;">
                        B. Jumlah Mutu Sebelum Tugas Akhir
                    </td>
                    <td>
                        {{ number_format($statusTranskrip->jumlah_mutu, 1) }}
                    </td>
                </tr>
                <tr>
                    <td class="pb-2">
                        C. IPK Sebelum Tugas Akhir
                    </td>
                    <td>
                        {{ number_format($statusTranskrip->ipk, 2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="pb-2">
                        <table style="border-collapse: collapse; width: 50%">
                            <tbody>
                                <tr>
                                    <td class="default-border text-center" style="width: 20%; padding:0.3rem;">Nilai
                                        Skripsi</td>
                                    <td class="default-border text-center" style="padding: 0.3rem; width:10%;">K</td>
                                    <td class="default-border text-center" style="padding: 0.3rem; width:10%;">K x N
                                    </td>
                                </tr>
                                <tr>
                                    <td class="default-border text-center" style="padding: 0.3rem;">
                                        {{ $mutu }}</td>
                                    <td class="default-border text-center" style="padding: 0.3rem;">6</td>
                                    <td class="default-border text-center" style="padding: 0.3rem;">
                                        @php
                                            switch ($mutu) {
                                                case 'A':
                                                    $nilai = 4;
                                                    break;
                                                case 'B+':
                                                    $nilai = 3.5;
                                                    break;
                                                case 'B':
                                                    $nilai = 3.0;
                                                    break;
                                                case 'C+':
                                                    $nilai = 2.5;
                                                    break;
                                                case 'C':
                                                    $nilai = 2.0;
                                                    break;
                                                case 'D+':
                                                    $nilai = 1.5;
                                                    break;
                                                case 'D':
                                                    $nilai = 1.0;
                                                    break;
                                                case 'E':
                                                    $nilai = 0;
                                                    break;
                                                default:
                                                    $nilai = null;
                                                    break;
                                            }
                                            $total = $nilai * 6;
                                            echo $total;
                                        @endphp
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        D. IPK Setelah Tugas Akhir
                    </td>
                    <td>
                        @php
                            $mutuAkhir = $statusTranskrip->jumlah_mutu + $total;
                            $sksAkhir = $statusTranskrip->jumlah_sks + 6; // 6 adalah sks tugas akhir
                            $finalIpk = $mutuAkhir / $sksAkhir;

                            echo number_format($finalIpk, 2);
                        @endphp
                    </td>
                </tr>
            </table>
            <p class="text-justify" style="line-height: 1.7rem;">
                Berdasarkan nilai-nilai tersebut di atas serta ketentuan yang berlaku di Universitas Tanjungpura,
                maka yang bersangkutan dinyatakan
                {{ $seminarSidang->total_nilai_akhir < $passingGrade ? 'TIDAK LULUS' : 'LULUS' }} dengan predikat :
                <span style="font-weight: bold; text-transform: uppercase;">
                    @php
                        // $tahunMasuk = $seminarSidang->mahasiswa->tahun_masuk;
                        // $tahunSekarang = date('Y');
                        // $masaStudi = $tahunSekarang - $tahunMasuk;

                        // Ambil tahun masuk dan tentukan tanggal masuk sebagai 1 Agustus tahun masuk
                        $tahunMasuk = $seminarSidang->mahasiswa->tahun_masuk;
                        $tanggalMasuk = new DateTime($tahunMasuk . '-08-01');

                        // Ambil tanggal lulus dari data seminar sidang
                        $tanggalLulus = new DateTime($seminarSidang->tanggal_pelaksanaan);

                        // Hitung selisih antara tanggal lulus dan tanggal masuk
                        $interval = $tanggalMasuk->diff($tanggalLulus);
                        $masaStudi = $interval->y + $interval->m / 12; // Mengubah bulan menjadi tahun desimal

                        if ($finalIpk >= 2.76 && $finalIpk <= 3.0) {
                            echo 'Memuaskan';
                        } elseif ($finalIpk >= 3.01 && $finalIpk <= 3.5) {
                            echo 'Sangat Memuaskan';
                        } elseif ($finalIpk >= 3.51 && $finalIpk <= 4.0 && $masaStudi <= 5) {
                            echo 'Dengan Pujian';
                        } elseif ($finalIpk >= 3.51 && $finalIpk <= 4.0) {
                            echo 'Sangat Memuaskan';
                        } else {
                            echo 'IPK tidak valid atau di luar kategori yang ditentukan';
                        }
                    @endphp.
                </span>
            </p>
            <table style="margin-top: 30px;">
                <tbody>
                    <tr>
                        <td style="text-align: right;">

                        </td>
                        <td>
                            Pontianak,
                            {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 60%; height:80px;" class="align-top">

                        </td>
                        <td class="align-top">
                            Ketua Tim Penguji,
                        </td>
                    </tr>
                    <tr>
                        <td>

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
        @else
            <b>Transkrip Nilai Mahasiswa Bermasalah</b>
        @endif
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
                            style="font-size: 12pt; line-height: 1.25rem; padding-top:0.5rem; padding-bottom:0.5rem; border-top: 4px solid black;">
                            <b>FORM PENILAIAN SIDANG SARJANA</b>
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
                            Dosen Penguji
                        </td>
                        <td style="width: 2%">
                            :
                        </td>
                        <td>
                            {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="border-collapse: collapse; margin-top:10px; font-size:12pt;">
                <tr>
                    <td colspan="2" style="padding-bottom: 0.5rem;">
                        <b>1. Penilaian Skripsi</b>
                    </td>
                </tr>
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:10%">
                            <b>No</b>
                        </th>
                        <th class="default-border" style="width:50%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Bobot<br>(%)</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Nilai</b>
                        </th>
                        <th class="default-border" style="line-height: 1.5rem; width:10%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembimbing1SkripsiNilais as $index => $pembimbing1SkripsiNilai)
                        <tr>
                            <td class="default-border text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="default-border" style="padding: 3px 0 3px 3px;">
                                {{ $pembimbing1SkripsiNilai->parameter_penilaian->nama_parameter }}, meliputi:
                                {{-- {!! $pembimbing1SkripsiNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                                @php
                                    $deskripsi = $pembimbing1SkripsiNilai->parameter_penilaian->deskripsi_parameter;
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
                                {{ $pembimbing1SkripsiNilai->parameter_penilaian->persentase }}%
                            </td>
                            <td class="default-border text-center" style="background-color: #CFE2F3;">
                                {{ $pembimbing1SkripsiNilai->nilai }}
                            </td>
                            <td class="default-border text-center">
                                @php
                                    $bobot = $pembimbing1SkripsiNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing1SkripsiNilai->nilai;
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
                                $totalPersentaseSkripsiPembimbing1 = 0;
                                for ($i = 0; $i < count($pembimbing1SkripsiNilais); $i++) {
                                    $totalPersentaseSkripsiPembimbing1 +=
                                        $pembimbing1SkripsiNilais[$i]->parameter_penilaian->persentase;
                                }
                                echo $totalPersentaseSkripsiPembimbing1;
                            @endphp
                            %
                        </td>
                        <td style="background-color: black">
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalNilaiSkripsiPembimbing1 = 0;
                                foreach ($pembimbing1SkripsiNilais as $pembimbing1SkripsiNilai) {
                                    $bobot = $pembimbing1SkripsiNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing1SkripsiNilai->nilai;
                                    $totalNilaiSkripsiPembimbing1 += $nilai * ($bobot / 100);
                                }
                                echo $totalNilaiSkripsiPembimbing1;
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="content">
            <table style="border-collapse: collapse; margin-top:10px; font-size:12pt;">
                <tr>
                    <td colspan="2" style="padding-bottom: 0.5rem;">
                        <b>2. Penilaian Artikel Ilmiah</b>
                    </td>
                </tr>
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:10%">
                            <b>No</b>
                        </th>
                        <th class="default-border" style="width:50%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Bobot<br>(%)</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Nilai</b>
                        </th>
                        <th class="default-border" style="line-height: 1.5rem; width:10%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembimbing1ArtikelNilais as $index => $pembimbing1ArtikelNilai)
                        <tr>
                            <td class="default-border text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="default-border" style="padding: 3px 0 3px 3px;">
                                {{ $pembimbing1ArtikelNilai->parameter_penilaian->nama_parameter }}, meliputi:
                                {{-- {!! $pembimbing1ArtikelNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                                @php
                                    $deskripsi = $pembimbing1ArtikelNilai->parameter_penilaian->deskripsi_parameter;
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
                                {{ $pembimbing1ArtikelNilai->parameter_penilaian->persentase }}%
                            </td>
                            <td class="default-border text-center" style="background-color: #CFE2F3;">
                                {{ $pembimbing1ArtikelNilai->nilai }}
                            </td>
                            <td class="default-border text-center">
                                @php
                                    $bobot = $pembimbing1ArtikelNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing1ArtikelNilai->nilai;
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
                                $totalPersentaseArtikelPembimbing1 = 0;
                                for ($i = 0; $i < count($pembimbing1ArtikelNilais); $i++) {
                                    $totalPersentaseArtikelPembimbing1 +=
                                        $pembimbing1ArtikelNilais[$i]->parameter_penilaian->persentase;
                                }
                                echo $totalPersentaseArtikelPembimbing1;
                            @endphp
                            %
                        </td>
                        <td style="background-color: black">
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalNilaiArtikelPembimbing1 = 0;
                                foreach ($pembimbing1ArtikelNilais as $pembimbing1ArtikelNilai) {
                                    $bobot = $pembimbing1ArtikelNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing1ArtikelNilai->nilai;
                                    $totalNilaiArtikelPembimbing1 += $nilai * ($bobot / 100);
                                }
                                echo $totalNilaiArtikelPembimbing1;
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>

            <table style="border-collapse: collapse; margin-top:30px; font-size:12pt;">
                <tr>
                    <td colspan="2" style="padding-bottom: 0.5rem;">
                        <b>3. Penilaian Presentasi</b>
                    </td>
                </tr>
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:10%">
                            <b>No</b>
                        </th>
                        <th class="default-border" style="width:50%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Bobot<br>(%)</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Nilai</b>
                        </th>
                        <th class="default-border" style="line-height: 1.5rem; width:10%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembimbing1PresentasiNilais as $index => $pembimbing1PresentasiNilai)
                        <tr>
                            <td class="default-border text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="default-border" style="padding: 3px 0 3px 3px;">
                                {{ $pembimbing1PresentasiNilai->parameter_penilaian->nama_parameter }}, meliputi:
                                {{-- {!! $pembimbing1PresentasiNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                                @php
                                    $deskripsi = $pembimbing1PresentasiNilai->parameter_penilaian->deskripsi_parameter;
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
                                {{ $pembimbing1PresentasiNilai->parameter_penilaian->persentase }}%
                            </td>
                            <td class="default-border text-center" style="background-color: #CFE2F3;">
                                {{ $pembimbing1PresentasiNilai->nilai }}
                            </td>
                            <td class="default-border text-center">
                                @php
                                    $bobot = $pembimbing1PresentasiNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing1PresentasiNilai->nilai;
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
                                $totalPersentasePresentasiPembimbing1 = 0;
                                for ($i = 0; $i < count($pembimbing1PresentasiNilais); $i++) {
                                    $totalPersentasePresentasiPembimbing1 +=
                                        $pembimbing1PresentasiNilais[$i]->parameter_penilaian->persentase;
                                }
                                echo $totalPersentasePresentasiPembimbing1;
                            @endphp
                            %
                        </td>
                        <td style="background-color: black">
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalNilaiPresentasiPembimbing1 = 0;
                                foreach ($pembimbing1PresentasiNilais as $pembimbing1PresentasiNilai) {
                                    $bobot = $pembimbing1PresentasiNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing1PresentasiNilai->nilai;
                                    $totalNilaiPresentasiPembimbing1 += $nilai * ($bobot / 100);
                                }
                                echo $totalNilaiPresentasiPembimbing1;
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>

            <table style="border-collapse: collapse; margin-top:30px; font-size:12pt;">
                <tr>
                    <td colspan="2" style="padding-bottom: 0.5rem;">
                        <b>4. Daftar Nilai</b>
                    </td>
                </tr>
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:30%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:40%">
                            <b>Bobot (%)</b>
                        </th>
                        <th class="default-border" style="width:15%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                        <th class="default-border" style="width:15%">
                            <b>Nilai rata-rata</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                            Skripsi
                        </td>
                        <td class="default-border text-center">
                            50%
                        </td>
                        <td class="default-border text-center" style="font-weight: bold;">
                            {{ $totalNilaiSkripsiPembimbing1 * (50 / 100) }}
                        </td>
                        <td rowspan="3" class="default-border text-center" style="font-weight: bold;">
                            {{ $seminarSidang->total_nilai_pembimbing_1 }}
                        </td>
                    </tr>
                    <tr>
                        <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                            Artikel
                        </td>
                        <td class="default-border text-center">
                            30%
                        </td>
                        <td class="default-border text-center" style="font-weight: bold;">
                            {{ $totalNilaiArtikelPembimbing1 * (30 / 100) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                            Presentasi
                        </td>
                        <td class="default-border text-center">
                            20%
                        </td>
                        <td class="default-border text-center" style="font-weight: bold;">
                            {{ $totalNilaiPresentasiPembimbing1 * (20 / 100) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="margin-top: 10px; padding-left:65%; float: right;">
                <tbody>
                    <tr>
                        <td>
                            Pontianak,
                            {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                            <br>Penguji
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td style="height:40px;" class="align-top">

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
                            style="font-size: 12pt; line-height: 1.25rem; padding-top:0.5rem; padding-bottom:0.5rem; border-top: 4px solid black;">
                            <b>FORM PENILAIAN SIDANG SARJANA</b>
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
                            Dosen Penguji
                        </td>
                        <td style="width: 2%">
                            :
                        </td>
                        <td>
                            {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <table style="border-collapse: collapse; margin-top:10px; font-size:12pt;">
                <tr>
                    <td colspan="2" style="padding-bottom: 0.5rem;">
                        <b>1. Penilaian Skripsi</b>
                    </td>
                </tr>
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:10%">
                            <b>No</b>
                        </th>
                        <th class="default-border" style="width:50%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Bobot<br>(%)</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Nilai</b>
                        </th>
                        <th class="default-border" style="line-height: 1.5rem; width:10%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembimbing2SkripsiNilais as $index => $pembimbing2SkripsiNilai)
                        <tr>
                            <td class="default-border text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="default-border" style="padding: 3px 0 3px 3px;">
                                {{ $pembimbing2SkripsiNilai->parameter_penilaian->nama_parameter }}, meliputi:
                                {{-- {!! $pembimbing2SkripsiNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                                @php
                                    $deskripsi = $pembimbing2SkripsiNilai->parameter_penilaian->deskripsi_parameter;
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
                                {{ $pembimbing2SkripsiNilai->parameter_penilaian->persentase }}%
                            </td>
                            <td class="default-border text-center" style="background-color: #CFE2F3;">
                                {{ $pembimbing2SkripsiNilai->nilai }}
                            </td>
                            <td class="default-border text-center">
                                @php
                                    $bobot = $pembimbing2SkripsiNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing2SkripsiNilai->nilai;
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
                                $totalPersentaseSkripsiPembimbing2 = 0;
                                for ($i = 0; $i < count($pembimbing2SkripsiNilais); $i++) {
                                    $totalPersentaseSkripsiPembimbing2 +=
                                        $pembimbing2SkripsiNilais[$i]->parameter_penilaian->persentase;
                                }
                                echo $totalPersentaseSkripsiPembimbing2;
                            @endphp
                            %
                        </td>
                        <td style="background-color: black">
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalNilaiSkripsiPembimbing2 = 0;
                                foreach ($pembimbing2SkripsiNilais as $pembimbing2SkripsiNilai) {
                                    $bobot = $pembimbing2SkripsiNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing2SkripsiNilai->nilai;
                                    $totalNilaiSkripsiPembimbing2 += $nilai * ($bobot / 100);
                                }
                                echo $totalNilaiSkripsiPembimbing2;
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div style="page-break-after: always;"></div>
        <div class="content">
            <table style="border-collapse: collapse; margin-top:10px; font-size:12pt;">
                <tr>
                    <td colspan="2" style="padding-bottom: 0.5rem;">
                        <b>2. Penilaian Artikel Ilmiah</b>
                    </td>
                </tr>
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:10%">
                            <b>No</b>
                        </th>
                        <th class="default-border" style="width:50%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Bobot<br>(%)</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Nilai</b>
                        </th>
                        <th class="default-border" style="line-height: 1.5rem; width:10%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembimbing2ArtikelNilais as $index => $pembimbing2ArtikelNilai)
                        <tr>
                            <td class="default-border text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="default-border" style="padding: 3px 0 3px 3px;">
                                {{ $pembimbing2ArtikelNilai->parameter_penilaian->nama_parameter }}, meliputi:
                                {{-- {!! $pembimbing2ArtikelNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                                @php
                                    $deskripsi = $pembimbing2ArtikelNilai->parameter_penilaian->deskripsi_parameter;
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
                                {{ $pembimbing2ArtikelNilai->parameter_penilaian->persentase }}%
                            </td>
                            <td class="default-border text-center" style="background-color: #CFE2F3;">
                                {{ $pembimbing2ArtikelNilai->nilai }}
                            </td>
                            <td class="default-border text-center">
                                @php
                                    $bobot = $pembimbing2ArtikelNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing2ArtikelNilai->nilai;
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
                                $totalPersentaseArtikelPembimbing2 = 0;
                                for ($i = 0; $i < count($pembimbing2ArtikelNilais); $i++) {
                                    $totalPersentaseArtikelPembimbing2 +=
                                        $pembimbing2ArtikelNilais[$i]->parameter_penilaian->persentase;
                                }
                                echo $totalPersentaseArtikelPembimbing2;
                            @endphp
                            %
                        </td>
                        <td style="background-color: black">
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalNilaiArtikelPembimbing2 = 0;
                                foreach ($pembimbing2ArtikelNilais as $pembimbing2ArtikelNilai) {
                                    $bobot = $pembimbing2ArtikelNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing2ArtikelNilai->nilai;
                                    $totalNilaiArtikelPembimbing2 += $nilai * ($bobot / 100);
                                }
                                echo $totalNilaiArtikelPembimbing2;
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>

            <table style="border-collapse: collapse; margin-top:30px; font-size:12pt;">
                <tr>
                    <td colspan="2" style="padding-bottom: 0.5rem;">
                        <b>3. Penilaian Presentasi</b>
                    </td>
                </tr>
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:10%">
                            <b>No</b>
                        </th>
                        <th class="default-border" style="width:50%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Bobot<br>(%)</b>
                        </th>
                        <th class="default-border" style="width:10%">
                            <b>Nilai</b>
                        </th>
                        <th class="default-border" style="line-height: 1.5rem; width:10%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pembimbing2PresentasiNilais as $index => $pembimbing2PresentasiNilai)
                        <tr>
                            <td class="default-border text-center">
                                {{ $index + 1 }}
                            </td>
                            <td class="default-border" style="padding: 3px 0 3px 3px;">
                                {{ $pembimbing2PresentasiNilai->parameter_penilaian->nama_parameter }}, meliputi:
                                {{-- {!! $pembimbing2PresentasiNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                                @php
                                    $deskripsi = $pembimbing2PresentasiNilai->parameter_penilaian->deskripsi_parameter;
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
                                {{ $pembimbing2PresentasiNilai->parameter_penilaian->persentase }}%
                            </td>
                            <td class="default-border text-center" style="background-color: #CFE2F3;">
                                {{ $pembimbing2PresentasiNilai->nilai }}
                            </td>
                            <td class="default-border text-center">
                                @php
                                    $bobot = $pembimbing2PresentasiNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing2PresentasiNilai->nilai;
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
                                $totalPersentasePresentasiPembimbing2 = 0;
                                for ($i = 0; $i < count($pembimbing2PresentasiNilais); $i++) {
                                    $totalPersentasePresentasiPembimbing2 +=
                                        $pembimbing2PresentasiNilais[$i]->parameter_penilaian->persentase;
                                }
                                echo $totalPersentasePresentasiPembimbing2;
                            @endphp
                            %
                        </td>
                        <td style="background-color: black">
                        </td>
                        <td class="default-border text-center">
                            @php
                                $totalNilaiPresentasiPembimbing2 = 0;
                                foreach ($pembimbing2PresentasiNilais as $pembimbing2PresentasiNilai) {
                                    $bobot = $pembimbing2PresentasiNilai->parameter_penilaian->persentase;
                                    $nilai = $pembimbing2PresentasiNilai->nilai;
                                    $totalNilaiPresentasiPembimbing2 += $nilai * ($bobot / 100);
                                }
                                echo $totalNilaiPresentasiPembimbing2;
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>

            <table style="border-collapse: collapse; margin-top:30px; font-size:12pt;">
                <tr>
                    <td colspan="2" style="padding-bottom: 0.5rem;">
                        <b>4. Daftar Nilai</b>
                    </td>
                </tr>
                <thead class="default-border text-center">
                    <tr>
                        <th class="default-border" style="width:30%">
                            <b>Penilaian</b>
                        </th>
                        <th class="default-border" style="width:40%">
                            <b>Bobot (%)</b>
                        </th>
                        <th class="default-border" style="width:15%">
                            <b>Nilai<br>Terbobot</b>
                        </th>
                        <th class="default-border" style="width:15%">
                            <b>Nilai rata-rata</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                            Skripsi
                        </td>
                        <td class="default-border text-center">
                            50%
                        </td>
                        <td class="default-border text-center" style="font-weight: bold;">
                            {{ $totalNilaiSkripsiPembimbing2 * (50 / 100) }}
                        </td>
                        <td rowspan="3" class="default-border text-center" style="font-weight: bold;">
                            {{ $seminarSidang->total_nilai_pembimbing_2 }}
                        </td>
                    </tr>
                    <tr>
                        <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                            Artikel
                        </td>
                        <td class="default-border text-center">
                            30%
                        </td>
                        <td class="default-border text-center" style="font-weight: bold;">
                            {{ $totalNilaiArtikelPembimbing2 * (30 / 100) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                            Presentasi
                        </td>
                        <td class="default-border text-center">
                            20%
                        </td>
                        <td class="default-border text-center" style="font-weight: bold;">
                            {{ $totalNilaiPresentasiPembimbing2 * (20 / 100) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <table style="margin-top: 10px; padding-left:65%; float: right;">
                <tbody>
                    <tr>
                        <td>
                            Pontianak,
                            {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                            <br>Penguji
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td style="height:40px;" class="align-top">

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
                        style="font-size: 12pt; line-height: 1.25rem; padding-top:0.5rem; padding-bottom:0.5rem; border-top: 4px solid black;">
                        <b>FORM PENILAIAN SIDANG SARJANA</b>
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
                        Dosen Penguji
                    </td>
                    <td style="width: 2%">
                        :
                    </td>
                    <td>
                        {{ $seminarSidang->tugas_akhir->dosen_penguji_1->nama_dosen }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="border-collapse: collapse; margin-top:10px; font-size:12pt;">
            <tr>
                <td colspan="2" style="padding-bottom: 0.5rem;">
                    <b>1. Penilaian Skripsi</b>
                </td>
            </tr>
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:10%">
                        <b>No</b>
                    </th>
                    <th class="default-border" style="width:50%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Bobot<br>(%)</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1.5rem; width:10%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penguji1SkripsiNilais as $index => $penguji1SkripsiNilai)
                    <tr>
                        <td class="default-border text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="default-border" style="padding: 3px 0 3px 3px;">
                            {{ $penguji1SkripsiNilai->parameter_penilaian->nama_parameter }}, meliputi:
                            {{-- {!! $penguji1SkripsiNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                            @php
                                $deskripsi = $penguji1SkripsiNilai->parameter_penilaian->deskripsi_parameter;
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
                            {{ $penguji1SkripsiNilai->parameter_penilaian->persentase }}%
                        </td>
                        <td class="default-border text-center" style="background-color: #CFE2F3;">
                            {{ $penguji1SkripsiNilai->nilai }}
                        </td>
                        <td class="default-border text-center">
                            @php
                                $bobot = $penguji1SkripsiNilai->parameter_penilaian->persentase;
                                $nilai = $penguji1SkripsiNilai->nilai;
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
                            $totalPersentaseSkripsiPenguji1 = 0;
                            for ($i = 0; $i < count($penguji1SkripsiNilais); $i++) {
                                $totalPersentaseSkripsiPenguji1 +=
                                    $penguji1SkripsiNilais[$i]->parameter_penilaian->persentase;
                            }
                            echo $totalPersentaseSkripsiPenguji1;
                        @endphp
                        %
                    </td>
                    <td style="background-color: black">
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalNilaiSkripsiPenguji1 = 0;
                            foreach ($penguji1SkripsiNilais as $penguji1SkripsiNilai) {
                                $bobot = $penguji1SkripsiNilai->parameter_penilaian->persentase;
                                $nilai = $penguji1SkripsiNilai->nilai;
                                $totalNilaiSkripsiPenguji1 += $nilai * ($bobot / 100);
                            }
                            echo $totalNilaiSkripsiPenguji1;
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="page-break-after: always;"></div>
    <div class="content">
        <table style="border-collapse: collapse; margin-top:10px; font-size:12pt;">
            <tr>
                <td colspan="2" style="padding-bottom: 0.5rem;">
                    <b>2. Penilaian Artikel Ilmiah</b>
                </td>
            </tr>
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:10%">
                        <b>No</b>
                    </th>
                    <th class="default-border" style="width:50%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Bobot<br>(%)</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1.5rem; width:10%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penguji1ArtikelNilais as $index => $penguji1ArtikelNilai)
                    <tr>
                        <td class="default-border text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="default-border" style="padding: 3px 0 3px 3px;">
                            {{ $penguji1ArtikelNilai->parameter_penilaian->nama_parameter }}, meliputi:
                            {{-- {!! $penguji1ArtikelNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                            @php
                                $deskripsi = $penguji1ArtikelNilai->parameter_penilaian->deskripsi_parameter;
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
                            {{ $penguji1ArtikelNilai->parameter_penilaian->persentase }}%
                        </td>
                        <td class="default-border text-center" style="background-color: #CFE2F3;">
                            {{ $penguji1ArtikelNilai->nilai }}
                        </td>
                        <td class="default-border text-center">
                            @php
                                $bobot = $penguji1ArtikelNilai->parameter_penilaian->persentase;
                                $nilai = $penguji1ArtikelNilai->nilai;
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
                            $totalPersentaseArtikelPenguji1 = 0;
                            for ($i = 0; $i < count($penguji1ArtikelNilais); $i++) {
                                $totalPersentaseArtikelPenguji1 +=
                                    $penguji1ArtikelNilais[$i]->parameter_penilaian->persentase;
                            }
                            echo $totalPersentaseArtikelPenguji1;
                        @endphp
                        %
                    </td>
                    <td style="background-color: black">
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalNilaiArtikelPenguji1 = 0;
                            foreach ($penguji1ArtikelNilais as $penguji1ArtikelNilai) {
                                $bobot = $penguji1ArtikelNilai->parameter_penilaian->persentase;
                                $nilai = $penguji1ArtikelNilai->nilai;
                                $totalNilaiArtikelPenguji1 += $nilai * ($bobot / 100);
                            }
                            echo $totalNilaiArtikelPenguji1;
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>

        <table style="border-collapse: collapse; margin-top:30px; font-size:12pt;">
            <tr>
                <td colspan="2" style="padding-bottom: 0.5rem;">
                    <b>3. Penilaian Presentasi</b>
                </td>
            </tr>
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:10%">
                        <b>No</b>
                    </th>
                    <th class="default-border" style="width:50%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Bobot<br>(%)</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1.5rem; width:10%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penguji1PresentasiNilais as $index => $penguji1PresentasiNilai)
                    <tr>
                        <td class="default-border text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="default-border" style="padding: 3px 0 3px 3px;">
                            {{ $penguji1PresentasiNilai->parameter_penilaian->nama_parameter }}, meliputi:
                            {{-- {!! $penguji1PresentasiNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                            @php
                                $deskripsi = $penguji1PresentasiNilai->parameter_penilaian->deskripsi_parameter;
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
                            {{ $penguji1PresentasiNilai->parameter_penilaian->persentase }}%
                        </td>
                        <td class="default-border text-center" style="background-color: #CFE2F3;">
                            {{ $penguji1PresentasiNilai->nilai }}
                        </td>
                        <td class="default-border text-center">
                            @php
                                $bobot = $penguji1PresentasiNilai->parameter_penilaian->persentase;
                                $nilai = $penguji1PresentasiNilai->nilai;
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
                            $totalPersentasePresentasiPenguji1 = 0;
                            for ($i = 0; $i < count($penguji1PresentasiNilais); $i++) {
                                $totalPersentasePresentasiPenguji1 +=
                                    $penguji1PresentasiNilais[$i]->parameter_penilaian->persentase;
                            }
                            echo $totalPersentasePresentasiPenguji1;
                        @endphp
                        %
                    </td>
                    <td style="background-color: black">
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalNilaiPresentasiPenguji1 = 0;
                            foreach ($penguji1PresentasiNilais as $penguji1PresentasiNilai) {
                                $bobot = $penguji1PresentasiNilai->parameter_penilaian->persentase;
                                $nilai = $penguji1PresentasiNilai->nilai;
                                $totalNilaiPresentasiPenguji1 += $nilai * ($bobot / 100);
                            }
                            echo $totalNilaiPresentasiPenguji1;
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>

        <table style="border-collapse: collapse; margin-top:30px; font-size:12pt;">
            <tr>
                <td colspan="2" style="padding-bottom: 0.5rem;">
                    <b>4. Daftar Nilai</b>
                </td>
            </tr>
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:30%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:40%">
                        <b>Bobot (%)</b>
                    </th>
                    <th class="default-border" style="width:15%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                    <th class="default-border" style="width:15%">
                        <b>Nilai rata-rata</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                        Skripsi
                    </td>
                    <td class="default-border text-center">
                        50%
                    </td>
                    <td class="default-border text-center" style="font-weight: bold;">
                        {{ $totalNilaiSkripsiPenguji1 * (50 / 100) }}
                    </td>
                    <td rowspan="3" class="default-border text-center" style="font-weight: bold;">
                        {{ $seminarSidang->total_nilai_penguji_1 }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                        Artikel
                    </td>
                    <td class="default-border text-center">
                        30%
                    </td>
                    <td class="default-border text-center" style="font-weight: bold;">
                        {{ $totalNilaiArtikelPenguji1 * (30 / 100) }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                        Presentasi
                    </td>
                    <td class="default-border text-center">
                        20%
                    </td>
                    <td class="default-border text-center" style="font-weight: bold;">
                        {{ $totalNilaiPresentasiPenguji1 * (20 / 100) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 10px; padding-left:65%; float: right;">
            <tbody>
                <tr>
                    <td>
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                        <br>Penguji
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td style="height:40px;" class="align-top">

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
                        style="font-size: 12pt; line-height: 1.25rem; padding-top:0.5rem; padding-bottom:0.5rem; border-top: 4px solid black;">
                        <b>FORM PENILAIAN SIDANG SARJANA</b>
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
                        Dosen Penguji
                    </td>
                    <td style="width: 2%">
                        :
                    </td>
                    <td>
                        {{ $seminarSidang->tugas_akhir->dosen_penguji_2->nama_dosen }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="border-collapse: collapse; margin-top:10px; font-size:12pt;">
            <tr>
                <td colspan="2" style="padding-bottom: 0.5rem;">
                    <b>1. Penilaian Skripsi</b>
                </td>
            </tr>
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:10%">
                        <b>No</b>
                    </th>
                    <th class="default-border" style="width:50%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Bobot<br>(%)</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1.5rem; width:10%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penguji2SkripsiNilais as $index => $penguji2SkripsiNilai)
                    <tr>
                        <td class="default-border text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="default-border" style="padding: 3px 0 3px 3px;">
                            {{ $penguji2SkripsiNilai->parameter_penilaian->nama_parameter }}, meliputi:
                            {{-- {!! $penguji2SkripsiNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                            @php
                                $deskripsi = $penguji2SkripsiNilai->parameter_penilaian->deskripsi_parameter;
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
                            {{ $penguji2SkripsiNilai->parameter_penilaian->persentase }}%
                        </td>
                        <td class="default-border text-center" style="background-color: #CFE2F3;">
                            {{ $penguji2SkripsiNilai->nilai }}
                        </td>
                        <td class="default-border text-center">
                            @php
                                $bobot = $penguji2SkripsiNilai->parameter_penilaian->persentase;
                                $nilai = $penguji2SkripsiNilai->nilai;
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
                            $totalPersentaseSkripsiPenguji2 = 0;
                            for ($i = 0; $i < count($penguji2SkripsiNilais); $i++) {
                                $totalPersentaseSkripsiPenguji2 +=
                                    $penguji2SkripsiNilais[$i]->parameter_penilaian->persentase;
                            }
                            echo $totalPersentaseSkripsiPenguji2;
                        @endphp
                        %
                    </td>
                    <td style="background-color: black">
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalNilaiSkripsiPenguji2 = 0;
                            foreach ($penguji2SkripsiNilais as $penguji2SkripsiNilai) {
                                $bobot = $penguji2SkripsiNilai->parameter_penilaian->persentase;
                                $nilai = $penguji2SkripsiNilai->nilai;
                                $totalNilaiSkripsiPenguji2 += $nilai * ($bobot / 100);
                            }
                            echo $totalNilaiSkripsiPenguji2;
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div style="page-break-after: always;"></div>
    <div class="content">
        <table style="border-collapse: collapse; margin-top:10px; font-size:12pt;">
            <tr>
                <td colspan="2" style="padding-bottom: 0.5rem;">
                    <b>2. Penilaian Artikel Ilmiah</b>
                </td>
            </tr>
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:10%">
                        <b>No</b>
                    </th>
                    <th class="default-border" style="width:50%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Bobot<br>(%)</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1.5rem; width:10%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penguji2ArtikelNilais as $index => $penguji2ArtikelNilai)
                    <tr>
                        <td class="default-border text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="default-border" style="padding: 3px 0 3px 3px;">
                            {{ $penguji2ArtikelNilai->parameter_penilaian->nama_parameter }}, meliputi:
                            {{-- {!! $penguji2ArtikelNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                            @php
                                $deskripsi = $penguji2ArtikelNilai->parameter_penilaian->deskripsi_parameter;
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
                            {{ $penguji2ArtikelNilai->parameter_penilaian->persentase }}%
                        </td>
                        <td class="default-border text-center" style="background-color: #CFE2F3;">
                            {{ $penguji2ArtikelNilai->nilai }}
                        </td>
                        <td class="default-border text-center">
                            @php
                                $bobot = $penguji2ArtikelNilai->parameter_penilaian->persentase;
                                $nilai = $penguji2ArtikelNilai->nilai;
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
                            $totalPersentaseArtikelPenguji1 = 0;
                            for ($i = 0; $i < count($penguji2ArtikelNilais); $i++) {
                                $totalPersentaseArtikelPenguji1 +=
                                    $penguji2ArtikelNilais[$i]->parameter_penilaian->persentase;
                            }
                            echo $totalPersentaseArtikelPenguji1;
                        @endphp
                        %
                    </td>
                    <td style="background-color: black">
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalNilaiArtikelPenguji2 = 0;
                            foreach ($penguji2ArtikelNilais as $penguji2ArtikelNilai) {
                                $bobot = $penguji2ArtikelNilai->parameter_penilaian->persentase;
                                $nilai = $penguji2ArtikelNilai->nilai;
                                $totalNilaiArtikelPenguji2 += $nilai * ($bobot / 100);
                            }
                            echo $totalNilaiArtikelPenguji2;
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>

        <table style="border-collapse: collapse; margin-top:30px; font-size:12pt;">
            <tr>
                <td colspan="2" style="padding-bottom: 0.5rem;">
                    <b>3. Penilaian Presentasi</b>
                </td>
            </tr>
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:10%">
                        <b>No</b>
                    </th>
                    <th class="default-border" style="width:50%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Bobot<br>(%)</b>
                    </th>
                    <th class="default-border" style="width:10%">
                        <b>Nilai</b>
                    </th>
                    <th class="default-border" style="line-height: 1.5rem; width:10%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penguji2PresentasiNilais as $index => $penguji2PresentasiNilai)
                    <tr>
                        <td class="default-border text-center">
                            {{ $index + 1 }}
                        </td>
                        <td class="default-border" style="padding: 3px 0 3px 3px;">
                            {{ $penguji2PresentasiNilai->parameter_penilaian->nama_parameter }}, meliputi:
                            {{-- {!! $penguji2PresentasiNilai->parameter_penilaian->deskripsi_parameter !!} --}}
                            @php
                                $deskripsi = $penguji2PresentasiNilai->parameter_penilaian->deskripsi_parameter;
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
                            {{ $penguji2PresentasiNilai->parameter_penilaian->persentase }}%
                        </td>
                        <td class="default-border text-center" style="background-color: #CFE2F3;">
                            {{ $penguji2PresentasiNilai->nilai }}
                        </td>
                        <td class="default-border text-center">
                            @php
                                $bobot = $penguji2PresentasiNilai->parameter_penilaian->persentase;
                                $nilai = $penguji2PresentasiNilai->nilai;
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
                            $totalPersentasePresentasiPenguji2 = 0;
                            for ($i = 0; $i < count($penguji2PresentasiNilais); $i++) {
                                $totalPersentasePresentasiPenguji2 +=
                                    $penguji2PresentasiNilais[$i]->parameter_penilaian->persentase;
                            }
                            echo $totalPersentasePresentasiPenguji2;
                        @endphp
                        %
                    </td>
                    <td style="background-color: black">
                    </td>
                    <td class="default-border text-center">
                        @php
                            $totalNilaiPresentasiPenguji2 = 0;
                            foreach ($penguji2PresentasiNilais as $penguji2PresentasiNilai) {
                                $bobot = $penguji2PresentasiNilai->parameter_penilaian->persentase;
                                $nilai = $penguji2PresentasiNilai->nilai;
                                $totalNilaiPresentasiPenguji2 += $nilai * ($bobot / 100);
                            }
                            echo $totalNilaiPresentasiPenguji2;
                        @endphp
                    </td>
                </tr>
            </tfoot>
        </table>

        <table style="border-collapse: collapse; margin-top:30px; font-size:12pt;">
            <tr>
                <td colspan="2" style="padding-bottom: 0.5rem;">
                    <b>4. Daftar Nilai</b>
                </td>
            </tr>
            <thead class="default-border text-center">
                <tr>
                    <th class="default-border" style="width:30%">
                        <b>Penilaian</b>
                    </th>
                    <th class="default-border" style="width:40%">
                        <b>Bobot (%)</b>
                    </th>
                    <th class="default-border" style="width:15%">
                        <b>Nilai<br>Terbobot</b>
                    </th>
                    <th class="default-border" style="width:15%">
                        <b>Nilai rata-rata</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                        Skripsi
                    </td>
                    <td class="default-border text-center">
                        50%
                    </td>
                    <td class="default-border text-center" style="font-weight: bold;">
                        {{ $totalNilaiSkripsiPenguji2 * (50 / 100) }}
                    </td>
                    <td rowspan="3" class="default-border text-center" style="font-weight: bold;">
                        {{ $seminarSidang->total_nilai_penguji_2 }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                        Artikel
                    </td>
                    <td class="default-border text-center">
                        30%
                    </td>
                    <td class="default-border text-center" style="font-weight: bold;">
                        {{ $totalNilaiArtikelPenguji2 * (30 / 100) }}
                    </td>
                </tr>
                <tr>
                    <td class="default-border text-center" style="padding: 5px 0 5px 0px;">
                        Presentasi
                    </td>
                    <td class="default-border text-center">
                        20%
                    </td>
                    <td class="default-border text-center" style="font-weight: bold;">
                        {{ $totalNilaiPresentasiPenguji2 * (20 / 100) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="margin-top: 10px; padding-left:65%; float: right;">
            <tbody>
                <tr>
                    <td>
                        Pontianak,
                        {{ Carbon\Carbon::parse($seminarSidang->tanggal_pelaksanaan)->translatedFormat('d F Y') }}
                        <br>Penguji
                    </td>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
                <tr>
                    <td style="height:40px;" class="align-top">

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
