<x-app-layout>
    @section('title', 'Dashboard')

    <div class="text-gray-900 dark:text-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h5 class="text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                    {{ $title }}
                </h5>
                <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                    <div><b>Dosen:</b> {{ $dosen->nama_dosen }}</div>
                    <div><b>Mahasiswa:</b> {{ $mahasiswa->nama_lengkap }} ({{ $mahasiswa->nim }})</div>
                </div>
            </div>

            <a href="{{ url()->previous() }}"
                class="px-3 py-2 rounded-lg text-sm bg-slate-200 hover:bg-slate-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                Kembali
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
            <div class="overflow-x-auto">
                <table id="myTable" class="min-w-full text-sm">
                    <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                        <tr>
                            <th class="px-3 py-2 text-center">No</th>
                            <th class="px-3 py-2 text-center">Tanggal</th>
                            <th class="px-3 py-2 text-center">Status</th>
                            <th class="px-3 py-2">Uraian Kemajuan TA</th>
                            <th class="px-3 py-2">Pengerjaan Selanjutnya</th>
                            <th class="px-3 py-2">Presentase Progress</th>
                            <th class="px-3 py-2">Dokumen Bimbingan</th>
                            <th class="px-3 py-2">Komentar Dosen</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-gray-700">
                        @forelse($rekamRows as $i => $r)
                            <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/40 align-top">
                                <td class="px-3 py-2 text-center whitespace-nowrap">{{ $i + 1 }}</td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    {{ $r->tanggal_bimbingan ? \Carbon\Carbon::parse($r->tanggal_bimbingan)->format('d-m-Y') : '-' }}
                                </td>

                                <td class="px-3 py-2 text-center whitespace-nowrap">
                                    @php
                                        $status = $r->status_rekam_bimbingan ?? '-';
                                        $badge = 'bg-slate-100 text-slate-600';
                                        if ($status === 'DISETUJUI') {
                                            $badge = 'bg-green-100 text-green-700';
                                        } elseif ($status === 'DITOLAK') {
                                            $badge = 'bg-red-100 text-red-700';
                                        } elseif ($status === 'Menunggu Keputusan') {
                                            $badge = 'bg-yellow-100 text-yellow-700';
                                        }
                                    @endphp

                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold {{ $badge }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="px-3 py-2 whitespace-normal">
                                    {{ $r->uraian_kemajuan_ta ?? '-' }}
                                </td>

                                <td class="px-3 py-2 whitespace-normal">
                                    {{ $r->pengerjaan_selanjutnya ?? '-' }}
                                </td>

                                <td class="px-3 py-2 whitespace-normal">
                                    {{ $r->persentase_mhs ?? 0 }} %
                                </td>

                                <td class="px-3 py-2 whitespace-normal">
                                    @if ($r->dokumen_bimbingan_mhs)
                                        <a href="{{ asset('storage/' . $r->dokumen_bimbingan_mhs) }}" target="_blank"
                                            class="text-blue-600 hover:underline">
                                            Lihat Dokumen
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-3 py-2 whitespace-normal">
                                    {{ $r->komentar_dosen ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-6 text-center text-slate-500">
                                    Belum ada rekam bimbingan.
                                </td>
                            </tr>
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

            /* biar kolom teks bisa wrap */
            #myTable td:nth-child(4),
            #myTable td:nth-child(5) {
                white-space: normal;
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
                    searchPlaceholder: "Cari isi bimbingan...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    emptyTable: "Belum ada rekam bimbingan",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                },
                columnDefs: [{
                        targets: [0, 1, 2],
                        className: "dt-head-center dt-body-center"
                    },
                    {
                        targets: [3, 4],
                        className: "dt-head-left dt-body-left"
                    },

                    {
                        targets: 0,
                        width: "70px"
                    },
                    {
                        targets: 1,
                        width: "120px"
                    },
                    {
                        targets: 2,
                        width: "170px"
                    },
                ]
            });
        </script>
    @endsection
</x-app-layout>
