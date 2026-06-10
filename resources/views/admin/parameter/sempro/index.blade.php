<x-app-layout>
    @section('title', 'Kelola Parameter Penilaian Sempro')
    @section('head')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <style>
            .trix-editor {
                width: 100%;
                min-height: 6.2rem;
            }

            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
            }

            @media (max-width: 640px) {
                .trix-editor {
                    width: 100%;
                    min-height: 18rem;
                }
            }
        </style>
    @endsection
    @if (session()->has('success'))
        <div id="alert-3"
            class="flex items-center p-3 mb-4 text-white rounded-lg bg-green-400 dark:bg-gray-800 dark:text-green-400"
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
        <div id="alert-2" class="flex items-center p-3 mb-4 text-white rounded-lg bg-red-700" role="alert">
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
        <div>
            <form action="{{route('edit.passing.grade.sempro')}}" method="post">
                @csrf
                <label for="passingGrade" class="font-bold text-lg">Nilai Standar Kelulusan Sempro :</label>
                <input id="passingGrade" name="passingGrade" type="number" min="0" max="100"
                    value="{{ $passingGrade->nilai }}"
                    class="text-lg mr-5 font-semibold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-16 p-1 leading-tight focus:outline-none focus:border-blue-500">
                <button type="submit"
                    class="cursor-pointer sm:float-end text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                    Simpan
                </button>
            </form>
        </div>
    </x-main-card>
    <x-main-card>
        @if ($parameters->count() && $totalPersentase < 100)
            <div
                class="w-full relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-orange-500 rounded-lg group border-2 border-orange-500">
                <span
                    class="relative px-2 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                    <i class="bi bi-exclamation-triangle-fill text-base"></i>
                    TOTAL BOBOT BELUM MENCAPAI 100%
                </span>
            </div>
        @endif

        {{-- Modal --}}
        <dialog id="modalForm" class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 fixed">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                    Tambah Parameter Penilaian Sempro
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
            <form action="{{ route('store.parameter.sempro') }}" method="post" class="p-4 md:p-5">
                @csrf
                <label for="nama">Nama Parameter<span class="text-red-600">*</span></label>
                <div class="my-2">
                    <input type="text" name="nama" id="nama" class="w-full rounded-md border-slate-300"
                        required></input>
                </div>
                <br>
                <label for="deskripsi">Deskripsi Parameter<span class="text-red-600">*</span></label>
                <div class="border border-slate-300 rounded-md">
                    <input id="deskripsi" type="hidden" name="deskripsi">
                    <trix-editor input="deskripsi" class="trix-editor"
                        placeholder="Masukkan deskripsi parameter..."></trix-editor>
                </div>
                <br>
                <label for="persentase">Pesentase Parameter<span class="text-red-600">*</span></label>
                <div class="my-2">
                    <input type="number" min="0" max="{{ 100 - $totalPersentase }}" name="persentase"
                        id="persentase" class="w-full rounded-md border-slate-300" required>
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
                    Kelola Parameter Penilaian Sempro
                </h5>
                @if ($totalPersentase < 100)
                <div class="float-end text-gray-900 dark:text-gray-100">
                    <button type="button" onclick="modalForm.showModal()"
                        class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Tambah
                    </button>
                </div>
                @endif
            </div>
            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-bordered table-hover">
                    <thead class="text-base text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl text-center">
                            <th style="width: 5%">No</th>
                            <th style="width: 20%">Nama Parameter</th>
                            <th style="width: 50%">Deskripsi Parameter</th>
                            <th style="width: 10%">Bobot</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-base text-slate-800">
                        @if ($parameters->count())
                            @foreach ($parameters as $index => $parameter)
                                {{-- Confirmation Cancel Modal --}}
                                <dialog id="confirmDialog_{{ $parameter->id }}"
                                    class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                                    <div class="relative">
                                        <button onclick="closeConfirmDialog('{{ $parameter->id }}')" type="button"
                                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                            <svg class="w-3 h-3" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 14 14">
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
                                                Apakah kamu yakin untuk menghapus parameter penilaian ini?
                                            </h3>

                                            <form action="{{ route('delete.parameter', $parameter->id) }}"
                                                method="POST" enctype="multipart/form-data" class="inline">
                                                @method('delete')
                                                @csrf
                                                <button type="submit"
                                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                    Ya, saya yakin
                                                </button>
                                            </form>
                                            <button onclick="closeConfirmDialog('{{ $parameter->id }}')"
                                                type="button"
                                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                                Tidak, batal
                                            </button>
                                        </div>
                                    </div>
                                </dialog>
                                {{-- End Confirmation Cancel Modal --}}
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}.</td>
                                    <td>{{ $parameter->nama_parameter }}</td>
                                    <td>
                                        @php
                                            $formattedText = $parameter->deskripsi_parameter;
                                            if (strpos($formattedText, '<ol>') !== false) {
                                                $formattedText = str_replace(
                                                    '<ol>',
                                                    '<ol class="list-decimal pl-7">',
                                                    $formattedText,
                                                );
                                            }
                                            if (strpos($formattedText, '<ul>') !== false) {
                                                $formattedText = str_replace(
                                                    '<ul>',
                                                    '<ul class="list-disc pl-7">',
                                                    $formattedText,
                                                );
                                            }
                                            echo $formattedText;
                                        @endphp
                                    </td>
                                    <td class="text-center">{{ $parameter->persentase }}%</td>
                                    <td class="text-center">
                                        <a href="/kaprodi/parameter-sempro/edit/{{ $parameter->id }}"
                                            data-tooltip-target="ubah_{{ $parameter->id }}"
                                            data-tooltip-placement="bottom" type="button"
                                            class="mb-2 cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <div id="ubah_{{ $parameter->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Edit
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>

                                        <button data-tooltip-target="hapus_{{ $parameter->id }}"
                                            data-tooltip-placement="bottom" type="button"
                                            onclick="showConfirm('{{ $parameter->id }}')"
                                            class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>

                                        <div id="hapus_{{ $parameter->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Hapus
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Belum ada parameter penilaian yang ditambahkan
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </x-main-card>
    @section('script')
        <script>
            // open dropdown menu
            dropDownParameter()

            // close modal
            const dialog = document.getElementById("modalForm");

            function closeDialog() {
                dialog.close();
            }

            // Function to show modal for a specific seminar proposal
            function showEditModal(id) {
                var modal = document.getElementById('modalEdit_' + id);
                modal.showModal();
            }

            function closeEditModal(id) {
                var modal = document.getElementById('modalEdit_' + id);
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
        </script>
    @endsection
</x-app-layout>
