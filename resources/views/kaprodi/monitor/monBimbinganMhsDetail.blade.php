<x-app-layout>
    @section('title', 'Dashboard')
    @if (session()->has('success'))
        <div id="alert-3"
            class="flex items-center p-3 mb-1 text-white rounded-lg bg-green-400 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <span class="text-3xl"><i class="bi bi-check2-circle"></i></span>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                {{ session('success') }}
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-green-400 text-white rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-800 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-3" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @elseif (session()->has('fail'))
        <div id="alert-2" class="flex items-center p-3 mb-1 text-white rounded-lg bg-red-700" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
            <span class="sr-only">Info</span>
            <div class="ms-3 text-sm font-medium">
                {{ session('fail') }}
            </div>
            <button type="button"
                class="ms-auto -mx-1.5 -my-1.5 bg-red-700 text-white rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-800 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                data-dismiss-target="#alert-2" aria-label="Close">
                <span class="sr-only">Close</span>
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
            </button>
        </div>
    @endif
    <div class="text-gray-900 dark:text-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                {{ $title }}
            </h5>
        </div>
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('monitor.monBimbinganMhs.detail', $dosen->id) }}?role=pembimbing"
                class="px-3 py-2 rounded-lg text-sm {{ $role === 'pembimbing' ? 'bg-slate-900 text-white' : 'bg-slate-200' }}">
                Sebagai Pembimbing
            </a>
            <a href="{{ route('monitor.monBimbinganMhs.detail', $dosen->id) }}?role=penguji"
                class="px-3 py-2 rounded-lg text-sm {{ $role === 'penguji' ? 'bg-slate-900 text-white' : 'bg-slate-200' }}">
                Sebagai Penguji
            </a>

            <button onclick="openGrafik()"
                class="px-3 py-2 rounded-lg text-sm bg-blue-600 text-white hover:bg-blue-700">
                Lihat Grafik
            </button>

            <a href="{{ url('kaprodi/monitor/mon-bimbingan') }}"
                class="px-3 py-2 rounded-lg text-sm bg-slate-200 hover:bg-slate-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                Kembali
            </a>
        </div>

        <div id="modalGrafik" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

            <div class="bg-white rounded-xl shadow-xl w-[600px] p-6 relative">

                <!-- tombol close -->
                <button onclick="closeGrafik()" class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">
                    &times;
                </button>

                <h3 class="text-lg font-semibold mb-4 text-center">
                    Grafik Monitoring Dosen
                </h3>

                <canvas id="chartDosen"></canvas>

            </div>
        </div>

        {{-- Card wrapper biar tabel enak dilihat --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <form method="GET" class="flex items-center gap-2 mb-4">

                <input type="hidden" name="role" value="{{ $role }}">

                <select name="angkatan" class="border px-3 py-2 rounded-lg text-sm" onchange="this.form.submit()">

                    <option value="">Semua Angkatan</option>

                    @foreach ($listAngkatan as $a)
                        <option value="{{ $a }}" {{ $selectedAngkatan == $a ? 'selected' : '' }}>
                            {{ $a }}
                        </option>
                    @endforeach

                </select>

                @if ($selectedAngkatan)
                    <a href="{{ request()->url() }}?role={{ $role }}"
                        class="px-3 py-2 bg-gray-200 rounded-lg text-sm">
                        Reset
                    </a>
                @endif

            </form>
            <div class="overflow-x-auto">
                <table id="myTable" class="min-w-full text-sm">
                    <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                        <tr>
                            <th class="px-3 py-2 text-center">No</th>
                            <th class="px-3 py-2">NIM</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Judul Tugas Akhir</th>
                            <th class="px-3 py-2 text-center">Sebagai</th>

                            @if ($role === 'penguji')
                                <th class="px-3 py-2 text-center">Tahapan TA</th>
                            @else
                                <th class="px-3 py-2 text-center">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @forelse($rows as $i => $ta)
                            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $ta->mahasiswa?->nim ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $ta->mahasiswa?->nama_lengkap ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-normal">{{ $ta->judul ?? '-' }}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $ta->sebagai }}</td>

                                @if ($role === 'penguji')
                                    <td class="px-3 py-2 text-center whitespace-nowrap">
                                        {{ $ta->tahapan_terakhir ?? '-' }}</td>
                                @else
                                    <td class="px-3 py-2 text-center whitespace-nowrap">
                                        <a class="px-3 py-1 rounded-lg bg-blue-600 text-white text-xs hover:bg-blue-700"
                                            href="{{ route('monitor.rekamBimbinganMhs', [$dosen->id, $ta->mahasiswa_id]) }}">
                                            Lihat Rekam
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            {{-- biarkan kosong supaya DataTables tampilkan emptyTable (lebih aman) --}}
                        @endforelse
                    </tbody>
                </table>


            </div>
        </div>
    </div>

    @section('script')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

        <style>
            .dt-container {
                font-size: .95rem;
            }

            /* bikin bar atas rapi: kiri length, kanan search */
            .dt-length,
            .dt-search {
                margin-bottom: .75rem;
            }

            .dt-search input {
                padding: .45rem .75rem;
                border: 1px solid #cbd5e1;
                border-radius: .6rem;
                outline: none;
            }

            .dt-length select {
                padding: .35rem .6rem;
                border: 1px solid #cbd5e1;
                border-radius: .6rem;
                outline: none;
                margin: 0 .25rem;
            }

            .dt-paging button {
                border-radius: .6rem !important;
            }

            /* judul panjang wrap */
            #myTable td:nth-child(4) {
                white-space: normal;
                max-width: 520px;
            }

            #myTable thead th {
                white-space: nowrap;
            }
        </style>

        <script>
            // kalau ada init ganda (misal livewire/turbo), hancurkan dulu
            if ($.fn.dataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable().destroy();
            }

            new DataTable('#myTable', {
                responsive: true,
                autoWidth: false,
                pageLength: 10, // default 10
                lengthMenu: [10, 25, 50, 100], // tetap muncul dropdown "Tampilkan"
                language: {
                    search: "",
                    searchPlaceholder: "Cari NIM / Nama / Judul...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    emptyTable: "Belum ada data",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                },
                columnDefs: [
                    // total kolom = 6 (index 0..5)
                    {
                        targets: [0, 4, 5],
                        className: "dt-head-center dt-body-center"
                    },
                    {
                        targets: [1, 2, 3],
                        className: "dt-head-left dt-body-left"
                    },

                    {
                        targets: 0,
                        width: "70px"
                    },
                    {
                        targets: 1,
                        width: "140px"
                    },
                    {
                        targets: 2,
                        width: "240px"
                    },
                    {
                        targets: 3,
                        width: "520px"
                    },
                    {
                        targets: 4,
                        width: "130px"
                    },

                    // kolom terakhir (Aksi / Tahapan) jangan sortable kalau role pembimbing (kolom aksi)
                    @if ($role !== 'penguji')
                        {
                            targets: 5,
                            orderable: false,
                            width: "140px"
                        },
                    @else
                        {
                            targets: 5,
                            width: "160px"
                        },
                    @endif
                ],
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            function openGrafik() {
                const modal = document.getElementById('modalGrafik');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                loadChart();
            }

            function closeGrafik() {
                const modal = document.getElementById('modalGrafik');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function loadChart() {

                const data = @json($grafikSummary);

                const ctx = document.getElementById('chartDosen');

                if (window.myChart) {
                    window.myChart.destroy();
                }

                window.myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            'MHS Bimbingan (aktif)',
                            'Pembimbing 1',
                            'Pembimbing 2',
                            'MHS Diuji (aktif)',
                            'Penguji 1',
                            'Penguji 2'
                        ],
                        datasets: [{
                            label: 'Jumlah',
                            data: [
                                data.totalBimbingan,
                                data.pb1,
                                data.pb2,
                                data.totalDiuji,
                                data.uj1,
                                data.uj2
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        </script>
    @endsection

</x-app-layout>
