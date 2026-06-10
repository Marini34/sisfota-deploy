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

        {{-- Card wrapper biar tabel enak dilihat --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <p class="text-xs text-slate-500 mt-2">
                *Tahun pada grafik menunjukkan tahun kelulusan mahasiswa.
            </p>
            <canvas id="grafikLMS" height="100"></canvas>
            <div class="mb-3">
                <span class="text-sm text-slate-600">Rata-rata LMS:</span>
                <span class="font-bold text-lg text-blue-600">
                    {{ $rataLms }} Tahun
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-2 mb-3">
                <form method="GET" class="flex items-center gap-2">
                    <label class="text-sm text-slate-600 dark:text-slate-300">Filter:</label>
                    <select name="status"
                        class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 dark:border-gray-700"
                        onchange="this.form.submit()">
                        <option value="all" {{ $selectedStatus === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="lulus" {{ $selectedStatus === 'lulus' ? 'selected' : '' }}>Sudah Lulus</option>
                        <option value="belum" {{ $selectedStatus === 'belum' ? 'selected' : '' }}>Belum Lulus</option>
                    </select>
                    <select name="angkatan" class="border rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">

                        <option value="">Semua Angkatan</option>

                        @foreach ($listAngkatan as $angk)
                            <option value="{{ $angk }}" {{ $selectedAngkatan == $angk ? 'selected' : '' }}>
                                {{ $angk }}
                            </option>
                        @endforeach
                    </select>

                    @if ($selectedStatus !== 'all')
                        <a href="{{ url()->current() }}"
                            class="px-3 py-2 rounded-lg text-sm bg-slate-200 hover:bg-slate-300">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
            <div class="bg-white rounded-xl shadow p-4 mb-4">
                <h3 class="font-semibold mb-2">Rata-rata LMS per Angkatan</h3>

                <table class="w-full text-sm" border="1">
                    <thead>
                        <tr class="bg-slate-200">
                            <th class="px-3 py-2 text-center whitespace-nowrap">Angkatan</th>
                            <th class="px-3 py-2 text-center whitespace-nowrap">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rataPerAngkatan as $r)
                            <tr>
                                <td class="text-center">{{ $r->tahun_masuk }}</td>
                                <td class="text-center">{{ round($r->rata, 2) }} Tahun</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="overflow-x-auto">
                <table id="myTable" class="min-w-full text-sm">
                    <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                        <tr>
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">NIM</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Tanggal Masuk</th>
                            <th class="px-3 py-2">Tanggal Lulus</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">LMS</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @foreach ($mhsLmsData as $i => $mhs)
                            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $mhs->nim ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $mhs->nama_lengkap ?? '-' }}</td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    {{ $mhs->tgl_masuk ?? '-' }}-{{ $mhs->bln_masuk ?? '-' }}-{{ $mhs->tahun_masuk ?? '-' }}
                                </td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    @if (empty($mhs->tahun_lulus))
                                        -
                                    @else
                                        {{ $mhs->tgl_lulus ?? '-' }}-{{ $mhs->bln_lulus ?? '-' }}-{{ $mhs->tahun_lulus ?? '-' }}
                                    @endif
                                </td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 rounded-lg text-xs font-semibold
                                        {{ $mhs->status_lulus === 'Lulus' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $mhs->status_lulus }}
                                    </span>
                                </td>

                                <td class="px-3 py-2 text-center whitespace-nowrap font-medium">
                                    {{ $mhs->lms }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    @section('script')
        <script>
            document.querySelector('select[name="angkatan"]')?.addEventListener('change', function() {
                this.form.submit();
            });
        </script>
        {{-- jQuery --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        {{-- DataTables CSS + JS (PASTIKAN VERSI SAMA) --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

        <style>
            /* bikin kontrol DataTables lebih rapi */
            .dt-container {
                font-size: 0.95rem;
            }

            .dt-search input {
                padding: .45rem .75rem;
                border: 1px solid #cbd5e1;
                border-radius: .5rem;
                outline: none;
            }

            .dt-length select {
                padding: .35rem .6rem;
                border: 1px solid #cbd5e1;
                border-radius: .5rem;
                outline: none;
            }

            .dt-paging button {
                border-radius: .5rem !important;
            }
        </style>

        <script>
            dropDownSempro();

            new DataTable('#myTable', {
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: "",
                    searchPlaceholder: "Cari NIM / Nama...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    emptyTable: "Belum ada data",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                },
                columnDefs: [{
                        targets: [0, 3, 4, 5, 6],
                        className: "dt-head-center dt-body-center"
                    },
                    {
                        targets: [1, 2],
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
                        width: "260px"
                    },
                    {
                        targets: 3,
                        width: "160px"
                    },
                    {
                        targets: 4,
                        width: "160px"
                    },
                    {
                        targets: 5,
                        width: "120px"
                    },
                    {
                        targets: 6,
                        width: "170px"
                    },
                ],
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const labels = @json($grafik->pluck('tahun_lulus'));
            const data = @json($grafik->pluck('rata'));

            new Chart(document.getElementById('grafikLMS'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Rata-rata LMS',
                        data: data,
                        fill: false,
                        tension: 0.3
                    }]
                }
            });
        </script>

    @endsection
</x-app-layout>
