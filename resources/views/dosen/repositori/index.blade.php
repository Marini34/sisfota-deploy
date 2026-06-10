<x-app-layout>
    @section('title', 'Repositori File Tugas Akhir')
    @section('head')
        @include('styles.datatables')
    @endsection

    <x-main-card>
        <h1 class="text-xl font-bold text-center">Repositori File Tugas Akhir</h1>
        <br>
        <div class="table-responsive">
            <table id="myTable" class="table table-striped table-hover">
                <thead class="text-base uppercase text-slate-800">
                    <tr class="bg-slate-300 rounded-2xl">
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Judul TA</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tugasAkhirs as $data)
                    <tr>
                        <td>{{ $data->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $data->mahasiswa->nim }}</td>
                        <td>{{ $data->judul }}</td>
                        <td class="text-center">
                            <a href="/dosen/repositori/detail/{{ $data->slug }}"
                                data-tooltip-target="detail_{{ $data->slug }}" data-tooltip-placement="bottom" type="button"
                                class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                <i class="bi bi-eye"></i>
                            </a>
            
                            <div id="detail_{{ $data->slug }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Detail
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-main-card>
    @section('script')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
            let table = new DataTable('#myTable', {
                responsive: true,
                autoWidth: false,
                paging: true,
                searching: true,
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
                columnDefs: [{
                        targets: [-1],
                        width: "10%",
                        orderable: false,
                    },
                    {
                        targets: [0, 1, 2, 3],
                        className: "dt-head-center"
                    },
                    {
                        width: "25%",
                        targets: 0
                    },
                    {
                        width: "15%",
                        targets: 1
                    },
                    {
                        width: "50%",
                        targets: 2
                    },
                ],
                search: {
                    search: ""
                }
            });
        </script>
    @endsection
</x-app-layout>