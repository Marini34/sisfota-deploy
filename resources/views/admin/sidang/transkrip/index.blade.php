<x-app-layout>
    @section('title', 'Kelola Transkrip')
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

            #makul_length>label>select {
                width: 60px;
                /* Atur lebar sesuai kebutuhan */
                border-radius: 5px;
            }

            #makul_paginate>span>a {
                border-radius: 5px;
                /* Tambahkan sudut bulat pada tombol */
                padding: 0;
                margin-right: 3px;
                margin-left: 3px;
                margin-top: 4px;
            }

            #makul_previous,
            #makul_next {
                padding: 0px 0px;
                border: 1px solid rgb(154, 147, 147);
                border-radius: 5px;
            }

            #makul_previous {
                margin-right: 2px;
            }

            #makul_next:hover {
                background-color: #ddd;
                /* Warna latar belakang tombol saat dihover */
            }

            #makul_paginate>span>span {
                padding: 2px;
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

        <div class="text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                    Kelola Transkrip Nilai Mahasiswa
                </h5>
            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead class="text-base uppercase text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl">
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Makul</th>
                            <th>SKS</th>
                            <th>IPK</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statusTranskrip as $transkrip)
                            {{-- Tolak Modal --}}
                            <div class="modal-content">
                                <dialog id="modalTolak_{{ $transkrip->id }}"
                                    class="miniscrollbar bg-white rounded-lg shadow-2xl w-2/3 h-2/3">
                                    <div
                                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                        <h3
                                            class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                                            Tolak Pendaftaran
                                        </h3>
                                        <button onclick="closeModalTolak('{{ $transkrip->id }}')" type="button"
                                            class="float-end text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-toggle="timeline-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.transkrip.tolak', $transkrip->id) }}" method="post"
                                        class="p-2">
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
                            <tr>
                                <td class="font-medium">{{ $transkrip->mahasiswa->nama_lengkap }}</td>
                                <td class="font-medium">{{ $transkrip->mahasiswa->nim }}</td>
                                <td class="text-center font-medium">{{ $transkrip->jumlah_makul }}</td>
                                <td class="text-center font-medium">{{ $transkrip->jumlah_sks }}</td>
                                <td class="text-center font-medium">{{ $transkrip->ipk }}</td>
                                <td class="text-center whitespace-nowrap">
                                    <a href="{{ route('admin.transkrip.detail', $transkrip->mahasiswa_id) }}"
                                        data-tooltip-target="detail_{{ $transkrip->id }}"
                                        data-tooltip-placement="bottom" type="button"
                                        class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <div id="detail_{{ $transkrip->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Detail
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <form action="{{ route('admin.transkrip.terima', $transkrip->id) }}"
                                        method="post" class="inline">
                                        @csrf
                                        <button data-tooltip-target="terima_{{ $transkrip->id }}"
                                            data-tooltip-placement="bottom" type="submit"
                                            class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-2 py-1 text-center">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    <div id="terima_{{ $transkrip->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Terima
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>

                                    <button data-tooltip-target="tolak_{{ $transkrip->id }}"
                                        data-tooltip-placement="bottom" type="button"
                                        onclick="showModalTolak('{{ $transkrip->id }}')"
                                        class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-2 py-1 text-center">
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                    <div id="tolak_{{ $transkrip->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Tolak
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
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <p class="font-medium text-2xl mb-3">Tambah Mata Kuliah</p>
            <form action="{{ route('admin.transkrip.store.makul') }}" method="POST" class="">
                @csrf
                <table class="inline mr-2">
                    <tbody>
                        <tr>
                            <td>
                                <label for="kodeMakul" class="font-bold text-lg">Kode Mata Kuliah</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="kodeMakul" id="kodeMakul"
                                    value="{{ old('kodeMakul') }}" required
                                    class="text-lg mr-5 font-semibold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-40 leading-tight focus:outline-none focus:border-blue-500">
                                @error('kodeMakul')
                                    <br>
                                    @php
                                        $translate =
                                            $message == 'validation.unique' ? 'kode makul sudah ada' : $message;
                                    @endphp
                                    <p class="text-red-500 text-base">{{ $translate }}</p>
                                @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="inline mr-2">
                    <tbody>
                        <tr>
                            <td>
                                <label for="namaMakul" class="font-bold text-lg">Nama Mata Kuliah</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="namaMakul" id="namaMakul"
                                    value="{{ old('namaMakul') }}" required
                                    class="text-lg mr-5 font-semibold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-72 md:w-80 leading-tight focus:outline-none focus:border-blue-500">
                                @error('namaMakul')
                                    <br>
                                    <div>
                                        @php
                                            $translate =
                                                $message == 'validation.unique' ? 'nama makul sudah ada' : $message;
                                        @endphp
                                        <p class="text-red-500 text-base">{{ $translate }}</p>
                                    </div>
                                @enderror
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="inline mr-2">
                    <tbody>
                        <tr>
                            <td>
                                <label for="sks" class="font-bold text-lg">Jumlah SKS</label>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="number" name="sks" id="sks" min="0" required
                                    class="text-lg mr-5 font-semibold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-24 leading-tight focus:outline-none focus:border-blue-500">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="inline md:float-end">
                    <tbody>
                        <tr>
                            <td class="pb-2">
                                <br>
                            </td>
                        </tr>
                        <tr>
                            <td class="">
                                <button type="submit"
                                    class="text-center md:float-end text-white font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2">
                                    Submit
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>

        <hr class="my-4 text-gray-900">

        <div class="table-responsive">
            <table id="makul" class="table table-striped table-hover table-bordered">
                <thead class="text-base uppercase text-slate-800">
                    <tr class="bg-slate-300 rounded-2xl">
                        <th>Kode Mata Kuliah</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($makuls as $index => $makul)
                     {{-- Edit Modal --}}
                     <div class="modal-content">
                        <dialog id="edit_{{ $makul->id }}"
                            class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-2/5">
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                    Edit Mata Kuliah
                                </h3>
                                <button onclick="closeEditModal('{{ $makul->id }}')" type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                    data-modal-toggle="timeline-modal">
                                    <svg class="w-3 h-3" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <form action="{{ route('admin.transkrip.edit.makul', $makul->id) }}" method="post" class="p-2">
                                @csrf
                                <div class="mx-5 mb-3">
                                    <div class="my-2">
                                        <label for="editKodeMakul">Kode Mata Kuliah</label>
                                        <input type="text" name="editKodeMakul" id="editKodeMakul" value="{{ $makul->kode_makul }}" required
                                        class="w-full rounded-md border-slate-300" >
                                        @error('editKodeMakul')
                                            <br>
                                            @php
                                                $translate = $message == 'validation.unique' ? 'makul sudah ada di transkrip' : $message;
                                            @endphp
                                            <p class="text-red-500 text-base">{{ $translate }}</p>
                                        @enderror
                                    </div>

                                    <div class="my-2">
                                        <label for="editNamaMakul">Kode Mata Kuliah</label>
                                        <input type="text" name="editNamaMakul" id="editNamaMakul" value="{{ $makul->nama_makul }}" required
                                        class="w-full rounded-md border-slate-300" >
                                        @error('editNamaMakul')
                                            <br>
                                            @php
                                                $translate = $message == 'validation.unique' ? 'makul sudah ada di transkrip' : $message;
                                            @endphp
                                            <p class="text-red-500 text-base">{{ $translate }}</p>
                                        @enderror
                                    </div>
                                    <div class="my-2">
                                        <label for="editSks">SKS</label>
                                        <input type="number" name="editSks" id="editSks" value="{{ $makul->sks }}" min="1"
                                        class="w-full rounded-md border-slate-300" >
                                        @error('sks')
                                            <br>
                                            @php
                                                $translate = $message == 'validation.unique' ? 'makul sudah ada di transkrip' : $message;
                                            @endphp
                                            <p class="text-red-500 text-base">{{ $translate }}</p>
                                        @enderror
                                    </div>
                                    
                                    <button type="submit"
                                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                                        SIMPAN
                                    </button>
                                </div>
                            </form>
                        </dialog>
                    </div>
                    {{-- End edit Modal --}}
                    {{-- Confirmation Cancel Modal --}}
                        <dialog id="konfirmasiHapus_{{ $makul->id }}"
                            class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                            <div class="relative">
                                <button onclick="closeDeleteConfirm('{{ $makul->id }}')" type="button"
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
                                    <svg class="mx-auto mb-4 text-red-700 w-12 h-12 dark:text-gray-200"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="2"
                                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <h3 class="mb-5 text-lg font-bold text-red-700">
                                        Apakah kamu yakin untuk menghapus mata kuliah ini? Menghapus makul akan berpengaruh banyak terhadap transkrip banyak mahasiswa.
                                    </h3>

                                    <form action="{{ route('admin.transkrip.hapus.makul', $makul->id) }}"
                                        method="POST" class="inline">
                                        @method('delete')
                                        @csrf
                                        <button type="submit"
                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                            Ya, saya yakin
                                        </button>
                                    </form>
                                    <button onclick="closeDeleteConfirm('{{ $makul->id }}')" type="button"
                                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                        Tidak, batal
                                    </button>
                                </div>
                            </div>
                        </dialog>
                        {{-- End Confirmation Cancel Modal --}}
                        <tr>
                            <td>{{ $makul->kode_makul }}</td>
                            <td>{{ $makul->nama_makul }}</td>
                            <td>{{ $makul->sks }}</td>
                            <td class="whitespace-nowrap">
                                <button type="button" onclick="showEditModal('{{ $makul->id }}')"
                                    data-tooltip-target="ubah_{{ $makul->id }}"
                                    data-tooltip-placement="bottom" type="button"
                                    class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-2 py-1 text-center">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <div id="ubah_{{ $makul->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Ubah
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>

                                <button data-tooltip-target="hapus_{{ $makul->id }}"
                                    data-tooltip-placement="bottom" type="button"
                                    onclick="showDeleteConfirm('{{ $makul->id }}')"
                                    class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-2 py-1 text-center">
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                                <div id="hapus_{{ $makul->id }}" role="tooltip"
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
    @section('script')
        <script>
            dropDownTranskrip()

            function showModalTolak(id) {
                var modal = document.getElementById('modalTolak_' + id);
                modal.showModal();
            }

            function closeModalTolak(id) {
                var modal = document.getElementById('modalTolak_' + id);
                modal.close();
            }

            function showEditModal(id) {
                var modal = document.getElementById('edit_' + id);
                modal.showModal();
            }

            function closeEditModal(id) {
                var modal = document.getElementById('edit_' + id);
                modal.close();
            }

            function showDeleteConfirm(id) {
                var modal = document.getElementById('konfirmasiHapus_' + id);
                modal.showModal();
            }

            function closeDeleteConfirm(id) {
                var modal = document.getElementById('konfirmasiHapus_' + id);
                modal.close();
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
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                    emptyTable: "Belum ada data yang dapat dikelola"
                },
                columnDefs: [{
                        targets: [2, 3, 4, 5],
                        orderable: false,
                    },
                    {
                        targets: [0, 1, 2, 3, 4, 5],
                        className: "dt-head-center"
                    },
                    {
                        width: "35",
                        targets: 0
                    },
                    {
                        width: "20%",
                        targets: 1
                    },
                    {
                        width: "10%",
                        targets: 2
                    },
                    {
                        width: "10%",
                        targets: 3
                    },
                    {
                        width: "10%",
                        targets: 4
                    },
                    {
                        width: "15%",
                        targets: 5
                    }
                ],
                search: {
                    search: ""
                }
            });

            let tableMakul = new DataTable('#makul', {
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
                        targets: [0, 1, 2, 3],
                        className: "dt-head-center"
                    },
                    {
                        width: "20%",
                        targets: 0,
                        className: "text-center"
                    },
                    {
                        width: "60",
                        targets: 1
                    },
                    {
                        width: "10%",
                        className: "text-center",
                        targets: 2
                    },
                    {
                        width: "10%",
                        targets: 3,
                        className: "text-center"
                    }
                ],
                search: {
                    search: ""
                }
            });
        </script>

        <script type="text/javascript"></script>
    @endsection
</x-app-layout>
