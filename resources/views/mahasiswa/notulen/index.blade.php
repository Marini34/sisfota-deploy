<x-app-layout>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ $title }}</h2>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table id="myTable" class="min-w-full text-sm">
                <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                    <tr>
                        <th class="px-3 py-2 text-center">No</th>
                        <th class="px-3 py-2">Mahasiswa</th>
                        <th class="px-3 py-2">Judul TA</th>
                        <th class="px-3 py-2 text-center">Tahapan</th>
                        <th class="px-3 py-2 text-center">Waktu Pelaksanaan</th>
                        <th class="px-3 py-2 text-center">Catatan Notulensi</th>
                        <th class="px-3 py-2 text-center">Status Validasi</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                    @forelse($rows as $i => $r)
                        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-center">{{ $i + 1 }}</td>

                            <td class="px-3 py-2">
                                {{ $r->mahasiswa->nim ?? '-' }}<br>
                                {{ $r->mahasiswa->nama_lengkap ?? '-' }}
                            </td>

                            <td class="px-3 py-2">
                                {{ $r->tugasAkhir->judul ?? '-' }}
                            </td>

                            <td class="px-3 py-2 text-center">
                                {{ $r->tahapan_ta ?? '-' }}
                            </td>

                            <td class="px-3 py-2 text-center">
                                {{ $r->tanggal_pelaksanaan ? \Carbon\Carbon::parse($r->tanggal_pelaksanaan)->format('d-m-Y') : '-' }}
                                -
                                {{ $r->tanggal_pelaksanaan ? \Carbon\Carbon::parse($r->jam_pelaksanaan)->format('H:i') : '-' }}
                            </td>
                            @php
                                $catatan = json_decode($r->catatan_ta_notulen_mahasiswa_id, true);
                            @endphp

                            <td class="px-3 py-2 text-center">
                                {{ Str::limit(data_get($catatan, 'catatan_penguji_1'), 50, '...') }}
                                {{ Str::limit(data_get($catatan, 'catatan_penguji_2'), 50, '...') }}
                            </td>

                            <td class="px-3 py-2 text-center">
                                @php
                                    $status = $r->status_validasi_notulen_mhs ?? 'Menunggu';
                                @endphp

                                @if ($status === 'Disetujui')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Disetujui</span>
                                @elseif($status === 'Ditolak')
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Ditolak</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">Menunggu</span>
                                @endif
                            </td>
                            @if ($r->status_validasi_notulen_mhs == 'Disetujui')
                                <td class="px-3 py-2 text-center">
                                    <a href="{{ route('jadwal-notulen.edit', $r->slug) }}"
                                        class="px-3 py-1 rounded-lg bg-blue-600 text-white text-xs hover:bg-blue-700">
                                        Detail
                                    </a>
                                </td>
                            @else
                                <td class="px-3 py-2 text-center">
                                    <a href="{{ route('jadwal-notulen.edit', $r->slug) }}"
                                        class="px-3 py-1 rounded-lg bg-blue-600 text-white text-xs hover:bg-blue-700">
                                        Catatan
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @section('script')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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
                    searchPlaceholder: "Cari mahasiswa / judul...",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    emptyTable: "Belum ada jadwal notulen",
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
                        targets: 6,
                        orderable: false
                    }
                ]
            });
        </script>
    @endsection
</x-app-layout>
