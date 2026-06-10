<x-app-layout>
    @section('title', 'Daftar Mahasiswa Bimbingan')
    @section('head')
        @include('styles.datatables')
    @endsection
    <x-main-card>
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
                    Daftar Mahasiswa Bimbingan
                </h5>
            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead class="uppercase text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl">
                            <th>Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($rekamBimbingans as $rekamBimbingan)
                            <tr>
                                <td>
                                    <div class="">
                                        {{ $rekamBimbingan->mahasiswa->nama_lengkap }}
                                    </div>
                                </td>
                                <td>{{ $rekamBimbingan->mahasiswa->nim }}</td>
                                <td>
                                    @if ($rekamBimbingan->dosen_pembimbing_1_id == $dosenId)
                                        Pembimbing 1
                                    @elseif ($rekamBimbingan->dosen_pembimbing_2_id == $dosenId)
                                        Pembimbing 2
                                    @endif
                                </td>
                                <td class="whitespace-nowrap text-center">
                                    
                                    <a href="/dosen/daftar-bimbingan/{{ $rekamBimbingan->mahasiswa->slug }}"
                                        data-tooltip-target="detail_{{ $rekamBimbingan->id }}"
                                        data-tooltip-placement="bottom" type="button"
                                        class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <div id="detail_{{ $rekamBimbingan->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Lihat Rekam Bimbingan Tugas Akhir
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <a href="/dosen/daftar-bimbingan/informasi-mahasiswa/{{ $rekamBimbingan->mahasiswa->slug }}"
                                        data-tooltip-target="info_{{ $rekamBimbingan->id }}"
                                        data-tooltip-placement="bottom" type="button"
                                        class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-info-lg"></i>
                                    </a>

                                    <div id="info_{{ $rekamBimbingan->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Detail Inforamasi Mahasiswa
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
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
            dropDownBimbingan()
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
            let table = new DataTable('#myTable', {
                // responsive: true,
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
                        targets: [0, 1, 2],
                        className: "dt-head-center",
                        orderable: true
                    },
                    {
                        targets: 0,
                        width: "40%",
                    },
                    {
                        targets: 1,
                        width: "25%",
                    },
                    {
                        width: "25%",
                        targets: 2
                    },
                    {
                        width: "10%",
                        orderable: false,
                        className: "dt-head-center",
                        targets: 3
                    }
                ],
                search: {
                    search: ""
                }
            });
        </script>
    @endsection
</x-app-layout>
