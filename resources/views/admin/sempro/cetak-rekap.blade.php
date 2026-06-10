<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Rekapitulasi Hasil Seminar Proposal
        {{ Carbon\Carbon::parse($sempros[0]->tanggal_pelaksanaan)->translatedFormat('F Y') }}</title>

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

        .mainTable {
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 18px;
            text-align: left;
        }

        thead tr {
            background-color: #f2f2f2;
            color: #333;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        th {
            background-color: #a3c4e4;
        }

        .dosen-list {
            padding-left: 10px;
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
        <hr style="border: 1px solid black">

        <table style="width: 100%">
            <tbody>
                <tr>
                    <td class="text-center" style="font-size: 13pt; line-height: 1.25rem; padding-bottom:1.25rem;">
                        <b>HASIL SEMINAR PROPOSAL PERIODE
                            {{ Carbon\Carbon::parse($sempros[0]->tanggal_pelaksanaan)->translatedFormat('F Y') }} </b>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="mainTable">
            <thead>
                <tr>
                    <th style="padding: 12px; border: 0.5px solid black;">NIM</th>
                    <th style="padding: 12px; border: 0.5px solid black;">Nama</th>
                    <th style="padding: 12px; border: 0.5px solid black;">Nilai</th>
                    <th style="padding: 12px; border: 0.5px solid black;">Hasil</th>
                    <th style="padding: 12px; border: 0.5px solid black;">Dosen Pembimbing</th>
                    <th style="padding: 12px; border: 0.5px solid black;">Dosen Penguji</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sempros as $sempro)
                    <tr>
                        <td style="padding: 12px; border: 0.5px solid black;">{{ $sempro->mahasiswa->nim }}</td>
                        <td style="padding: 12px; border: 0.5px solid black;">{{ $sempro->mahasiswa->nama_lengkap }}
                        </td>
                        <td style="padding: 12px; border: 0.5px solid black;">{{ $sempro->total_nilai_akhir }}</td>
                        <td style="padding: 12px; border: 0.5px solid black;">{{ $sempro->status_kelulusan }}</td>
                        <td style="padding: 12px; border: 0.5px solid black;">
                            1. {{ $sempro->tugas_akhir->dosen_pembimbing_1->nama_dosen }}<br>
                            2. {{ $sempro->tugas_akhir->dosen_pembimbing_2->nama_dosen }}
                        </td>
                        <td style="padding: 12px; border: 1px solid black;">
                            1. {{ $sempro->tugas_akhir->dosen_penguji_1->nama_dosen }}<br>
                            2. {{ $sempro->tugas_akhir->dosen_penguji_2->nama_dosen }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
