<x-app-layout>
    @section('title', 'Penilaian Sidang Akhir')
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
    @if (($skripsiParameters->count() && $totalPersentaseSkripsi == 100) && ($artikelParameters->count() && $totalPersentaseArtikel == 100) && ($presentasiParameters->count() && $totalPersentasePresentasi == 100))
        <x-main-card>
            <h1 class="text-2xl font-bold mb-5">Penilaian Sidang Akhir {{ $sidang->mahasiswa->nama_lengkap }}</h1>
            <br>
            <form action="{{ route('dosen.sidang.penilaian.store', $sidang->slug) }}" method="post">
                @csrf
                <h2 class="text-lg font-bold mb-2">Penilaian Skripsi</h2>
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
                            @foreach ($skripsiParameters as $index => $skripsiParameter)
                                <tr>
                                    <td>{{ $skripsiParameter->nama_parameter }}</td>
                                    <td>
                                        @php
                                            $deskripsi = $skripsiParameter->deskripsi_parameter;
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
                                    <td class="text-center" id="persentaseSkripsi[{{ $index }}]">
                                        {{ $skripsiParameter->persentase }}%</td>
                                    <td class="text-center">
                                        <label for="nilaiSkripsi[{{ $index }}]" class="hidden">nilai</label>
                                        <input required type="number" min="0" max="100"
                                            name="nilaiSkripsi[{{ $index }}]" id="nilaiSkripsi[{{ $index }}]"
                                            class="input-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 "
                                            oninput="calculateSkripsiResult({{ $index }})">
                                        @error('nilaiSkripsi[{{ $index }}]')
                                            <div>
                                                <p class="text-red-500 text-base">{{ $message }}</p>
                                            </div>
                                        @enderror
                                    </td>
                                    <td id="nilaiSkripsiTerbobot[{{ $index }}]" class="text-center">
                                        <!-- Nilai terbobot akan diperbarui secara dinamis -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-left font-bold">Total Nilai Terbobot</td>
                                <td id="totalNilaiSkripsiTerbobot" class="text-center font-bold">
                                    <!-- Total nilai terbobot akan diperbarui secara dinamis -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <br>

                <h2 class="text-lg font-bold mb-2">Penilaian Artikel</h2>
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
                            @foreach ($artikelParameters as $index => $artikelParameter)
                                <tr>
                                    <td>{{ $artikelParameter->nama_parameter }}</td>
                                    <td>
                                        @php
                                            $deskripsi = $artikelParameter->deskripsi_parameter;
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
                                    <td class="text-center" id="persentaseArtikel[{{ $index }}]">
                                        {{ $artikelParameter->persentase }}%</td>
                                    <td class="text-center">
                                        <label for="nilaiArtikel[{{ $index }}]" class="hidden">nilai</label>
                                        <input required type="number" min="0" max="100"
                                            name="nilaiArtikel[{{ $index }}]" id="nilaiArtikel[{{ $index }}]"
                                            class="input-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 "
                                            oninput="calculateArtikelResult({{ $index }})">
                                        @error('nilaiArtikel[{{ $index }}]')
                                            <div>
                                                <p class="text-red-500 text-base">{{ $message }}</p>
                                            </div>
                                        @enderror
                                    </td>
                                    <td id="nilaiArtikelTerbobot[{{ $index }}]" class="text-center">
                                        <!-- Nilai terbobot akan diperbarui secara dinamis -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-left font-bold">Total Nilai Terbobot</td>
                                <td id="totalNilaiArtikelTerbobot" class="text-center font-bold">
                                    <!-- Total nilai terbobot akan diperbarui secara dinamis -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <br>

                <h2 class="text-lg font-bold mb-2">Penilaian Presentasi</h2>
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
                            @foreach ($presentasiParameters as $index => $presentasiParameter)
                                <tr>
                                    <td>{{ $presentasiParameter->nama_parameter }}</td>
                                    <td>
                                        @php
                                            $deskripsi = $presentasiParameter->deskripsi_parameter;
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
                                    <td class="text-center" id="persentasePresentasi[{{ $index }}]">
                                        {{ $presentasiParameter->persentase }}%</td>
                                    <td class="text-center">
                                        <label for="nilaiPresentasi[{{ $index }}]" class="hidden">nilai</label>
                                        <input required type="number" min="0" max="100"
                                            name="nilaiPresentasi[{{ $index }}]" id="nilaiPresentasi[{{ $index }}]"
                                            class="input-field bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 "
                                            oninput="calculatePresentasiResult({{ $index }})">
                                        @error('nilaiPresentasi[{{ $index }}]')
                                            <div>
                                                <p class="text-red-500 text-base">{{ $message }}</p>
                                            </div>
                                        @enderror
                                    </td>
                                    <td id="nilaiPresentasiTerbobot[{{ $index }}]" class="text-center">
                                        <!-- Nilai terbobot akan diperbarui secara dinamis -->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-left font-bold">Total Nilai Terbobot</td>
                                <td id="totalNilaiPresentasiTerbobot" class="text-center font-bold">
                                    <!-- Total nilai terbobot akan diperbarui secara dinamis -->
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="mb-5">
                    <p class="font-bold text-xl">Total nilai keseluruhan = <span id="totalNilaiKeseluruhan">0</span></p>
                    <label for="totalNilaiKeseluruhan" class="hidden">nilai</label>
                    <input type="hidden" name="totalNilaiKeseluruhan" id="inputTotalNilaiKeseluruhan">
                </div>
                <div class="mb-5">
                    <label for="catatan" class="font-bold">Catatan Penilaian</label>
                    <div class="border border-slate-300 rounded-md">
                        <input id="catatan" type="hidden" name="catatan">
                        <trix-editor input="catatan" class="trix-editor"
                            placeholder="Masukkan catatan penilaian..."></trix-editor>
                    </div>
                    @error('catatan')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-10">
                    <a href="/dosen/penilaian-sidang" type="button"
                        class="cursor-pointer text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                        Kembali
                    </a>
                    <button type="submit"
                        class="cursor-pointer text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                        Simpan
                    </button>
                </div>
            </form>
        </x-main-card>
    @else
        <x-main-card>
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
                    <h3 class="text-lg font-medium">Belum bisa melakukan penilaian Sidang Akhir</h3>
                </div>

                <div class="mt-2 mb-4 text-sm">
                    Bobot persentase parameter penilaian Sidang Akhir Belum mencapai 100%,
                    hubungi admin untuk melakukan konfigurasi persentase parameter penilaian Seminar Akhir.
                </div>

                <div class="flex">
                    <a href="/dosen/penilaian-sidang" type="button"
                        class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Kembali
                    </a>
                </div>
            </div>
        </x-main-card>
    @endif
    @section('script')
        <script>
            dropDownSidang()
        </script>
        <script>
            function updateTotalNilaiKeseluruhan() {
                var totalNilaiSkripsiTerbobot = parseFloat(document.getElementById('totalNilaiSkripsiTerbobot').textContent) || 0;
                var totalNilaiArtikelTerbobot = parseFloat(document.getElementById('totalNilaiArtikelTerbobot').textContent) || 0;
                var totalNilaiPresentasiTerbobot = parseFloat(document.getElementById('totalNilaiPresentasiTerbobot').textContent) || 0;

                var totalNilaiKeseluruhan = (totalNilaiSkripsiTerbobot*(50/100)) + (totalNilaiArtikelTerbobot*(30/100)) + (totalNilaiPresentasiTerbobot*(20/100));
                document.getElementById('totalNilaiKeseluruhan').textContent = totalNilaiKeseluruhan.toFixed(2);
                document.getElementById('inputTotalNilaiKeseluruhan').value = totalNilaiKeseluruhan.toFixed(2);
            }
            
            function calculateSkripsiResult(index) {
                // Ambil nilai persentase dari elemen dengan id persentase[index]
                var persentaseSkripsi = parseFloat(document.getElementById('persentaseSkripsi[' + index + ']').textContent);

                // Ambil nilai input dari elemen dengan id nilai[index]
                var nilaiSkripsi = parseFloat(document.getElementById('nilaiSkripsi[' + index + ']').value);

                // Hitung hasil perkalian
                var hasilSkripsi = (persentaseSkripsi / 100) * nilaiSkripsi;

                // Periksa apakah hasil adalah NaN
                if (!isNaN(hasilSkripsi)) {
                    // Jika hasilnya bukan NaN, tampilkan hasil pada elemen dengan id nilaiTerbobot[index]
                    document.getElementById('nilaiSkripsiTerbobot[' + index + ']').textContent = hasilSkripsi.toFixed(2);

                    // Hitung total nilai terbobot
                    var totalNilaiSkripsiTerbobot = 0;
                    var jumlahParameterSkripsi = <?php echo $skripsiParameters->count(); ?>; // Ambil jumlah parameter dari PHP
                    for (var i = 0; i < jumlahParameterSkripsi; i++) {
                        var nilaiSkripsiTerbobot = parseFloat(document.getElementById('nilaiSkripsiTerbobot[' + i + ']').textContent);
                        totalNilaiSkripsiTerbobot += isNaN(nilaiSkripsiTerbobot) ? 0 :
                        nilaiSkripsiTerbobot; // Jika nilaiTerbobot adalah NaN, jangan tambahkan ke total
                    }
                    document.getElementById('totalNilaiSkripsiTerbobot').textContent = totalNilaiSkripsiTerbobot.toFixed(2);
                } else {
                    document.getElementById('nilaiSkripsiTerbobot[' + index + ']').textContent = '';

                    // Hitung total nilai terbobot ulang
                    var totalNilaiSkripsiTerbobot = 0;
                    var jumlahParameterSkripsi = <?php echo $skripsiParameters->count(); ?>; // Ambil jumlah parameter dari PHP
                    for (var i = 0; i < jumlahParameterSkripsi; i++) {
                        var nilaiSkripsiTerbobot = parseFloat(document.getElementById('nilaiSkripsiTerbobot[' + i + ']').textContent);
                        totalNilaiSkripsiTerbobot += isNaN(nilaiSkripsiTerbobot) ? 0 :
                        nilaiSkripsiTerbobot; // Jika nilaiTerbobot adalah NaN, jangan tambahkan ke total
                    }

                    // Tampilkan total nilai terbobot
                    document.getElementById('totalNilaiSkripsiTerbobot').textContent = totalNilaiSkripsiTerbobot.toFixed(2);
                }
                updateTotalNilaiKeseluruhan();
            }

            function calculateArtikelResult(index) {
                var persentaseArtikel = parseFloat(document.getElementById('persentaseArtikel[' + index + ']').textContent);
                var nilaiArtikel = parseFloat(document.getElementById('nilaiArtikel[' + index + ']').value);
                var hasilArtikel = (persentaseArtikel / 100) * nilaiArtikel;

                if (!isNaN(hasilArtikel)) {
                    document.getElementById('nilaiArtikelTerbobot[' + index + ']').textContent = hasilArtikel.toFixed(2);

                    var totalNilaiArtikelTerbobot = 0;
                    var jumlahParameterArtikel = <?php echo $artikelParameters->count(); ?>; // Ambil jumlah parameter dari PHP
                    for (var i = 0; i < jumlahParameterArtikel; i++) {
                        var nilaiArtikelTerbobot = parseFloat(document.getElementById('nilaiArtikelTerbobot[' + i + ']').textContent);
                        totalNilaiArtikelTerbobot += isNaN(nilaiArtikelTerbobot) ? 0 :
                        nilaiArtikelTerbobot;
                    }
                    document.getElementById('totalNilaiArtikelTerbobot').textContent = totalNilaiArtikelTerbobot.toFixed(2);
                } else {
                    document.getElementById('nilaiArtikelTerbobot[' + index + ']').textContent = '';

                    var totalNilaiArtikelTerbobot = 0;
                    var jumlahParameterArtikel = <?php echo $artikelParameters->count(); ?>; // Ambil jumlah parameter dari PHP
                    for (var i = 0; i < jumlahParameterArtikel; i++) {
                        var nilaiArtikelTerbobot = parseFloat(document.getElementById('nilaiArtikelTerbobot[' + i + ']').textContent);
                        totalNilaiArtikelTerbobot += isNaN(nilaiArtikelTerbobot) ? 0 :
                        nilaiArtikelTerbobot; 
                    }

                    document.getElementById('totalNilaiArtikelTerbobot').textContent = totalNilaiArtikelTerbobot.toFixed(2);
                }
                updateTotalNilaiKeseluruhan();
            }

            function calculatePresentasiResult(index) {
                var persentasePresentasi = parseFloat(document.getElementById('persentasePresentasi[' + index + ']').textContent);
                var nilaiPresentasi = parseFloat(document.getElementById('nilaiPresentasi[' + index + ']').value);
                var hasilPresentasi = (persentasePresentasi / 100) * nilaiPresentasi;

                if (!isNaN(hasilPresentasi)) {
                    document.getElementById('nilaiPresentasiTerbobot[' + index + ']').textContent = hasilPresentasi.toFixed(2);

                    var totalNilaiPresentasiTerbobot = 0;
                    var jumlahParameterPresentasi = <?php echo $presentasiParameters->count(); ?>; // Ambil jumlah parameter dari PHP
                    for (var i = 0; i < jumlahParameterPresentasi; i++) {
                        var nilaiPresentasiTerbobot = parseFloat(document.getElementById('nilaiPresentasiTerbobot[' + i + ']').textContent);
                        totalNilaiPresentasiTerbobot += isNaN(nilaiPresentasiTerbobot) ? 0 :
                        nilaiPresentasiTerbobot;
                    }
                    document.getElementById('totalNilaiPresentasiTerbobot').textContent = totalNilaiPresentasiTerbobot.toFixed(2);
                } else {
                    document.getElementById('nilaiPresentasiTerbobot[' + index + ']').textContent = '';

                    var totalNilaiPresentasiTerbobot = 0;
                    var jumlahParameterPresentasi = <?php echo $presentasiParameters->count(); ?>; // Ambil jumlah parameter dari PHP
                    for (var i = 0; i < jumlahParameterPresentasi; i++) {
                        var nilaiPresentasiTerbobot = parseFloat(document.getElementById('nilaiPresentasiTerbobot[' + i + ']').textContent);
                        totalNilaiPresentasiTerbobot += isNaN(nilaiPresentasiTerbobot) ? 0 :
                        nilaiPresentasiTerbobot; 
                    }

                    document.getElementById('totalNilaiPresentasiTerbobot').textContent = totalNilaiPresentasiTerbobot.toFixed(2);
                }
                updateTotalNilaiKeseluruhan();
            }

            document.querySelectorAll('.input-field').forEach(input => {
                input.addEventListener('input', function() {
                    var index = this.getAttribute('data-index');
                    var type = this.getAttribute('data-type');
                    if (type === 'skripsi') calculateSkripsiResult(index);
                    else if (type === 'artikel') calculateArtikelResult(index);
                    else if (type === 'presentasi') calculatePresentasiResult(index);
                });
            });

        </script>
    @endsection
</x-app-layout>
