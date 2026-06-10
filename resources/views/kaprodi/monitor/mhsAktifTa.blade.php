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
                Data Mahasiswa Aktif Tugas Akhir (TA)
            </h5>
        </div>

        {{-- Card wrapper biar tabel enak dilihat --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <div class="overflow-x-auto">
                <span class="text-sm text-slate-500 mb-4">
                    Total Mahasiswa Aktif TA: {{ $totalAktif }} <br>
                    <form method="GET" class="flex items-center gap-2 mb-4">
                        <label class="text-sm text-slate-600">Filter Angkatan:</label>

                        <select name="angkatan" class="border border-slate-300 rounded-lg px-3 py-2 text-sm"
                            onchange="this.form.submit()">

                            <option value="">Semua Angkatan</option>

                            @foreach ($listAngkatan as $a)
                                <option value="{{ $a }}" {{ $selectedAngkatan == $a ? 'selected' : '' }}>
                                    {{ $a }}
                                </option>
                            @endforeach
                        </select>

                        @if ($selectedAngkatan)
                            <a href="{{ url()->current() }}"
                                class="px-3 py-2 rounded-lg text-sm bg-slate-200 hover:bg-slate-300">
                                Reset
                            </a>
                        @endif
                    </form>
                </span>
                <table id="myTable" class="min-w-full text-sm">
                    <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                        <tr>
                            <th class="px-3 py-2 text-center">No</th>
                            <th class="px-3 py-2">NIM</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Judul Tugas Akhir</th>
                            <th class="px-3 py-2 text-center">Status Tahapan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @forelse($mahasiswaAktifTa as $i => $mhs)
                            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $mhs->nim ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $mhs->nama_lengkap ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $mhs->current_judul }}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 rounded-lg text-xs font-semibold
                                        {{ $mhs->current_tahapan === 'Seminar Proposal' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $mhs->current_tahapan === 'Seminar Hasil' ? 'bg-orange-100 text-orange-700' : '' }}
                                        {{ $mhs->current_tahapan === 'Sidang Akhir' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $mhs->current_tahapan === '-' ? 'bg-slate-100 text-slate-600' : '' }}
                                    ">
                                        {{ $mhs->current_tahapan }}
                                    </span>
                                    <div class="text-xs text-slate-500 mt-1">{{ $mhs->current_status }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-slate-500">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    @section('script')
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
            new DataTable('#myTable', {
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: "",
                    searchPlaceholder: "Cari NIM / Nama / Judul...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                    emptyTable: "Belum ada data"
                },
                columnDefs: [
                    // center kolom No + Status
                    {
                        targets: [0, 4],
                        className: "dt-head-center dt-body-center"
                    },

                    // align kiri untuk teks panjang
                    {
                        targets: [1, 2, 3],
                        className: "dt-head-left dt-body-left"
                    },

                    // lebar kolom (hanya yang ada)
                    {
                        targets: 0,
                        width: "70px"
                    },
                    {
                        targets: 1,
                        width: "130px"
                    },
                    {
                        targets: 2,
                        width: "220px"
                    },
                    {
                        targets: 3,
                        width: "520px"
                    },
                    {
                        targets: 4,
                        width: "180px"
                    },
                ],
            });
        </script>

    @endsection
</x-app-layout>
