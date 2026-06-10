<x-app-layout>
    @section('title', 'Detail Seminar Hasil')
    @section('head')
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
        @foreach ($semhases as $semhas)
        <div class="overflow-x-auto miniscrollbar">
            <table class="table table-striped table-hover table-auto" style="width: 100%">
                <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                    Detail Pendaftaran Seminar Hasil {{$semhas->mahasiswa->nama_lengkap}}
                    <hr class="mt-2 border border-l border-slate-900">
                </caption>
                {{-- Terima Modal --}}
                <div class="modal-content">
                    <dialog id="modalTerima_{{ $semhas->slug }}"
                        class="miniscrollbar bg-white rounded-lg shadow-2xl w-2/5">
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                Terima Pendaftaran Seminar Hasil
                            </h3>
                            <button onclick="closeDialogTerima('{{ $semhas->slug }}')" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="timeline-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form action="{{ route('terima.semhas', $semhas->slug) }}" method="post"
                            enctype="multipart/form-data" class="p-2">
                            @csrf
                            <div class="mx-5 mb-3">

                                <label for="tanggal">Tanggal Pelaksanaan</label>
                                <div class="my-2">
                                <input type="date" name="tanggal" id="tanggal"
                                class="w-full rounded-md border-slate-300" required>
                                </div>

                                <label for="jam">Jam Pelaksanaan</label>
                                <div class="my-2">
                                <select name="jam" id="jam" class="w-full rounded-md border-slate-300" required>
                                    <option value="" disabled selected data-text="-- Pilih Jam --">-- Pilih Jam --</option>
                                    <option value="08:00" data-text="08:00 - 10:00">08:00 - 10:00</option>
                                    <option value="10:00" data-text="10:00 - 12:00">10:00 - 12:00</option>
                                    <option value="13:00" data-text="13:00 - 15:00">13:00 - 15:00</option>
                                </select>
                                </div>
                                {{-- <label for="jam">Jam Pelaksanaan</label>
                                <div class="my-2">
                                    <input type="time" name="jam" id="jam"
                                        class="w-full rounded-md border-slate-300" required>
                                </div> --}}

                                <label for="tempat">Tempat Pelaksanaan</label>
                                <div class="my-2">
                                    <input type="text" name="tempat" id="tempat"
                                        class="w-full rounded-md border-slate-300" required>
                                </div>

                                <button type="submit"
                                    class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                                    TERIMA
                                </button>
                            </div>
                        </form>
                    </dialog>
                </div>
                {{-- End Terima Modal --}}
                {{-- Tolak Modal --}}
                <div class="modal-content">
                    <dialog id="modalTolak_{{ $semhas->slug }}"
                        class="miniscrollbar bg-white rounded-lg shadow-2xl w-2/3 h-2/3">
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                                Tolak Pendaftaran
                            </h3>
                            <button onclick="closeDialogTolak('{{ $semhas->slug }}')" type="button"
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
                        <form action="{{ route('tolak.semhas', $semhas->slug) }}" method="post" class="p-2">
                            @csrf
                            <div class="mx-5 mb-3">
                                <input id="tolak" type="hidden" name="tolak">
                                <trix-editor input="tolak" class="trix-editor" placeholder="Masukkan alasan pendaftaran ditolak..."></trix-editor>
                                <button type="submit"
                                    class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2 mt-5">
                                    KIRIM
                                </button>
                            </div>
                        </form>
                    </dialog>
                </div>
                {{-- End Tolak Modal --}}
                {{-- Edit Modal --}}
                <div class="modal-content">
                    <dialog id="modalEdit_{{ $semhas->slug }}"
                        class="miniscrollbar bg-white rounded-lg shadow-2xl w-2/5">
                        <div
                            class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                Ubah Data Tugas Akhir
                            </h3>
                            <button onclick="closeDialogEdit('{{ $semhas->slug }}')" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-toggle="timeline-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <form action="{{ route('admin.semhas.update-ta', $semhas->slug) }}" method="post" class="p-2">

                            @csrf
                            <div class="mx-5 mb-3">
                                <div class="col-span-2">
                                    <label for="judul" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
                                    <input type="text" name="judul" id="judul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $semhas->tugas_akhir->judul }}" required="">
                                </div>
                                <div class="col-span-2">
                                    <label for="studi_kasus" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Studi Kasus</label>
                                    <input type="text" name="studi_kasus" id="studi_kasus" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $semhas->tugas_akhir->studi_kasus }}" required="">
                                </div>
                                <div class="col-span-2">
                                    <label for="metode" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Metode</label>
                                    <input type="text" name="metode" id="metode" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" value="{{ $semhas->tugas_akhir->metode }}" required="">
                                </div>
                                <div class="col-span-2">
                                    &nbsp;
                                </div>
                                <button type="submit"
                                    class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </dialog>
                </div>
                {{-- End Edit Modal --}}
                <tr>
                    <th style="width: 28%">Nama</th>
                    <td class="font-bold" style="width: 1%">:</td>
                    <td>{{ $semhas->mahasiswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td class="font-bold">:</td>
                    <td>{{ $semhas->mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <th>Judul</th>
                    <td class="font-bold">:</td>
                    <td>{{ $semhas->tugas_akhir->judul }}</td>
                </tr>
                <tr>
                    <th>Studi Kasus</th>
                    <td class="font-bold">:</td>
                    <td>{{ $semhas->tugas_akhir->studi_kasus }}</td>
                </tr>
                <tr>
                    <th>Metode</th>
                    <td class="font-bold">:</td>
                    <td>{{ $semhas->tugas_akhir->metode }}</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing Akademik</th>
                    <td class="font-bold">:</td>
                    <td class="">{{ $semhas->tugas_akhir->dosen_PA->nama_dosen }} </td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing 1</th>
                    <td class="font-bold">:</td>
                    <td>{{ $semhas->tugas_akhir->dosen_pembimbing_1->nama_dosen }} (Nilai : {{ $semhas->total_nilai_pembimbing_1 }})</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing 2</th>
                    <td class="font-bold">:</td>
                    <td>{{ $semhas->tugas_akhir->dosen_pembimbing_2->nama_dosen }} (Nilai : {{ $semhas->total_nilai_pembimbing_2 }})</td>
                </tr>
                <tr>
                    <th>Dosen Penguji 1</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($semhas->tugas_akhir->dosen_penguji_1_id === null)
                            -
                        @else
                            {{ $semhas->tugas_akhir->dosen_penguji_1->nama_dosen }} (Nilai : {{ $semhas->total_nilai_penguji_1 }})
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Dosen Penguji 2</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($semhas->tugas_akhir->dosen_penguji_2_id === null)
                            -
                        @else
                            {{ $semhas->tugas_akhir->dosen_penguji_2->nama_dosen }} (Nilai : {{ $semhas->total_nilai_penguji_2 }})
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Tempat Pelaksanaan</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($semhas->tempat_pelaksanaan === null)
                            -
                        @else
                            {{ $semhas->tempat_pelaksanaan }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Pelaksanaan</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($semhas->tanggal_pelaksanaan === null)
                            -
                        @else
                            {{ $semhas->tanggal_pelaksanaan }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Jam Pelaksanaan</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($semhas->jam_pelaksanaan === null)
                            -
                        @else
                            {{ $semhas->jam_pelaksanaan }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Dokumen Seminar Hasil</th>
                    <td class="font-bold">:</td>
                    <td>
                        <x-new-modal :file="$semhas->dokumen_ta" />
                    </td>
                </tr>
                @if ($semhas->file_lampiran)
                    @php
                        $lampiran = json_decode($semhas->file_lampiran);
                        $accJadwal = $lampiran[1] ?? null;
                    @endphp
                @endif
                <tr>
                    <th>Surat ACC Jadwal Seminar</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($accJadwal)
                            <x-new-modal :file="$accJadwal" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>File Lainnya</th>
                    <td class="font-bold">:</td>
                    <td>
                        @if ($lampiran !== null)
                            @for ($i = 2; $i < count($lampiran); $i++)
                                <x-new-modal :file="$lampiran[$i]" />
                            @endfor
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
            <hr>

        </div>
            @if ($semhas->status_pendaftaran === 'Menunggu Verifikasi')
                <a href="/admin/semhas" type="button"
                    class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                    Kembali
                </a>
                <button type="button" onclick="showModalEdit('{{ $semhas->slug }}')"
                    class="mt-3 text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:focus:ring-yellow-900">
                    Ubah Data
                </button>
                <div class="float-end">
                    <button type="button" onclick="showModalTerima('{{ $semhas->slug }}')"
                        class="cursor-pointer mt-3 focus:outline-none text-white bg-green-400 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Terima
                    </button>
                    <button type="button" onclick="showModalTolak('{{ $semhas->slug }}')"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                        Tolak
                    </button>
                </div>
            @elseif ($semhas->status_pendaftaran === 'Diterima' || $semhas->status_pendaftaran === 'Ditolak')
                <a href="/admin/semhas/riwayat" type="button"
                    class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                    Kembali
                </a>
                <button type="button" onclick="showModalEdit('{{ $semhas->slug }}')"
                    class="mt-3 text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:outline-none focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:focus:ring-yellow-900">
                    Ubah Data
                </button>
            @endif
        @endforeach
    </x-main-card>
    @section('script')
       <script>
            document.getElementById('tanggal').addEventListener('change', async function () {
                const jamSelect = document.getElementById('jam');
                const selectedDate = this.value;

                // 1. Reset semua option (Aktifkan kembali dan kembalikan teks asli)
                Array.from(jamSelect.options).forEach(opt => {
                    opt.disabled = false;
                    if (opt.value === "") {
                        opt.selected = true; // Kembalikan ke opsi default
                    } else {
                        // Ambil teks asli dari atribut data-text
                        opt.innerText = opt.getAttribute('data-text');
                    }
                });

                if (!selectedDate) return;

                // 2. Validasi hari Senin
                const date = new Date(selectedDate);
                const day = date.getDay(); // 0 = Minggu, 1 = Senin
                if (day !== 1) {
                    alert('Hanya boleh memilih hari Senin!');
                    this.value = '';
                    return;
                }

                try {
                    // 3. Fetch data dari server
                    const res = await fetch(`/admin/semhas/cek-kuota?tanggal=${selectedDate}`);
                    const data = await res.json();
                    // Contoh response data: {"08:00": 3, "10:00": 1}
                    // (Jam 13:00 tidak ada di response karena nilainya 0)

                    // 4. Update Label dan Status Select
                    Array.from(jamSelect.options).forEach(opt => {
                        const jamValue = opt.value;
                        if (!jamValue) return; // Lewati option kosong "-- Pilih Jam --"

                        const originalText = opt.getAttribute('data-text');

                        // Ambil jumlah terisi dari data, jika tidak ada berarti 0
                        const terisi = data[jamValue] || 0;

                        if (terisi >= 3) {
                            // Jika sudah 3 (Maksimal)
                            opt.innerText = `${originalText} (Penuh)`;
                            opt.disabled = true; // Kunci pilihan
                        } else if (terisi > 0) {
                            // Jika sudah ada yang daftar tapi belum penuh (1 atau 2)
                            // Saya tambahkan angka biar lebih informatif, misal: (Terisi: 1/3)
                            opt.innerText = `${originalText} (Terisi: ${terisi}/1)`;
                        } else {
                            // Jika 0
                            opt.innerText = `${originalText} (Kosong)`;
                        }
                    });

                } catch (error) {
                    console.error('Gagal mengambil data kuota:', error);
                }
            });
            </script>
        <script>
            function showModalTerima(slug) {
                var modal = document.getElementById('modalTerima_' + slug);
                modal.showModal();
            }

            function closeDialogTerima(slug) {
                var modal = document.getElementById('modalTerima_' + slug);
                modal.close();
            }

            function showModalTolak(slug) {
                var modal = document.getElementById('modalTolak_' + slug);
                modal.showModal();
            }

            function closeDialogTolak(slug) {
                var modal = document.getElementById('modalTolak_' + slug);
                modal.close();
            }

            function showModalEdit(slug) {
                var modal = document.getElementById('modalEdit_' + slug);
                modal.showModal();
            }

            function closeDialogEdit(slug) {
                var modal = document.getElementById('modalEdit_' + slug);
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
    @endsection
</x-app-layout>
