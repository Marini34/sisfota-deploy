<x-app-layout>
    @section('title', 'Rekapitulasi Seminar Proposal')
    @section('head')
        @include('styles.datatables')
    @endsection
    <x-main-card>
        @if (session()->has('fail'))
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
        {{-- Modal --}}
        <dialog id="modalForm" class="miniscrollbar bg-white rounded-lg shadow-2xl w-4/5 md:w-4/12 fixed">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                    Cetak Rekapitulasi Seminar Proposal
                </h3>
                <button onclick="closeDialog()" type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-toggle="timeline-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('admin.sempro.rekap.cetak') }}" method="post" class="p-4 md:p-5">
                @csrf
                <label for="periode">Periode Pelaksanaan</label>
                <div class="my-2">
                    <input type="month" name="periode" id="periode" class="w-full rounded-md border-slate-300"
                        required></input>
                </div>
                <br>
                <button type="submit"
                    class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                    DOWNLOAD
                </button>
            </form>
        </dialog>
        {{-- End Modal --}}
        <div class="text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                    Rekapan Seminar Proposal
                </h5>
                <div class="float-end text-gray-900 dark:text-gray-100">
                    <button type="button" onclick="modalForm.showModal()"
                        class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Cetak
                    </button>
                </div>
            </div>
            <div class="table-responsive text-base">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead class="text-base uppercase text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl">
                            <th>NIM</th>
                            <th>Nama</th>
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
                                <td class="">{{ $sempro->mahasiswa->nim }}</td>
                                <td class="">{{ $sempro->mahasiswa->nama_lengkap }}</td>
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

            const dialog = document.getElementById("modalForm");

            function closeDialog() {
                dialog.close();
            }
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
                    targets: [1, 2, 3, 4, 5, 6, 7],
                    className: "dt-head-center text-sm"
                },
                {
                    width: "15%",
                    targets: 1
                },
                {
                    width: "5%",
                    targets: 2,
                },
                {
                    width: "6%",
                    targets: 3,
                    className: "text-center"
                },
                {
                    width: "14%",
                    targets: 4,
                    className: "text-center"
                },
                {
                    width: "25%",
                    targets: 5
                },
                {
                    width: "25%",
                    targets: 6
                },
            ],
            search: {
                search: ""
            }
        });
    </script>
    @endsection
</x-app-layout>