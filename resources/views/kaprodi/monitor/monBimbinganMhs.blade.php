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
            <div class="overflow-x-auto">
               <table id="myTable" class="min-w-full text-sm">
                    <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                        <tr>
                            <th class="px-3 py-2 text-center">No</th>
                            <th class="px-3 py-2">Nama Dosen</th>

                            <th class="px-3 py-2 text-center ">Mhs Aktif Bimbingan</th>
                            <th class="px-3 py-2 text-center">Pembimbing 1</th>
                            <th class="px-3 py-2 text-center">Pembimbing 2</th>

                            <th class="px-3 py-2 text-center">Mhs Aktif Diuji</th>
                            <th class="px-3 py-2 text-center">Penguji 1</th>
                            <th class="px-3 py-2 text-center">Penguji 2</th>

                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @foreach($dosenRows as $i => $d)
                            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $i+1 }}</td>
                                <td class="px-3 py-2">{{ $d->nama_dosen }}</td>

                                <td class="px-3 py-2 text-center">{{ $d->jml_aktif_bimbingan }}</td>
                                <td class="px-3 py-2 text-center">{{ $d->jml_bim1 }}</td>
                                <td class="px-3 py-2 text-center">{{ $d->jml_bim2 }}</td>

                                <td class="px-3 py-2 text-center">{{ $d->jml_aktif_diuji }}</td>
                                <td class="px-3 py-2 text-center">{{ $d->jml_uji1 }}</td>
                                <td class="px-3 py-2 text-center">{{ $d->jml_uji2 }}</td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    <a class="px-3 py-1 rounded-lg bg-slate-900 text-white text-xs hover:bg-slate-700"
                                    href="{{ route('monitor.monBimbinganMhs.detail', $d->id) }}">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
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
        /* rapihin komponen datatable */
        .dt-container { font-size: .95rem; }
        .dt-search { display:flex; justify-content:flex-end; margin-bottom:.75rem; }
        .dt-search label { width: 100%; max-width: 320px; }
        .dt-search input {
            width: 100%;
            padding: .5rem .75rem;
            border: 1px solid #cbd5e1;
            border-radius: .6rem;
            outline: none;
        }
        .dt-length select {
            padding: .4rem .6rem;
            border: 1px solid #cbd5e1;
            border-radius: .6rem;
            outline: none;
            margin: 0 .25rem;
        }
        .dt-paging button { border-radius: .6rem !important; }
        table.dataTable tbody tr:hover { cursor: default; }
        table.dataTable thead th { white-space: nowrap; }
    </style>

    <script>
        // kalau function ini tidak ada di halaman ini, hapus baris ini biar tidak error
        if (typeof dropDownSempro === 'function') dropDownSempro();

        // Hindari init double kalau halaman ke-render ulang
        if ($.fn.dataTable.isDataTable('#myTable')) {
            $('#myTable').DataTable().destroy();
        }

        new DataTable('#myTable', {
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            searching: false,     // ❌ hilangkan search
            lengthChange: false,  // ❌ hilangkan "Tampilkan 10 data"
            info: false,          // ❌ hilangkan info bawah
            lengthMenu: [10, 25, 50, 100],
            language: {
                search: "",
                searchPlaceholder: "Cari nama dosen...",
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
                // 9 kolom => index 0..8
                { targets: [0,2,3,4,5,6,7,8], className: "dt-head-center dt-body-center" },
                { targets: [1], className: "dt-head-left dt-body-left" },

                // lebar kolom (biar rapih)
                { targets: 0, width: "60px" },
                { targets: 1, width: "220px" },
                { targets: 2, width: "150px" },
                { targets: 3, width: "120px" },
                { targets: 4, width: "120px" },
                { targets: 5, width: "150px" },
                { targets: 6, width: "120px" },
                { targets: 7, width: "120px" },
                { targets: 8, width: "90px", orderable: false },
            ],
        });
    </script>
@endsection

</x-app-layout>