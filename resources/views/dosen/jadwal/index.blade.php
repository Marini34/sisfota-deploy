<x-app-layout>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                {{ $title }}
            </h2>

            <a href="{{ route('jadwal-bimbingan.create') }}"
               class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-700 text-white text-sm">
                + Tambah Jadwal
            </a>
        </div>

        <div class="overflow-x-auto">
            <table id="myTable" class="min-w-full text-sm">
                <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                    <tr>
                        <th class="px-3 py-2 text-center">No</th>
                        <th class="px-3 py-2 text-center">Tanggal</th>
                        <th class="px-3 py-2 text-center">Jam</th>
                        <th class="px-3 py-2 text-center">Kuota</th>
                        <th class="px-3 py-2 text-center">Lokasi</th>
                        <th class="px-3 py-2 text-center">Terisi</th>
                        <th class="px-3 py-2 text-center">Sisa</th>
                        <th class="px-3 py-2 text-center">Status</th>
                        <th class="px-3 py-2 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                    @forelse($rows as $i => $r)
                        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40">
                            <td class="px-3 py-2 text-center">{{ $i+1 }}</td>

                            <td class="px-3 py-2 text-center">
                                {{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}
                            </td>

                            <td class="px-3 py-2 text-center">
                                {{ substr($r->jam_mulai,0,5) }} - {{ substr($r->jam_selesai,0,5) }}
                            </td>

                            <td class="px-3 py-2 text-center">{{ $r->kuota }}</td>
                            <td class="px-3 py-2 text-center">{{ $r->lokasi }}</td>
                            <td class="px-3 py-2 text-center">{{ $r->terisi }}</td>
                            <td class="px-3 py-2 text-center">{{ $r->kuota - $r->terisi }}</td>

                            <td class="px-3 py-2 text-center">
                                @if($r->terisi >= $r->kuota)
                                    <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                                        Penuh
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                        Tersedia
                                    </span>
                                @endif
                            </td>

                            <td class="px-3 py-2 text-center space-x-1">
                                <a href="{{ route('jadwal-bimbingan.edit', $r->id) }}"
                                   class="px-2 py-1 text-xs rounded bg-blue-600 text-white hover:bg-blue-700">
                                    Edit
                                </a>

                                <form action="{{ route('jadwal-bimbingan.destroy', $r->id) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 text-xs rounded bg-red-600 text-white hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- biarkan kosong agar DataTables emptyTable tampil --}}
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
            searchPlaceholder: "Cari tanggal / jam...",
            info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 data",
            emptyTable: "Belum ada jadwal bimbingan",
            paginate: {
                next: '<i class="bi bi-chevron-right"></i>',
                previous: '<i class="bi bi-chevron-left"></i>'
            },
        },
        columnDefs: [
            { targets: [0,1,2,3,4,5,6,7], className: "dt-head-center dt-body-center" },
            { targets: 7, orderable: false }
        ]
    });
</script>
@endsection

</x-app-layout>