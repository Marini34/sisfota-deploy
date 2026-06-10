<x-app-layout>
    @section('title', 'Edit Penilaian Seminar Proposal')
    @section('head')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <style>
            .trix-editor {
                width: 100%;
                min-height: 6.2rem;
            }

            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
            }

            @media (max-width: 640px) {
                .trix-editor {
                    width: 100%;
                    min-height: 18rem;
                }
            }
        </style>
    @endsection
    <x-main-card>
        @if ($parameters->count() && $totalPersentase == 100)
            <h1 class="text-lg font-bold mb-5">@if ($semhas) Riwayat @else Edit @endif Penilaian Seminar Proposal {{ $sempro->mahasiswa->nama_lengkap }}
            </h1>
            <form action="{{ route('dosen.sempro.penilaian.edit.store', $sempro->slug) }}" method="post">
                @csrf
                <div class="overflow-x-auto mb-3">
                    <table id="myTable" class="table table-striped table-hover table-bordered">
                        <thead class="text-base uppercase text-slate-800">
                            <tr class="bg-slate-300 rounded-2xl text-center">
                                <th>Parameter</th>
                                <th>Deskripsi</th>
                                <th>Persentase</th>
                                <th>Nilai</th>
                                <th>Nilai Terbobot</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($parameters as $index => $parameter)
                                <tr>
                                    <td>{{ $parameter->nama_parameter }}</td>
                                    <td>
                                        @php
                                            $deskripsi = $parameter->deskripsi_parameter;
                                            if (strpos($deskripsi, '<ol>') !== false) {
                                                $deskripsi = str_replace(
                                                    '<ol>',
                                                    '<ol class="list-decimal pl-7">',
                                                    $deskripsi,
                                                );
                                            }
                                            if (strpos($deskripsi, '<ul>') !== false) {
                                                $deskripsi = str_replace(
                                                    '<ul>',
                                                    '<ul class="list-disc pl-7">',
                                                    $deskripsi,
                                                );
                                            }
                                            echo $deskripsi;
                                        @endphp
                                    </td>
                                    <td class="text-center" id="persentase[{{ $index }}]">
                                        {{ $parameter->persentase }}%
                                    </td>
                                    <td class="text-center">
                                        <label for="nilai[{{ $index }}]" class="hidden">nilai</label>
                                        <input required type="number" min="0" max="100"
                                            name="nilai[{{ $index }}]" id="nilai[{{ $index }}]"
                                            value="{{ isset($nilais[$index]) ? old('nilai[' . $index . ']', $nilais[$index]->nilai) : '' }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 "
                                            oninput="calculateResult({{ $index }})"
                                            @if ($semhas) disabled @endif>
                                        @error('nilai[{{ $index }}]')
                                            <div>
                                                <p class="text-red-500 text-base">{{ $message }}</p>
                                            </div>
                                        @enderror
                                    </td>
                                    <td id="nilaiTerbobot[{{ $index }}]" class="text-center">
                                        <?php
                                        $nilai = isset($nilais[$index]) ? $nilais[$index]->nilai : 0;
                                        $persentase = $parameter->persentase;
                                        echo $nilai * ($persentase / 100);
                                        ?>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-left font-bold">Total Nilai Terbobot</td>
                                <td id="totalNilaiTerbobot" class="text-center font-bold">
                                    <?php
                                    $totalNilaiTerbobot = 0;
                                    foreach ($parameters as $index => $parameter) {
                                        $persentase = $parameter->persentase;
                                        $nilai = $nilais[$index]->nilai ?? 0; // Ambil nilai jika sudah ada, jika tidak, gunakan nilai default 0
                                        $nilaiTerbobot = ($persentase / 100) * $nilai; // Hitung nilai terbobot
                                        $totalNilaiTerbobot += $nilaiTerbobot;
                                    }
                                    echo $totalNilaiTerbobot;
                                    ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mb-5">
                    <label for="catatan" class="font-bold">Catatan Penilaian</label>
                    <div class="border border-slate-300 rounded-md">
                        <input id="catatan" type="hidden" name="catatan" @if ($semhas) disabled @endif>
                        @if ($semhas) 
        
                            @php
                                if ($sempro->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                                    $catatan = $sempro->catatan_ta_pembimbing_1;
                                } elseif ($sempro->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                                    $catatan = $sempro->catatan_ta_pembimbing_2;
                                } elseif ($sempro->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                                    $catatan = $sempro->catatan_ta_penguji_1;
                                } elseif ($sempro->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                                    $catatan = $sempro->catatan_ta_penguji_2;
                                }
                                if (strpos($catatan, '<ol>') !== false) {
                                    $catatan = str_replace('<ol>', '<ol class="list-decimal pl-7">', $catatan);
                                }
                                if (strpos($catatan, '<ul>') !== false) {
                                    $catatan = str_replace('<ul>', '<ul class="list-disc pl-7">', $catatan);
                                }
                                echo $catatan;
                            @endphp
                        
                        @else
                        <trix-editor input="catatan" class="trix-editor">
                            @php
                                if ($sempro->tugas_akhir->dosen_pembimbing_1_id == $dosenId) {
                                    $catatan = $sempro->catatan_ta_pembimbing_1;
                                } elseif ($sempro->tugas_akhir->dosen_pembimbing_2_id == $dosenId) {
                                    $catatan = $sempro->catatan_ta_pembimbing_2;
                                } elseif ($sempro->tugas_akhir->dosen_penguji_1_id == $dosenId) {
                                    $catatan = $sempro->catatan_ta_penguji_1;
                                } elseif ($sempro->tugas_akhir->dosen_penguji_2_id == $dosenId) {
                                    $catatan = $sempro->catatan_ta_penguji_2;
                                }
                                if (strpos($catatan, '<ol>') !== false) {
                                    $catatan = str_replace('<ol>', '<ol class="list-decimal pl-7">', $catatan);
                                }
                                if (strpos($catatan, '<ul>') !== false) {
                                    $catatan = str_replace('<ul>', '<ul class="list-disc pl-7">', $catatan);
                                }
                                echo $catatan;
                            @endphp
                        </trix-editor>
                        @endif
                    </div>
                    @error('catatan')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                    @enderror
                </div>
                    @if ($semhas)
                    <a href="/dosen/penilaian-sempro/riwayat" type="button"
                        class="cursor-pointer w-full text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                        Kembali
                    </a>
                    @else  
                    <div class="grid grid-cols-2 gap-10">
                    <a href="/dosen/penilaian-sempro/riwayat" type="button"
                        class="cursor-pointer text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                        Kembali
                    </a>

                    <button type="submit"
                        class="cursor-pointer text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                        Edit
                    </button>
                    </div>
                    @endif
            </form>
        @else
            <div id="alert-additional-content-1"
                class="p-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
                role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <h3 class="text-lg font-medium">Belum bisa melakukan penilaian Seminar Proposal</h3>
                </div>

                <div class="mt-2 mb-4 text-sm">
                    Bobot persentase parameter penilaian Seminar Proposal Belum mencapai 100%,
                    hubungi admin untuk melakukan konfigurasi persentase parameter penilaian Seminar Proposal.
                </div>

                <div class="flex">
                    <a href="/dosen/penilaian-sempro/riwayat" type="button"
                        class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Kembali
                    </a>
                </div>
            </div>
        @endif
    </x-main-card>
    @section('script')
        <script>
            // open dropdown menu
            dropDownSempro()

            function calculateResult(index) {
                // Ambil nilai persentase dari elemen dengan id persentase[index]
                var persentase = parseFloat(document.getElementById('persentase[' + index + ']').textContent);

                // Ambil nilai input dari elemen dengan id nilai[index]
                var nilai = parseFloat(document.getElementById('nilai[' + index + ']').value);

                // Hitung hasil perkalian
                var hasil = (persentase / 100) * nilai;

                // Periksa apakah hasil adalah NaN
                if (!isNaN(hasil)) {
                    // Jika hasilnya bukan NaN, tampilkan hasil pada elemen dengan id nilaiTerbobot[index]
                    document.getElementById('nilaiTerbobot[' + index + ']').textContent = hasil.toFixed(2);

                    // Hitung total nilai terbobot
                    var totalNilaiTerbobot = 0;
                    var jumlahParameter = <?php echo $parameters->count(); ?>; // Ambil jumlah parameter dari PHP
                    for (var i = 0; i < jumlahParameter; i++) {
                        var nilaiTerbobot = parseFloat(document.getElementById('nilaiTerbobot[' + i + ']').textContent);
                        totalNilaiTerbobot += isNaN(nilaiTerbobot) ? 0 :
                            nilaiTerbobot; // Jika nilaiTerbobot adalah NaN, jangan tambahkan ke total
                    }
                    document.getElementById('totalNilaiTerbobot').textContent = totalNilaiTerbobot.toFixed(2);
                } else {
                    document.getElementById('nilaiTerbobot[' + index + ']').textContent = '';

                    // Hitung total nilai terbobot ulang
                    var totalNilaiTerbobot = 0;
                    var jumlahParameter = <?php echo $parameters->count(); ?>; // Ambil jumlah parameter dari PHP
                    for (var i = 0; i < jumlahParameter; i++) {
                        var nilaiTerbobot = parseFloat(document.getElementById('nilaiTerbobot[' + i + ']').textContent);
                        totalNilaiTerbobot += isNaN(nilaiTerbobot) ? 0 :
                            nilaiTerbobot; // Jika nilaiTerbobot adalah NaN, jangan tambahkan ke total
                    }

                    // Tampilkan total nilai terbobot
                    document.getElementById('totalNilaiTerbobot').textContent = totalNilaiTerbobot.toFixed(2);
                }
            }
        </script>
    @endsection
</x-app-layout>
