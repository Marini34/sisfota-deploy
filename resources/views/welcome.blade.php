<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Berita Acara</title>
    <link rel="stylesheet" href="">
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background-color: #444444;
            font-size: 12pt;
        }

        table {
            width: 100%;
        }

        .content {
            background-color: #fff;
            height: 297mm;
            width: 210mm;
            margin-left: auto;
            margin-right: auto;
            color: black;
            padding: 0.47cm 1.9cm 0.47cm 1.9cm
        }

        pre {
            font-size: 12;
            color: black;
            margin: 0;
            font-family: "Times New Roman", Times, serif;
        }
    </style>
</head>

<body> 
    <div class="content" style="page-break-after: always;">
        <table style="width: 100%">
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
                <tr class="">
                    <td class="text-center font-bold pt-5 leading-6" style="font-size: 14pt">
                        BERITA ACARA<br>SEMINAR PROPOSAL TUGAS AKHIR
                    </td>
                </tr>

                <tr class="">
                    <td class="pt-4 pb-2">
                        Berdasarkan hasil Seminar Proposal Tugas Akhir yang telah dilaksanakan oleh:
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <tbody>
                <tr class="">
                    <td style="width: 10%" class="pb-2">
                        Nama
                    </td>
                    <td class=" pb-2" style="width: 2%">
                        :
                    </td>
                    <td class=" pb-2">
                        {{ $seminarSidang->mahasiswa->nama_lengkap }}
                    </td>
                </tr>
                <tr class="">
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
                <tr class="">
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
                        pada pukul <b>{{ Carbon\Carbon::parse($seminarSidang->jam_pelaksanaan)->format('h:i') }} s.d.
                            {{ Carbon\Carbon::parse($seminarSidang->jam_pelaksanaan)->addHours(2)->format('h:i') }}</b>
                        WIB secara daring
                        pada FMIPA Universitas Tanjungpura.
                    </td>
                </tr>
                <tr>
                    <td class="" style="width: 20%">
                        Pembimbing
                    </td>
                    <td class="" style="width: 2%">
                        :
                    </td>
                    <td class="">
                        1. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}
                    </td>
                </tr>
                <tr>
                    <td class="" style="width: 20%">

                    </td>
                    <td class="" style="width: 2%">

                    </td>
                    <td class="">
                        2. {{ $seminarSidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        Adapun Proposal Penelitian Tugas Akhir tersebut : <b
                            class="lowercase">{{ $seminarSidang->total_nilai_akhir < $passingGrade ? 'DITOLAK' : 'DITERIMA' }}.</b>
                        <br>
                        dengan catatan : **Lampiran
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">
                        Pontianak, {{ Carbon\Carbon::now()->translatedFormat('d F Y') }}
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
                    <td class="align-top " style=" height: 70px">
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
                    <td colspan="2" class="" style="font-size: 10pt;">
                        **) : Notulensi Seminar Proposal TA
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
