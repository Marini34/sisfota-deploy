<x-app-layout>
    @section('title', 'Detail Transkrip Nilai Mahasiswa')
    @section('head')
        @include('styles.datatables')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <style>
            .trix-editor {
                width: 100%;
                height: 11rem;
            }

            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
            }
        </style>
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
         {{-- Confirmation Cancel Modal --}}
         <dialog id="confirmDialog_{{ $statusTranskrip->id }}"
            class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
            <div class="relative">
                <button onclick="closeConfirmDialog('{{ $statusTranskrip->id }}')" type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        Apakah kamu yakin untuk membatalkan verifikasi transkrip ini?
                    </h3>
                    <form action="{{ route('admin.transkrip.batal', $statusTranskrip->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Ya, saya yakin
                        </button>
                    </form>
                    <button onclick="closeConfirmDialog('{{ $statusTranskrip->id }}')" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                        Tidak, batal
                    </button>
                </div>
            </div>
        </dialog>
        {{-- End Confirmation Cancel Modal --}}
        {{-- Tolak Modal --}}
        <div class="modal-content">
            <dialog id="modalTolak"
                class="miniscrollbar bg-white rounded-lg shadow-2xl w-2/3 h-2/3">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                        Tolak Pendaftaran
                    </h3>
                    <button onclick="closeModalTolak()" type="button"
                        class="float-end text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="timeline-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form action="{{ route('admin.transkrip.tolak', $statusTranskrip->id) }}" method="post" class="p-2">
                    @csrf
                    <div class="mx-5 mb-3">
                        <input id="tolak" type="hidden" name="tolak">
                        <trix-editor input="tolak" class="trix-editor"
                            placeholder="Masukkan alasan penolakan..."></trix-editor>
                        <button type="submit"
                            class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-red-600 to-red-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg px-5 py-2.5 me-2 mt-5">
                            TOLAK
                        </button>
                    </div>
                </form>
            </dialog>
        </div>
        {{-- End Tolak Modal --}}
        <div class="text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                    Transkrip Nilai <span class="capitalize">{{ $transkrips[0]->mahasiswa->nama_lengkap }}</span>
                </h5>
                <div class="float-end text-gray-900 dark:text-gray-100">
                    @if ($statusTranskrip->status_transkrip == 'Diterima' || $statusTranskrip->status_transkrip == 'Ditolak')
                    <button data-tooltip-target="tolak"
                        data-tooltip-placement="bottom" type="button"
                        onclick="showConfirm('{{ $statusTranskrip->id }}')"
                        class="text-white bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-bold tracking-wider uppercase rounded-lg px-7 py-2 text-center">
                        Batalkan
                    </button>
                    @else
                    <button data-tooltip-target="tolak"
                        data-tooltip-placement="bottom" type="button"
                        onclick="showModalTolak()"
                        class="text-white bg-red-500 focus:ring-4 focus:outline-none focus:ring-red-300 font-bold tracking-wider uppercase rounded-lg px-7 py-2 text-center">
                        Tolak
                    </button>

                    <form action="{{route('admin.transkrip.terima', $statusTranskrip->id)}}" method="post" class="inline">
                        @csrf
                        <button data-tooltip-target="terima_{{$statusTranskrip->id}}" data-tooltip-placement="bottom" type="submit"
                            class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-bold tracking-wider uppercase rounded-lg px-7 py-2 text-center">
                            Terima
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            <div class="mb-4">
                <div>
                    <b>Makul : {{ $transkrips->count() }}</b>
                </div>
                <div>
                    <b>IPK : {{ $ipkAkhir }}</b>
                </div>
                <div>
                    <b>SKS : {{ $jumlahSks }}</b>
                </div>
                <div class="flex items-center">
                    <b>File Transkrip :</b> <x-new-modal :file="$statusTranskrip->file_transkrip" />
                </div>
            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead class="text-base uppercase text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl">
                            <th>No</th>
                            <th>Kode Mata Kuliah</th>
                            <th>Nama Mata Kuliah</th>
                            <th>SKS</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transkrips as $index => $transkrip)
                            @if ($transkrip->makul)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $transkrip->makul->kode_makul }}</td>
                                    <td>{{ $transkrip->makul->nama_makul }}</td>
                                    <td>{{ $transkrip->makul->sks }}</td>
                                    <td>{{ $transkrip->nilai }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-main-card>
    @section('script')
        <script>
            dropDownTranskrip()

            function showModalTolak() {
                var modal = document.getElementById('modalTolak');
                modal.showModal();
            }

            function closeModalTolak() {
                var modal = document.getElementById('modalTolak');
                modal.close();
            }

            function showConfirm(id) {
                var modal = document.getElementById('confirmDialog_' + id);
                modal.showModal();
            }

            function closeConfirmDialog(id) {
                var modal = document.getElementById('confirmDialog_' + id);
                modal.close();
            }

            function show(file) {
                var modal = document.getElementById('lampiran_' + file);
                modal.showModal();
            }

            function closeLampiran(file) {
                var modal = document.getElementById('lampiran_' + file);
                modal.close();
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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
                    emptyTable: "Data Transkrip Nilai Belum Ada"
                },
                columnDefs: [{
                        targets: [-1],
                        orderable: false,
                        className: "dt-head-center"
                    },
                    {
                        targets: [0, 1, 2, 3, 4],
                        className: "dt-head-center"
                    },
                    {
                        width: "5%",
                        targets: 0,
                        className: "text-center"
                    },
                    {
                        width: "25",
                        targets: 1
                    },
                    {
                        width: "50%",
                        targets: 2
                    },
                    {
                        width: "10%",
                        targets: 3,
                        className: "text-center"
                    },
                    {
                        width: "10%",
                        targets: 4,
                        className: "text-center"
                    },
                ],
                search: {
                    search: ""
                }
            });
        </script>
    @endsection
</x-app-layout>