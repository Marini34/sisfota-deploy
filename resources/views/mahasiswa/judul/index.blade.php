<x-app-layout>
    @section('title', 'Pencarian Judul Tugas Akhir')
    @section('head')
        @include('styles.datatables')
    @endsection
    <x-main-card>
        <h5 class="mb-5 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
            Pencarian Judul Tugas Akhir
        </h5>
        <div class="text-gray-900 dark:text-gray-100">
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-hover">
                    <thead>
                        <tr class="bg-slate-300 rounded-2xl">
                            <th>Judul Tugas Akhir</th>
                            <th>Studi Kasus</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="hilang">
                        @foreach ($seminarSidang as $index => $judul)
                            <tr>
                                <td>{{ $judul->tugas_akhir->judul }}</td>
                                <td>{{ $judul->tugas_akhir->studi_kasus }}</td>
                                <td>{{ $judul->tugas_akhir->metode }}</td>
                                <td>
                                    <a href="/judul/detail/{{ $judul->tugas_akhir->judul }}"
                                        data-tooltip-target="detail_{{ $judul->tugas_akhir->judul }}"
                                        data-tooltip-placement="bottom" type="button"
                                        class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-2 py-1 text-center">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <div id="detail_{{ $judul->tugas_akhir->judul }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Detail
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tbody id="muncul">
                        <tr>
                            <td colspan="5" class="text-center">Ketik pada kotak pencarian untuk menampilkan data
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </x-main-card>
    @section('script')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>r
        <script type="text/javascript">
            // Sembunyikan div hilang secara default saat halaman dimuat
            $('#hilang').hide();
            document.addEventListener('DOMContentLoaded', function() {
                let table = new DataTable('#myTable', {
                    responsive: true,
                    autoWidth: false,
                    paging: true,
                    searching: true,
                    language: {
                        search: "",
                        searchPlaceholder: "Cari...",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Total Judul Tugas Akhir : _TOTAL_",
                        paginate: {
                            next: '<i class="bi bi-chevron-right"></i>',
                            previous: '<i class="bi bi-chevron-left"></i>'
                        },
                        emptyTable: "Data belum tersedia"
                    },
                    columnDefs: [{
                            targets: [-1],
                            orderable: false,
                            className: "dt-head-center"
                        },
                        {
                            targets: [0, 1, 2, 3],
                            className: "dt-head-center"
                        },
                        {
                            width: "53%",
                            targets: 0
                        },
                        {
                            width: "20%",
                            targets: 1
                        },
                        {
                            width: "20%",
                            targets: 2
                        },
                        {
                            width: "7%",
                            targets: 3
                        },
                    ]
                });
                // Tampilkan div hilang saat ada input di kotak pencarian
                $('#myTable_filter input').on('keyup', function() {
                    if ($(this).val().trim() === '') {
                        $('#hilang').hide();
                        $('#muncul').show();
                    } else {
                        $('#hilang').show();
                        $('#muncul').hide();
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
