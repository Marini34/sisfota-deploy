<x-app-layout>
    @section('title', 'Rekapitulasi Seminar Proposal')
    @section('head')
        @include('styles.datatables')
    @endsection
    <x-main-card>
        <div class="text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                    Rekapan Seminar Proposal
                </h5>
            </div>
            <div class="table-responsive text-base">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead class="text-base uppercase text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl">
                            <!--<th class="hidden"></th>-->
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Nilai</th>
                            <th>Hasil</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Dosen Pembimbing</th>
                            <th>Dosen Penguji</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sempros as $sempro)
                            <tr>
                                <td class="hidden">{{ $sempro->tanggal_pelaksanaan }}</td>
                                <td class="">{{ $sempro->mahasiswa->nama_lengkap }}</td>
                                <td class="">{{ $sempro->mahasiswa->nim }}</td>
                                <td class="">{{ $sempro->total_nilai_akhir }}</td>
                                <td class="">{{ $sempro->status_kelulusan }}</td>
                                <td class="">{{ \Carbon\Carbon::parse($sempro->tanggal_pelaksanaan)->translatedFormat('d F Y') }}</td>
                                <td class="">
                                    1. {{ $sempro->tugas_akhir->dosen_pembimbing_1->nama_dosen }}<br>
                                    2. {{ $sempro->tugas_akhir->dosen_pembimbing_2->nama_dosen }}
                                </td>
                                <td class="">
                                    1. {{ $sempro->tugas_akhir->dosen_penguji_1->nama_dosen }}<br>
                                    2. {{ $sempro->tugas_akhir->dosen_penguji_2->nama_dosen }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-main-card>
    @section('script')
        <script>
            // open dropdown menu
            dropDownSempro()
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        let table = new DataTable('#myTable', {
            responsive: true,
            autoWidth: false,
            paging: true,
            searching: true,
            order: [[0, 'desc']],
            language: {
                search: "",
                searchPlaceholder: "Cari...",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                paginate: {
                    next: '<i class="bi bi-chevron-right"></i>',
                    previous: '<i class="bi bi-chevron-left"></i>'
                },
                emptyTable: "Tidak ada data yang dapat ditampilkan"
            },
            columnDefs: [
                {
                    targets: [0,1, 2, 3, 4, 5, 6, 7],
                    className: "dt-head-center text-sm"
                },
                {
                    width: "5%",
                    target: 1,
                },
                {
                    width: "5%",
                    target: 2,
                },
                {
                    width: "5%",
                    target: 3,
                    className: "text-center"
                },
                {
                    width: "14%",
                    target: 4,
                    className: "text-center"
                },
                {
                    width: "23%",
                    target: 5
                },
                {
                    width: "23%",
                    target: 6
                },
                {
                    width: "1%",
                    target: 7
                },
            ],
            search: {
                search: ""
            }
        });
    </script>
    @endsection
</x-app-layout>