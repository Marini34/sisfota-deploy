<x-app-layout>
    @section('title', 'Sidang Akhir')
    @section('head')
        @include('styles.datatables')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
        <style>
            .select2-container--default .select2-selection--single {
                height: 40px; /* Tinggi dari kotak pilihan */
                border: 1px solid #ddd; /* Gaya border */
                border-radius: 4px; /* Rounded corners */
                background-color: rgb(229 231 235);
            }

            .select2-selection__rendered {
                color: red;
            }

            .select2-selection__placeholder{
                font-size: 1.125rem;
                line-height: 1.75rem;
            }

            #select2-makul-container {
                margin-top: 0.4rem;
            }

            .select2-selection__arrow{
                height: 5px;
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

        @if (
            $seminarSidang &&
                $rekamBimbingans1 >= 8 &&
                $rekamBimbingans2 >= 8 &&
                (($seminarSidang->tahapan_ta === 'Seminar Hasil' && $seminarSidang->status_kelulusan === 'LULUS') ||
                    $seminarSidang->tahapan_ta === 'Sidang Akhir'))
            <div id="alert-additional-content-1"
                class="p-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
                role="alert">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <h3 class="font-medium">Sebelum melakukan pendaftaran sidang akhir, kamu perlu mengisi transkrip
                        nilai kamu</h3>
                </div>
            </div>

            <div class="mt-4 block overflow-x-auto">
                <form action="{{ route('store.transkrip') }}" method="POST" class="block">
                    @csrf
                    <table class="inline mr-3">
                        <tbody>
                            <tr>
                                <td>
                                    <label for="makul" class="font-bold text-lg">Mata Kuliah</label>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="makul" id="makul" required
                                        class="text-lg mr-5 font-semibold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-72 px-1 py-2.5 leading-tight focus:outline-none focus:border-blue-500">
                                        {{-- <option value="" disabled selected hidden>Pilih Mata Kuliah</option>
                                        @foreach ($makuls as $makul)
                                            <option value="{{ $makul->id }}" @if (old('makul') == $makul->id ) selected @endif >{{ $makul->nama_makul }}</option>
                                        @endforeach --}}
                                    </select>
                                    @error('makul')
                                        <br>
                                        @php
                                            $translate = $message == 'validation.unique' ? 'makul sudah ada di transkrip' : $message;
                                        @endphp
                                        <p class="text-red-500 text-base">{{ $translate }}</p>
                                    @enderror
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="inline">
                        <tbody>
                            <tr>
                                <td class="pb-2 text-lg font-bold">
                                    Nilai
                                </td>
                            </tr>
                            <tr>
                                <td class="md:whitespace-nowrap">
                                    <div
                                        class="inline border border-gray-300 bg-gray-200 rounded-lg py-2.5 justify-center">
                                        <label for="A"
                                            class="whitespace-nowrap px-1 py-2 mx-1 border-r border-gray-300 text-lg font-bold text-slate-700">
                                            <input required
                                                class="w-4 h-4 mr-1 mb-1 text-gray-600 focus:ring-gray-500 focus:ring-1"
                                                type="radio" id="A" name="nilai" value="A">
                                            <p class="inline-block h-12">A</p>
                                        </label>
                                        <label for="B+"
                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                            <input
                                                class="w-4 h-4 mr-1 mb-1 text-gray-600 focus:ring-gray-500 focus:ring-1"
                                                type="radio" id="B+" name="nilai" value="B+">
                                            <p class="inline-block h-12">B+</p>
                                        </label>
                                        <label for="B"
                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                            <input
                                                class="w-4 h-4 mr-1 mb-1 text-gray-600 focus:ring-gray-500 focus:ring-1"
                                                type="radio" id="B" name="nilai" value="B">
                                            <p class="inline-block h-12">B</p>
                                        </label>
                                        <label for="C+"
                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                            <input
                                                class="w-4 h-4 mr-1 mb-1 text-gray-600 focus:ring-gray-500 focus:ring-1"
                                                type="radio" id="C+" name="nilai" value="C+">
                                            <p class="inline-block h-12">C+</p>
                                        </label>
                                        <label for="C"
                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                            <input
                                                class="w-4 h-4 mr-1 mb-1 text-gray-600 focus:ring-gray-500 focus:ring-1"
                                                type="radio" id="C" name="nilai" value="C">
                                            <p class="inline-block h-12">C</p>
                                        </label>
                                        <label for="D+"
                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                            <input
                                                class="w-4 h-4 mr-1 mb-1 text-gray-600 focus:ring-gray-500 focus:ring-1"
                                                type="radio" id="D+" name="nilai" value="D+">
                                            <p class="inline-block h-12">D+</p>
                                        </label>
                                        <label for="D"
                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                            <input
                                                class="w-4 h-4 mr-1 mb-1 text-gray-600 focus:ring-gray-500 focus:ring-1"
                                                type="radio" id="D" name="nilai" value="D">
                                            <p class="inline-block h-12">D</p>
                                        </label>
                                        <label for="E"
                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-gray-300 text-lg font-bold text-slate-700">
                                            <input
                                                class="w-4 h-4 mr-1 mb-1 text-gray-600 focus:ring-gray-500 focus:ring-1"
                                                type="radio" id="E" name="nilai" value="E">
                                            <p class="inline-block h-12">E</p>
                                        </label>
                                    </div>
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

            <div class="overflow-x-auto miniscrollbar">
                <p>Tambahkan mata kuliah apabila tidak ada pada pilihan mata kuliah diatas</p>
                <form action="{{ route('store.makul') }}" method="POST" class="">
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
                                    <input type="text" name="kodeMakul" id="kodeMakul" value="{{ old('kodeMakul') }}" required
                                        class="text-lg mr-5 font-semibold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-40 leading-tight focus:outline-none focus:border-blue-500">
                                    @error('kodeMakul')
                                        <br>
                                        @php
                                            $translate = $message == 'validation.unique' ? 'kode makul sudah ada' : $message;
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
                                    <input type="text" name="namaMakul" id="namaMakul" value="{{ old('namaMakul') }}" required
                                        class="text-lg mr-5 font-semibold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-72 md:w-80 leading-tight focus:outline-none focus:border-blue-500">
                                    @error('namaMakul')
                                        <br>
                                        <div>
                                            @php
                                            $translate = $message == 'validation.unique' ? 'nama makul sudah ada' : $message;
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
                    <table class="inline">
                        <tbody>
                            <tr>
                                <td class="text-xl font-bold">
                                    Nilai
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <select name="nilaiBaru" id="nilaiBaru" required
                                        class="text-lg mr-5 font-bold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-auto px-1 py-2 leading-tight focus:outline-none focus:border-blue-500">
                                        <option value="" disabled selected hidden></option>
                                        <option value="A" class="font-bold">A</option>
                                        <option value="B+" class="font-bold">B+</option>
                                        <option value="B" class="font-bold">B</option>
                                        <option value="C+" class="font-bold">C+</option>
                                        <option value="C" class="font-bold">C</option>
                                        <option value="D+" class="font-bold">D+</option>
                                        <option value="D" class="font-bold">D</option>
                                        <option value="E" class="font-bold">E</option>
                                    </select>
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

            {{-- Confirmation Modal --}}
            <dialog id="confirmDialog"
                class="fixed p-2 rounded-lg shadow-2xl w-11/12 max-w-lg max-h-full">
                <div class="relative">
                    <button onclick="closeConfirmDialog()" type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-2 md:p-3 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-1 text-center text-gray-500 dark:text-gray-400">
                            Apakah kamu yakin telah menambahkan semua mata kuliah?<br>
                        </h3>
                        <div>
                            <p class="text-left mb-5 font-normal text-gray-500">
                            Jumlah Mata Kuliah : {{ $jumlahMakul }} <br>
                            Jumlah SKS : {{ $jumlahSks }} <br>
                            IPK : {{ round($ipkAkhir,2) }}
                            </p>
                        </div>
                        <hr class="text-slate-800 border-2 rounded-lg">
                        <form action="{{ route('konfirmasi.transkrip') }}"
                            method="POST" enctype="multipart/form-data" class="inline">
                            @csrf
                            <div class="my-2 text-left">
                                <label for="fileTranskrip"><span>Transkrip Akademik (diverifikasi kaprodi)</span></label>
                                <input id="fileTranskrip" name="fileTranskrip" type="file" required class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                <p class="text-sm">(.pdf)</p>
                            </div>
                            <hr class="text-slate-800 border-2 rounded-lg">
                            <div class="hidden">
                                <label for="jumlahMakul">Jumlah Makul</label>
                                <input type="number" name="jumlahMakul" id="jumlahMakul" value="{{ $jumlahMakul }}">
                                <label for="jumlahSks">Jumlah Sks</label>
                                <input type="number" id="jumlahSks" name="jumlahSks" value="{{ $jumlahSks }}">
                                <label for="jumlahMutu">Jumlah Mutu</label>
                                <input type="number" id="jumlahMutu" name="jumlahMutu" value="{{ $jumlahMutu }}">
                                <label for="ipk">IPK</label>
                                <input type="number" id="ipk" name="ipk" value="{{ $ipkAkhir }}">
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mt-6">
                                <button type="submit"
                                    class="text-white bg-blue-600 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300  font-medium rounded-lg px-5 py-2.5">
                                    Ya, saya yakin
                                </button>


                                <button onclick="closeConfirmDialog()" type="button"
                                    class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                    Tidak, batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </dialog>
            {{-- End Confirmation Modal --}}

            <div class="text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                        Transkrip Nilai Mahasiswa
                    </h5>
                    <div class="float-end text-gray-900 dark:text-gray-100">
                        <button type="button" onclick="showConfirm()"
                            class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-bold tracking-wider uppercase rounded-lg px-7 py-2 text-center">
                            KIRIM
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <div>
                        <b>Makul : {{ $transkrips->count() }}</b>
                    </div>
                    <div>
                        <b>IPK : {{ round($ipkAkhir,2) }}</b>
                    </div>
                    <div>
                        <b>SKS : {{ $jumlahSks }}</b>
                    </div>
                    <div>
                        <b>STATUS : {{ $statusTranskrip ? $statusTranskrip->status_transkrip : "Transkrip Belum Dikirim" }}</b>
                    </div>
                    <div class="flex items-center">
                        <b>File Transkrip :</b>
                        @if ($statusTranskrip)
                            <x-new-modal :file="$statusTranskrip->file_transkrip" />
                        @else
                            <b>Transkrip Belum Dikirim</b>
                        @endif
                    </div>
                    
                    
                    @if ($statusTranskrip && $statusTranskrip->status_transkrip == 'Ditolak')
                        <p>Alasan Penolakan</p>
                        <p class="font-medium">
                            @php
                                $textAlasan = $statusTranskrip->alasan_penolakan;
                                if (strpos($textAlasan, '<ol>') !== false) {
                                    $textAlasan = str_replace(
                                        '<ol>',
                                        '<ol class="list-decimal pl-7">',
                                        $textAlasan,
                                    );
                                }
                                if (strpos($textAlasan, '<ul>') !== false) {
                                    $textAlasan = str_replace(
                                        '<ul>',
                                        '<ul class="list-disc pl-7">',
                                        $textAlasan,
                                    );
                                }
                                echo $textAlasan;
                            @endphp
                        </p>
                    @endif
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
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transkrips as $index => $transkrip)
                                @if ($transkrip->makul) 
                                {{-- Edit Modal --}}
                                <div class="modal-content">
                                    <dialog id="edit_{{ $transkrip->uuid }}"
                                        class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-2/5">
                                        <div
                                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                                Edit Transkrip Mata Kuliah
                                            </h3>
                                            <button onclick="closeEditModal('{{ $transkrip->uuid }}')" type="button"
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
                                        <form action="{{ route('edit.transkrip', $transkrip->uuid) }}" method="post" class="p-2">
                                            @csrf
                                            <div class="mx-5 mb-3">
                                                <div class="my-2">
                                                    <label for="editMakul" class="font-bold text-lg">Mata Kuliah</label> <br>
                                                    <select name="editMakul" id="editMakul" required
                                                        class="text-lg mr-5 font-semibold bg-gray-200 appearance-none border-1 border-gray-200 rounded w-full px-1 py-2.5 leading-tight focus:outline-none focus:border-blue-500">
                                                        <option value="" disabled selected hidden>Pilih Mata Kuliah</option>
                                                        @foreach ($makuls as $makul)
                                                            <option value="{{ $makul->id }}" @if ($transkrip->makul_id == $makul->id ) selected @endif >{{ $makul->nama_makul }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="my-2">
                                                    <p class="text-lg font-bold mb-2">Nilai</p>
                                                    <div class="inline border border-gray-300 bg-gray-200 rounded-lg py-2.5 justify-center">
                                                        <label for="A"
                                                            class="whitespace-nowrap px-1 py-2 mx-1 border-r border-gray-300 text-lg font-bold text-slate-700">
                                                            <input required @if ($transkrip->nilai == "A" ) checked @endif
                                                                class="w-4 h-4 mr-1 mb-1 text-blue-600 focus:ring-blue-500 focus:ring-1"
                                                                type="radio" id="A" name="editNilai" value="A">
                                                            <p class="inline-block h-12">A</p>
                                                        </label>
                                                        <label for="B+"
                                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                                            <input @if ($transkrip->nilai == "B+" ) checked @endif
                                                                class="w-4 h-4 mr-1 mb-1 text-blue-600 focus:ring-blue-500 focus:ring-1"
                                                                type="radio" id="B+" name="editNilai" value="B+">
                                                            <p class="inline-block h-12">B+</p>
                                                        </label>
                                                        <label for="B"
                                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                                            <input @if ($transkrip->nilai == "B" ) checked @endif
                                                                class="w-4 h-4 mr-1 mb-1 text-blue-600 focus:ring-blue-500 focus:ring-1"
                                                                type="radio" id="B" name="editNilai" value="B">
                                                            <p class="inline-block h-12">B</p>
                                                        </label>
                                                        <label for="C+"
                                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                                            <input @if ($transkrip->nilai == "C+" ) checked @endif
                                                                class="w-4 h-4 mr-1 mb-1 text-blue-600 focus:ring-blue-500 focus:ring-1"
                                                                type="radio" id="C+" name="editNilai" value="C+">
                                                            <p class="inline-block h-12">C+</p>
                                                        </label>
                                                        <label for="C"
                                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                                            <input @if ($transkrip->nilai == "C" ) checked @endif
                                                                class="w-4 h-4 mr-1 mb-1 text-blue-600 focus:ring-blue-500 focus:ring-1"
                                                                type="radio" id="C" name="editNilai" value="C">
                                                            <p class="inline-block h-12">C</p>
                                                        </label>
                                                        <label for="D+"
                                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                                            <input  @if ($transkrip->nilai == "D+" ) checked @endif
                                                                class="w-4 h-4 mr-1 mb-1 text-blue-600 focus:ring-blue-500 focus:ring-1"
                                                                type="radio" id="D+" name="editNilai" value="D+">
                                                            <p class="inline-block h-12">D+</p>
                                                        </label>
                                                        <label for="D"
                                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-r border-gray-300 text-lg font-bold text-slate-700">
                                                            <input @if ($transkrip->nilai == "D" ) checked @endif
                                                                class="w-4 h-4 mr-1 mb-1 text-blue-600 focus:ring-blue-500 focus:ring-1"
                                                                type="radio" id="D" name="editNilai" value="D">
                                                            <p class="inline-block h-12">D</p>
                                                        </label>
                                                        <label for="E"
                                                            class="whitespace-nowrap pr-1 py-2 m-1 mt-3 border-gray-300 text-lg font-bold text-slate-700">
                                                            <input @if ($transkrip->nilai == "E" ) checked @endif
                                                                class="w-4 h-4 mr-1 mb-1 text-blue-600 focus:ring-blue-500 focus:ring-1"
                                                                type="radio" id="E" name="editNilai" value="E">
                                                            <p class="inline-block h-12">E</p>
                                                        </label>
                                                    </div>
                                                </div>
                                                @error('editMakul')
                                                    <br>
                                                    @php
                                                        $translate = $message == 'validation.unique' ? 'makul sudah ada di transkrip' : $message;
                                                    @endphp
                                                    <p class="text-red-500 text-base">{{ $translate }}</p>
                                                @enderror
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
                                <dialog id="konfirmasiHapus_{{ $transkrip->uuid }}"
                                    class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                                    <div class="relative">
                                        <button onclick="closeDeleteConfirm('{{ $transkrip->uuid }}')" type="button"
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
                                                Apakah kamu yakin untuk menghapus data ini?
                                            </h3>
    
                                            <form action="{{ route('hapus.transkrip', $transkrip->uuid) }}"
                                                method="POST" class="inline">
                                                @method('delete')
                                                @csrf
                                                <button type="submit"
                                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                    Ya, saya yakin
                                                </button>
                                            </form>
                                            <button onclick="closeDeleteConfirm('{{ $transkrip->uuid }}')" type="button"
                                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                                Tidak, batal
                                            </button>
                                        </div>
                                    </div>
                                </dialog>
                                {{-- End Confirmation Cancel Modal --}}
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transkrip->makul->kode_makul }}</td>
                                        <td>{{ $transkrip->makul->nama_makul }}</td>
                                        <td>{{ $transkrip->makul->sks }}</td>
                                        <td>{{ $transkrip->nilai }}</td>
                                        <td class="whitespace-nowrap">
                                            <button type="button" onclick="showEditModal('{{ $transkrip->uuid }}')"
                                                data-tooltip-target="ubah_{{ $transkrip->uuid }}"
                                                data-tooltip-placement="bottom" type="button"
                                                class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-2 py-1 text-center">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
    
                                            <div id="ubah_{{ $transkrip->uuid }}" role="tooltip"
                                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                Ubah
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
    
                                            <button data-tooltip-target="hapus_{{ $transkrip->uuid }}"
                                                data-tooltip-placement="bottom" type="button"
                                                onclick="showDeleteConfirm('{{ $transkrip->uuid }}')"
                                                class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-2 py-1 text-center">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
    
                                            <div id="hapus_{{ $transkrip->uuid }}" role="tooltip"
                                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                Hapus
                                                <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <p>*tekan tombol kirim apabila semua mata kuliah telah ditambahkan</p>
                </div>
            </div>
            @elseif ( $seminarSidang &&
                ($rekamBimbingans1 < 8 || $rekamBimbingans2 < 8) &&
                    ($seminarSidang->tahapan_ta === 'Seminar Hasil' && $seminarSidang->status_kelulusan === 'LULUS'))
                <div id="alert-additional-content-1"
                    class="p-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="sr-only">Info</span>
                        <h3 class="text-lg font-medium">Pendaftaran Belum Bisa Dilakukan</h3>
                    </div>

                    <div class="mt-2 mb-4 text-sm">
                        Untuk dapat melakukan pendaftaran Sidang Akhir, kamu perlu melakukan rekam bimbingan tugas akhir
                        kepada masing-masing dosen pembimbing kamu sebanyak minimal delapan kali
                    </div>

                    <div class="flex">
                        <a href="/mahasiswa/bimbingan" type="button"
                            class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 14">
                                <path
                                    d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                            </svg>
                            Kontrol Bimbingan
                        </a>
                    </div>
                </div>
                {{-- Tampilan Belum Bisa Melakukan Pendaftarah --}}
            @else
                <div id="alert-additional-content-1"
                    class="p-4 text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800"
                    role="alert">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="sr-only">Info</span>
                        <h3 class="text-lg font-medium">Pendaftaran Belum Bisa Dilakukan</h3>
                    </div>

                    <div class="mt-2 mb-4 text-sm">
                        Untuk dapat melakukan pendaftaran Sidang Akhir, kamu perlu menyelesaikan Seminar Hasil yang
                        terakhir
                        kamu daftarkan terlebih
                        dahulu.
                    </div>

                    <div class="flex">
                        <a href="/mahasiswa/semhas" type="button"
                            class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 14">
                                <path
                                    d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                            </svg>
                            Seminar Hasil
                        </a>
                    </div>
                </div>
        @endif
    </x-main-card>
    @section('script')
        <script>
            function showConfirm() {
                var modal = document.getElementById('confirmDialog');
                modal.showModal();
            }

            function closeConfirmDialog() {
                var modal = document.getElementById('confirmDialog');
                modal.close();
            }
            
            function showEditModal(uuid) {
                var modal = document.getElementById('edit_' + uuid);
                modal.showModal();
            }

            function closeEditModal(uuid) {
                var modal = document.getElementById('edit_' + uuid);
                modal.close();
            }

            function showDeleteConfirm(uuid) {
                var modal = document.getElementById('konfirmasiHapus_' + uuid);
                modal.showModal();
            }

            function closeDeleteConfirm(uuid) {
                var modal = document.getElementById('konfirmasiHapus_' + uuid);
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
        {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#makul").select2({
                    placeholder:'Pilih Mata Kuliah',
                    ajax: {
                        url: "{{ route('cari.makul') }}",
                        processResults: function({data}){
                            return {
                                results: $.map(data, function(item){
                                    return {
                                        id: item.id,
                                        text: item.nama_makul
                                    }
                                })
                            }
                        }
                    }
                });
            })
        </script>
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
                        targets: [0, 1, 2, 3, 4, 5],
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
                        width: "40%",
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
                    {
                        width: "10%",
                        targets: 5,
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
