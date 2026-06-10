<x-app-layout>
    @section('title', 'Kelola Pengguna Mahasiswa')
    @section('head')
        @include('styles.datatables')
        <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <style>
            /* show entries */
            #myTable2_length>label>select {
                width: 60px;
                /* Atur lebar sesuai kebutuhan */
                border-radius: 5px;
            }

            .dataTables2_length {
                margin-bottom: 10px;
            }

            #myTable2_paginate>span>a {
                border-radius: 5px;
                /* Tambahkan sudut bulat pada tombol */
                padding: 0;
                margin-right: 3px;
                margin-left: 3px;
                margin-top: 4px;
            }

            #myTable2_previous,
            #myTable2_next {
                padding: 0px 0px;
                border: 1px solid rgb(154, 147, 147);
                border-radius: 5px;
            }

            #myTable2_previous {
                margin-right: 2px;
            }

            #myTable2_next:hover {
                background-color: #ddd;
                /* Warna latar belakang tombol saat dihover */
            }

            #myTable2_paginate>span>span {
                padding: 2px;
            }
        </style>
    @endsection
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
    <x-main-card>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
                Verifikasi Akun Pengguna Mahasiswa
            </h3>
        <div class="table-responsive">
            <table id="myTable2" class="table table-striped table-hover table-bordered">
                <thead class="text-base uppercase text-slate-800">
                    <tr class="bg-slate-300 rounded-2xl">
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Status Keaktifan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        {{-- Confirmation Cancel Modal --}}
                        <dialog id="confirmDialog_{{ $user->id }}"
                            class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                            <div class="relative">
                                <button onclick="closeConfirmDialog('{{ $user->id }}')" type="button"
                                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <div class="p-4 md:p-5 text-center">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                        Apakah kamu yakin untuk menghapus pengguna mahasiswa ini?
                                    </h3>

                                    <form action="{{ route('admin.kelola.mahasiswa.delete', $user->id) }}"
                                        method="POST" enctype="multipart/form-data" class="inline">
                                        @method('delete')
                                        @csrf
                                        <button type="submit"
                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                            Ya, saya yakin
                                        </button>
                                    </form>
                                    <button onclick="closeConfirmDialog('{{ $user->id }}')" type="button"
                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                        Tidak, batal
                                    </button>
                                </div>
                            </div>
                        </dialog>
                        {{-- End Confirmation Cancel Modal --}}
                        <tr>
                            <td class="">{{ $user->name }}</td>
                            <td>
                                <div class="">
                                    {{ $user->nim_nip }}
                                </div>
                            </td>
                            <td class="text-center">
                                    <div class="p-2 w-auto text-sm text-gray-800 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                                        Belum terdaftar sistem
                                    </div>
                            </td>
                            <td class="whitespace-nowrap text-center">
                                <form class="inline" action="{{ route('admin.kelola.mahasiswa.terima', $user->id) }}" method="POST">
                                    @csrf
                                    <button data-tooltip-target="detail_{{ $user->id }}" data-tooltip-placement="bottom" type="submit"
                                        class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-2 py-1 mx-1 text-center">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>

                                <div id="detail_{{ $user->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Terima
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <form class="inline" action="{{ route('admin.kelola.mahasiswa.tolak', $user->id) }}" method="POST">
                                    @csrf
                                    <button data-tooltip-target="hapus_{{ $user->id }}" data-tooltip-placement="bottom" type="submit"
                                        class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>

                                <div id="hapus_{{ $user->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Hapus
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-main-card>
    <x-main-card>
        {{-- Modal --}}
        <dialog id="modalForm" class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 md:w-2/5">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                    Tambah Pengguna Mahasiswa
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
            <form action="{{ route('admin.kelola.mahasiswa.store') }}" method="post" class="p-4 md:p-5">
                @csrf
                <label for="nama">Nama Mahasiswa</label>
                <div class="my-2">
                    <input type="text" name="nama" id="nama" class="w-full rounded-md border-slate-300"
                        required></input>
                </div>

                <label for="nim">NIM</label>
                <div class="my-2">
                    <input type="text" name="nim" id="nim" class="w-full rounded-md border-slate-300"
                        required>
                </div>

                <label for="jenis_kelamin">Jenis Kelamin</label>
                <div class="my-2">
                    <select name="jenis_kelamin" id="jenis_kelamin" class="w-full rounded-md border-slate-300" required>
                        <option value="" disabled selected hidden>Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <label for="alamat">Alamat Mahasiswa</label>
                <div class="my-2">
                    <textarea name="alamat" id="alamat" class="w-full rounded-md border-slate-300"></textarea>
                </div>

                <label for="email">Email</label>
                <div class="my-2">
                    <input type="email" name="email" id="email" class="w-full rounded-md border-slate-300"
                        required>
                </div>

                <label for="password">Password</label>
                <div class="my-2">
                    <input type="text" name="password" id="password" class="w-full rounded-md border-slate-300"
                        required>
                </div>

                <br>
                <button type="submit"
                    class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                    TAMBAH
                </button>
            </form>
        </dialog>
        {{-- End Modal --}}
        <div class="text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                    Kelola Pengguna Mahasiswa
                </h5>
                <div class="float-end text-gray-900 dark:text-gray-100">
                    <button type="button" onclick="modalForm.showModal()"
                        class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Tambah
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead class="text-base uppercase text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl">
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Status Keaktifan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswas as $mahasiswa)
                            {{-- Confirmation Cancel Modal --}}
                            <dialog id="confirmDialog_{{ $mahasiswa->id }}"
                                class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                                <div class="relative">
                                    <button onclick="closeConfirmDialog('{{ $mahasiswa->id }}')" type="button"
                                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <div class="p-4 md:p-5 text-center">
                                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2"
                                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                                            Apakah kamu yakin untuk menghapus pengguna mahasiswa ini?
                                        </h3>

                                        <form action="{{ route('admin.kelola.mahasiswa.delete', $mahasiswa->id) }}"
                                            method="POST" enctype="multipart/form-data" class="inline">
                                            @method('delete')
                                            @csrf
                                            <button type="submit"
                                                class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                Ya, saya yakin
                                            </button>
                                        </form>
                                        <button onclick="closeConfirmDialog('{{ $mahasiswa->id }}')" type="button"
                                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                            Tidak, batal
                                        </button>
                                    </div>
                                </div>
                            </dialog>
                            {{-- End Confirmation Cancel Modal --}}
                            <tr>
                                <td class="">{{ $mahasiswa->nama_lengkap }}</td>
                                <td>
                                    <div class="">
                                        {{ $mahasiswa->nim }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($mahasiswa->user->status === 'Aktif')
                                        <div class="p-2 w-auto text-sm text-blue-800 text-center uppercase font-bold border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800">
                                    @else
                                        <div class="p-2 w-auto text-sm text-gray-800 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                                    @endif
                                    {{ $mahasiswa->user->status }}
                                        </div>
                                </td>
                                <td class="whitespace-nowrap text-center">
                                    <a href="/admin/kelola-mahasiswa/detail/{{ $mahasiswa->id }}"
                                        data-tooltip-target="detail_{{ $mahasiswa->id }}"
                                        data-tooltip-placement="bottom" type="button"
                                        class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <div id="detail_{{ $mahasiswa->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Detail
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <a href="/admin/kelola-mahasiswa/ubah/{{ $mahasiswa->id }}"
                                        data-tooltip-target="ubah_{{ $mahasiswa->id }}"
                                        data-tooltip-placement="bottom" type="button"
                                        class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <div id="ubah_{{ $mahasiswa->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Ubah
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    {{-- <button data-tooltip-target="hapus_{{ $mahasiswa->id }}"
                                        data-tooltip-placement="bottom" type="button"
                                        onclick="showConfirm('{{ $mahasiswa->id }}')"
                                        class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>

                                    <div id="hapus_{{ $mahasiswa->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Hapus
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div> --}}
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
            dropDownPengguna()

            // close modal
            const dialog = document.getElementById("modalForm");

            function closeDialog() {
                dialog.close();
            }

            // Function to show modal for a specific seminar proposal
            function showModal(slug) {
                var modal = document.getElementById('modalVideo_' + slug);
                modal.showModal();
            }


            function showConfirm(slug) {
                var modal = document.getElementById('confirmDialog_' + slug);
                modal.showModal();
            }

            function closeConfirmDialog(slug) {
                var modal = document.getElementById('confirmDialog_' + slug);
                modal.close();
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
            let table2 = new DataTable('#myTable2', {
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
                        orderable: false,
                        className: "dt-head-center"
                    },
                    {
                        targets: [0, 1, 2],
                        className: "dt-head-center"
                    },
                    {
                        width: "30%",
                        targets: 0
                    },
                    {
                        width: "30",
                        targets: 1
                    },
                    {
                        width: "20%",
                        targets: 2
                    },
                    {
                        width: "20%",
                        targets: 3
                    },
                ],
                search: {
                    search: ""
                }
            });
        </script>
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
                        orderable: false,
                        className: "dt-head-center"
                    },
                    {
                        targets: [0, 1, 2],
                        className: "dt-head-center"
                    },
                    {
                        width: "30%",
                        targets: 0
                    },
                    {
                        width: "30",
                        targets: 1
                    },
                    {
                        width: "20%",
                        targets: 2
                    },
                    {
                        width: "20%",
                        targets: 3
                    },
                ],
                search: {
                    search: ""
                }
            });
        </script>
    @endsection
</x-app-layout>
