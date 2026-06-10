<x-app-layout>
    @section('title', 'Validasi Notulen')

    @if (session()->has('success'))
        <div class="flex items-center p-3 mb-3 text-white rounded-lg bg-green-500" role="alert">
            <span class="text-2xl me-2"><i class="bi bi-check2-circle"></i></span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ $title }}</h2>

        <div class="overflow-x-auto">
            <table id="myTable" class="min-w-full text-sm">
                <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                    <tr>
                        <th class="px-3 py-2 text-center">No</th>
                        <th class="px-3 py-2">Mahasiswa</th>
                        <th class="px-3 py-2">Judul TA</th>
                        <th class="px-3 py-2">Notulen</th>
                        <th class="px-3 py-2">Catatan Penguji 1</th>
                        <th class="px-3 py-2">Catatan Penguji 2</th>
                        <th class="px-3 py-2 text-center">Status</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                    @forelse($rows as $i => $r)
                        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-center">{{ $i + 1 }}</td>

                            <td class="px-3 py-2">
                                {{ $r->mahasiswa?->nim ?? '-' }}<br>
                                {{ $r->mahasiswa?->nama_lengkap ?? '-' }}
                            </td>

                            <td class="px-3 py-2">
                                {{ $r->tugasAkhir?->judul ?? '-' }}
                            </td>

                            <td class="px-3 py-2">
                                {{ $r->notulenMahasiswa?->nim ?? '-' }}<br>
                                {{ $r->notulenMahasiswa?->nama_lengkap ?? '-' }}
                            </td>

                            @php
                                $catatan = json_decode($r->catatan_ta_notulen_mahasiswa_id, true);
                            @endphp
                            <td class="px-3 py-2">
                                {{ Str::limit(data_get($catatan, 'catatan_penguji_1'), 50, '...') }}
                            </td>

                            <td class="px-3 py-2 text-center">
                                {{ Str::limit(data_get($catatan, 'catatan_penguji_2'), 50, '...') }}
                            </td>

                            <td class="px-3 py-2 text-center">

                                @if ($r->status_validasi_notulen_mhs === 'Disetujui')
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Disetujui</span>
                                @elseif($r->status_validasi_notulen_mhs === 'Ditolak')
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Ditolak</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-700">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('dosen.validasi-notulen.detail', $r->slug) }}"
                                        class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                        <i class="bi bi-eye"></i>
                                        Detail
                                    </a>

                                    <form action="{{ route('dosen.validasi-notulen.update', $r->slug) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('PUT')

                                        <div class="flex items-center gap-2">
                                            <button type="submit" name="status_validasi_notulen_mhs" value="Disetujui"
                                                class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg bg-green-600 text-white hover:bg-green-700">
                                                <i class="bi bi-check-circle"></i>
                                                Setujui
                                            </button>

                                            <button type="submit" name="status_validasi_notulen_mhs" value="Ditolak"
                                                class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium rounded-lg bg-red-600 text-white hover:bg-red-700">
                                                <i class="bi bi-x-circle"></i>
                                                Tolak
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </td>
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
                    searchPlaceholder: "Cari mahasiswa / judul / notulen...",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    emptyTable: "Belum ada data notulen untuk divalidasi",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                },
                columnDefs: [{
                        targets: [0, 6, 7],
                        className: "dt-head-center dt-body-center"
                    },
                    {
                        targets: 7,
                        orderable: false
                    }
                ]
            });
        </script>
    @endsection
</x-app-layout>
