<x-app-layout>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ $title }}</h2>

        {{-- 🔥 FILTER ANGKATAN --}}
        <div class="mb-4 flex items-center gap-3">
            <label class="text-sm font-medium">Filter Angkatan:</label>
            <select id="filterAngkatan" class="border px-3 py-2 rounded-lg text-sm">
                <option value="">Semua Angkatan</option>

                @php
                    $start = 2014;
                    $end = now()->year;
                @endphp

                @for ($tahun = $end; $tahun >= $start; $tahun--)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endfor
            </select>
        </div>

        <div class="overflow-x-auto">
            <table id="myTable" class="min-w-full text-sm">
                <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                    <tr>
                        <th class="px-3 py-2 min-w-10 text-center">No</th>
                        <th class="px-3 py-2 min-w-10">NIM</th>
                        <th class="px-3 py-2 min-w-10">Nama</th>
                        <th class="px-3 py-2 min-w-10">Judul</th>
                        <th class="px-3 py-2 min-w-10">Peran</th>

                        @if ($showTahunLulus == false)
                            <th class="px-3 py-2 min-w-10">Tahapan TA</th>
                        @endif

                        @if ($showTahunLulus)
                            <th class="px-3 py-2 min-w-10 text-center">Lama Pengerjaan TA</th>
                            <th class="px-3 py-2 min-w-10 text-center">Tahun Lulus</th>
                        @endif

                        @if (!request()->is('dosen/monitor/diuji-aktif') && !request()->is('dosen/monitor/diuji-lulus'))
                            <th class="px-3 py-2 min-w-10 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                    @forelse($rows as $i => $r)
                        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40"
                            data-angkatan="{{ $r->tahun_masuk }}">

                            <td class="px-3 py-2 text-center whitespace-nowrap">{{ $i + 1 }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $r->nim ?? '-' }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $r->nama_lengkap ?? '-' }}</td>

                            <td class="px-3 py-2 whitespace-normal">
                                {{ $r->tugasAkhir->judul ?? '-' }}
                            </td>

                            <td>
                                @if ($r->tugasAkhir->dosen_pembimbing_1_id == $dosenId)
                                    Pembimbing 1
                                @elseif ($r->tugasAkhir->dosen_pembimbing_2_id == $dosenId)
                                    Pembimbing 2
                                @elseif ($r->tugasAkhir->dosen_penguji_1_id == $dosenId)
                                    Penguji 1
                                @elseif ($r->tugasAkhir->dosen_penguji_2_id == $dosenId)
                                    Penguji 2
                                @endif
                            </td>

                            @if ($showTahunLulus == false)
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    {{ $r->seminarSidangData?->tahapan_ta ?? '-' }}
                                </td>
                            @endif

                            @if ($showTahunLulus)
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    {{ $r->lamaPengerjaanTa() }}
                                </td>
                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    {{ $r->tahun_lulus ?? '-' }}
                                </td>
                            @endif

                            {{-- 🔥 AKSI (TIDAK DIUBAH) --}}
                            @if (!request()->is('dosen/monitor/diuji-aktif') && !request()->is('dosen/monitor/diuji-lulus'))
                                <td class="px-3 py-2 text-center">

                                    <a href="{{ route('monitor.grafikBimbingan', $r->id) }}"
                                        data-tooltip-target="grafik_{{ $r->id }}"
                                        class="cursor-pointer mb-1 text-white bg-gradient-to-br from-green-400 to-blue-600 w-8 h-7 inline-flex items-center justify-center rounded-lg">
                                        <i class="bi bi-bar-chart"></i>
                                    </a><br>

                                    <a href="/dosen/daftar-bimbingan/{{ $r->slug }}"
                                        data-tooltip-target="detail_{{ $r->id }}"
                                        class="cursor-pointer mb-1 text-white bg-gradient-to-br from-green-400 to-blue-600 w-8 h-7 inline-flex items-center justify-center rounded-lg">
                                        <i class="bi bi-eye"></i>
                                    </a><br>

                                    <a href="/dosen/daftar-bimbingan/informasi-mahasiswa/{{ $r->slug }}"
                                        data-tooltip-target="info_{{ $r->id }}"
                                        class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 w-8 h-7 inline-flex items-center justify-center rounded-lg">
                                        <i class="bi bi-info-lg"></i>
                                    </a>

                                </td>
                            @endif

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-6 text-center text-slate-500">
                                Tidak ada data ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @section('script')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

        <style>
            /* 🔥 search kiri */
            .dt-search {
                justify-content: flex-start !important;
                margin-bottom: 10px;
            }

            .dt-search input {
                width: 260px;
                padding: .5rem .8rem;
                border: 1px solid #cbd5e1;
                border-radius: .5rem;
            }
        </style>

        <script>
            const table = new DataTable('#myTable', {
                pageLength: 10,
                searching: true,
                lengthChange: false,

                dom: '<"flex justify-between items-center mb-3"f>rt<"flex justify-between items-center mt-3"ip>',

                language: {
                    search: "",
                    searchPlaceholder: "Cari data...",
                    emptyTable: "Tidak ada data ditemukan",
                    zeroRecords: "Tidak ada data ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "", // 🔥 hilangkan info kalau kosong
                    paginate: {
                        next: '>',
                        previous: '<'
                    },
                }
            });

            // 🔥 FILTER ANGKATAN (native)
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const selected = $('#filterAngkatan').val();
                if (!selected) return true;

                const row = table.row(dataIndex).node();
                const angkatan = row.getAttribute('data-angkatan');

                return angkatan === selected;
            });

            $('#filterAngkatan').on('change', function() {
                table.draw();
            });
        </script>
    @endsection
</x-app-layout>