<x-app-layout>
    @section('title', 'Dashboard')

    <div class="text-gray-900 dark:text-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                {{ $title }}
            </h5>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <div class="overflow-x-auto">
                <table id="myTable" class="min-w-full text-sm">
                    <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                        <tr>
                            <th class="px-3 py-2 text-center">No</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">NIM</th>
                            <th class="px-3 py-2">Judul TA</th>
                            <th class="px-3 py-2 text-center">Dokumen Jurnal</th>
                            <th class="px-3 py-2 text-center">Bukti Submit Jurnal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @forelse($rows as $i => $r)
                            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $i + 1 }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $r->mahasiswa?->nama_lengkap ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $r->mahasiswa?->nim ?? '-' }}</td>
                                <td class="px-3 py-2 whitespace-normal">{{ $r->tugasAkhir?->judul ?? '-' }}</td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    @if ($r->dokumen_jurnal)
                                        <a href="{{ asset('storage/' . $r->dokumen_jurnal) }}" target="_blank"
                                            class="px-3 py-1 rounded-lg bg-blue-600 text-white text-xs hover:bg-blue-700">
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-slate-500">-</span>
                                    @endif
                                </td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    @if ($r->bukti_submit_jurnal)
                                        <a href="{{ asset('storage/' . $r->bukti_submit_jurnal) }}" target="_blank"
                                            class="px-3 py-1 rounded-lg bg-green-600 text-white text-xs hover:bg-green-700">
                                            Lihat File
                                        </a>
                                    @else
                                        <span class="text-slate-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
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

        <script>
            if ($.fn.dataTable.isDataTable('#myTable')) {
                $('#myTable').DataTable().destroy();
            }

            new DataTable('#myTable', {
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthChange: false,
                language: {
                    search: "",
                    searchPlaceholder: "Cari nama / NIM / judul...",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    emptyTable: "Belum ada data repositori jurnal",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                },
                columnDefs: [{
                        targets: [0, 4, 5],
                        className: "dt-head-center dt-body-center"
                    },
                    {
                        targets: 0,
                        width: "70px"
                    },
                    {
                        targets: 1,
                        width: "220px"
                    },
                    {
                        targets: 2,
                        width: "150px"
                    },
                    {
                        targets: 3,
                        width: "420px"
                    },
                    {
                        targets: 4,
                        width: "150px",
                        orderable: false
                    },
                    {
                        targets: 5,
                        width: "170px",
                        orderable: false
                    },
                ]
            });
        </script>
    @endsection
</x-app-layout>
