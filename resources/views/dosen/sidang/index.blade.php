<x-app-layout>
    @section('title', 'Penilaian Sidang Akhir')
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
                    Penilaian Sidang Akhir
                </h5>
            </div>
            <div class="table-responsive text-base">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead class="text-base uppercase text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl">
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Judul</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($seminarSidangs as $sidang)
                            @php
                                $tanggalPelaksanaan = \Carbon\Carbon::parse($sidang->tanggal_pelaksanaan);
                                $tanggalSekarang = \Carbon\Carbon::now();
                                $isTanggalLewat = $tanggalPelaksanaan->lessThanOrEqualTo($tanggalSekarang);
                            @endphp
                            <tr>
                                <td class="">{{ $sidang->mahasiswa->nama_lengkap }}</td>
                                <td class="">{{ $sidang->mahasiswa->nim }}</td>
                                <td class="min-w-40">{{ $sidang->tugas_akhir->judul }}</td>
                                <td class="">{{ \Carbon\Carbon::parse($sidang->tanggal_pelaksanaan)->translatedFormat('l, d F Y') }}</td>
                                <td class="text-center">
                                    @if ($sidang->tugas_akhir->dosen_pembimbing_1_id == $dosenId)
                                        Pembimbing 1
                                    @elseif ($sidang->tugas_akhir->dosen_pembimbing_2_id == $dosenId)
                                        Pembimbing 2
                                    @elseif ($sidang->tugas_akhir->dosen_penguji_1_id == $dosenId)
                                        Penguji 1
                                    @elseif ($sidang->tugas_akhir->dosen_penguji_2_id == $dosenId)
                                        Penguji 2
                                    @endif
                                </td>
                                <td class="whitespace-nowrap text-center">
                                    <a href="/dosen/penilaian-sidang/detail/{{ $sidang->slug }}"
                                        data-tooltip-target="detail_{{ $sidang->slug }}" data-tooltip-placement="bottom"
                                        type="button"
                                        class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <div id="detail_{{ $sidang->slug }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Detail
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    @if($isTanggalLewat)

                                    <a  href="/dosen/penilaian-sidang/nilai/{{ $sidang->slug }}"
                                        data-tooltip-target="nilai_{{ $sidang->slug }}"
                                        data-tooltip-placement="bottom" type="button"
                                        class="cursor-pointer text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-list-check"></i>
                                    </a>

                                    <div id="nilai_{{ $sidang->slug }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Nilai Sidang
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    @else

                                    <button data-tooltip-target="nilai_{{ $sidang->slug }}"
                                        data-tooltip-placement="bottom" type="button"
                                        class="cursor-not-allowed text-slate-800 bg-gradient-to-r from-slate-400 via-slate-500 to-slate-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-slate-300 dark:focus:ring-slate-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center"
                                        disabled>
                                        <i class="bi bi-list-check"></i>
                                    </button>

                                    <div id="nilai_{{ $sidang->slug }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Belum bisa menilai, seminar belum terlaksana
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    @endif

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
            dropDownSidang()
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
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                    emptyTable: "Belum ada sidang akhir yang perlu dinilai"
                },
                columnDefs: [{
                        targets: [-1],
                        width: "5%",
                        orderable: false,
                    },
                    {
                        targets: [0, 1, 2, 3, 4, 5],
                        className: "dt-head-center"
                    },
                    {
                        width: "23",
                        targets: 0
                    },
                    {
                        width: "10%",
                        targets: 1
                    },
                    {
                        width: "39%",
                        targets: 2
                    },
                    {
                        width: "12%",
                        targets: 3
                    },
                    {
                        width: "11%",
                        targets: 4
                    },
                ],
                search: {
                    search: ""
                }
            });
        </script>
    @endsection
</x-app-layout>
