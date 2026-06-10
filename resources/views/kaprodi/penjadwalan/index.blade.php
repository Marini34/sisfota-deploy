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
                            <th class="px-3 py-2">NIM</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Judul TA</th>
                            <th class="px-3 py-2 text-center">Status</th>
                            <th class="px-3 py-2 text-center">Jadwal</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @forelse($penjadwalanData as $i => $d)
                            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $i+1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $d->mahasiswa?->nim ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $d->mahasiswa?->nama_lengkap ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-normal">{{ $d->tugasAkhir?->judul ?? '-' }}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $d->status_pendaftaran ?? '-' }}</td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    @if($d->tanggal_pelaksanaan && $d->jam_pelaksanaan && $d->tempat_pelaksanaan)
                                        <div class="inline-block px-2 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-semibold">
                                            Sudah Terjadwal
                                        </div>
                                        <div class="text-xs text-slate-600 mt-1">
                                            {{ \Carbon\Carbon::parse($d->tanggal_pelaksanaan)->format('d-m-Y') }}
                                            • {{ $d->jam_pelaksanaan }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $d->tempat_pelaksanaan }}
                                        </div>
                                    @else
                                        <span class="inline-block px-2 py-1 rounded-lg bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                            Belum Terjadwal
                                        </span>
                                    @endif
                                    @if($d->tugasAkhir?->dosen_penguji_1_id && $d->tugasAkhir?->dosen_penguji_2_id )
                                        <div class="inline-block px-2 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-semibold">
                                            Penguji Sudah Ditentukan
                                        </div>
                                        <div class="text-xs text-slate-600 mt-1">
                                            {{ \Carbon\Carbon::parse($d->tanggal_pelaksanaan)->format('d-m-Y') }}
                                            • {{ $d->jam_pelaksanaan }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $d->tempat_pelaksanaan }}
                                        </div>
                                    @else
                                        <span class="inline-block px-2 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-semibold">
                                            Penguji Belum Ditentukan
                                        </span>
                                    @endif
                                </td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    {{-- nanti arahkan ke edit/show untuk set penguji --}}
                                    <a class="px-3 py-1 rounded-lg bg-slate-900 text-white hover:bg-slate-700"
                                    href="{{ route('penjadwalan.edit', $d->id) }}">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            {{-- biarkan kosong agar DataTables emptyTable tampil --}}
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
    .dt-container { font-size: .95rem; }
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
    .dt-paging button { border-radius: .6rem !important; }
    #myTable td:nth-child(4) { white-space: normal; max-width: 520px; }
    #myTable thead th { white-space: nowrap; }
</style>

<script>
    if ($.fn.dataTable.isDataTable('#myTable')) {
        $('#myTable').DataTable().destroy();
    }

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
            emptyTable: "Belum ada pengajuan yang perlu dijadwalkan",
            paginate: {
                next: '<i class="bi bi-chevron-right"></i>',
                previous: '<i class="bi bi-chevron-left"></i>'
            },
        },
        columnDefs: [
            { targets: [0,4,5,6], className: "dt-head-center dt-body-center" },
            { targets: [1,2,3], className: "dt-head-left dt-body-left" },

            { targets: 0, width: "60px" },
            { targets: 1, width: "140px" },
            { targets: 2, width: "220px" },
            { targets: 3, width: "520px" },
            { targets: 4, width: "150px" },
            { targets: 5, width: "220px", orderable: false }, // Jadwal jangan sortable
            { targets: 6, width: "90px", orderable: false },  // Aksi jangan sortable
            ],
    });
</script>
@endsection

</x-app-layout>
