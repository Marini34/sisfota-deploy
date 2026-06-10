<x-app-layout>
    @section('title', 'Detail Seminar Proposal')
    @section('head')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* biar select2 nyatu sama tailwind input */
        .select2-container .select2-selection--single {
            height: 42px;
            border: 1px solid #cbd5e1;
            border-radius: .5rem;
            display: flex;
            align-items: center;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-left: 12px;
        }
        .select2-container .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
            .trix-editor {
                width: 100%;
                height: 11rem;
            }

            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
            }
            /* Memaksa dropdown Select2 muncul di atas elemen modal */
    .select2-container--default .select2-dropdown {
        z-index: 99999 !important;
    }
        </style>
    @endsection
    <x-main-card>
        @foreach ($sempros as $sempro)
            <div class="overflow-x-auto miniscrollbar">
                <table class="table table-striped table-hover table-auto" style="width: 100%">
                    <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                        Detail Pendaftaran Seminar Proposal
                        <hr class="mt-2 border border-l border-slate-900">
                    </caption>
                    {{-- Edit modal --}}
                    <div class="modal-content">
                        <dialog id="modalEdit_{{ $sempro->tugas_akhir->slug }}"
                            class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 md:w-2/5">
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                    Edit data mahasiswa
                                </h3>
                                <button onclick="closeDialogTerima('{{ $sempro->tugas_akhir->slug }}')" type="button"
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
                            <form action="{{ route('edit.sempro', $sempro->tugas_akhir->slug) }}" method="post"
                                enctype="multipart/form-data" class="p-2">
                                @csrf
                                <div class="mx-5 mb-3">
                                    <label for="judul">Judul</label>
                                    <div class="my-2">
                                        <input type="text" name="judul" id="judul"
                                            class="w-full rounded-md border-slate-300" value="{{ $sempro->tugas_akhir->judul }}" required>
                                    </div>
                                    <label for="no_hp">No HP</label>
                                    <div class="my-2">
                                        <input type="text" name="no_hp" id="no_hp"
                                            class="w-full rounded-md border-slate-300" value="{{ $sempro->tugas_akhir->no_hp }}" required>
                                    </div>
                                    <label for="no_hp">Notulen</label>
                                    <div class="my-2">
                                        <select name="notulen_mahasiswa_id" id="notulen_mahasiswa_id" class="select2 w-full rounded-lg border border-slate-300 px-3 py-2 dark:bg-gray-900 dark:border-gray-700">
                                          <option value="">-- Pilih Penguji 1 --</option>
                                            @foreach($notulenID as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('notulen_mahasiswa_id', $sempro?->notulen_mahasiswa_id) == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama_lengkap }} -
                                                    {{ $item->nim }} -
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit"
                                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                                        Perbaharui Data
                                    </button>
                                </div>
                            </form>
                        </dialog>
                    </div>
                    {{-- End Edit modal --}}
                    {{-- Terima Modal --}}
                    <div class="modal-content">
                        <dialog id="modalTerima_{{ $sempro->slug }}"
                            class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 md:w-2/5">
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                                    Terima Pendaftaran Seminar Proposal
                                </h3>
                                <button onclick="closeDialogTerima('{{ $sempro->slug }}')" type="button"
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
                            <form action="{{ route('terima.sempro', $sempro->slug) }}" method="post"
                                enctype="multipart/form-data" class="p-2">
                                @csrf
                                <div class="mx-5 mb-3">
                                    <label for="tanggal">Tanggal Pelaksanaan </label>
                                    <div class="my-2">
                                        <input type="date" name="tanggal" id="tanggal"
                                            class="w-full rounded-md border-slate-300" value="Diterima" required >
                                    </div>
                                    <label for="jam">Jam Pelaksanaan</label>
                                    <div class="my-2">
                                        <input type="time" name="jam" id="jam"
                                            class="w-full rounded-md border-slate-300" value="Diterima" required >
                                    </div>
                                    <label for="tempat">Tempat Pelaksanaan</label>
                                    <div class="my-2">
                                        <input type="text" name="tempat" id="tempat"
                                            class="w-full rounded-md border-slate-300" placeholder="Ruang Sidang A" required >
                                    </div>
                                    <label for="status_pendaftaran">Status</label>
                                    <div class="my-2">
                                        <input type="text" name="status_pendaftaran" id="status_pendaftaran"
                                            class="w-full rounded-md border-slate-300" value="Diterima" required readonly>
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
                        <dialog id="modalTolak_{{ $sempro->slug }}"
                            class="miniscrollbar bg-white rounded-lg shadow-2xl w-2/3 h-2/3">
                            <div
                                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                                    Tolak Pendaftaran
                                </h3>
                                <button onclick="closeDialogTolak('{{ $sempro->slug }}')" type="button"
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
                            <form action="{{ route('tolak.sempro', $sempro->slug) }}" method="post" class="p-2">
                                @csrf
                                <div class="mx-5 mb-3">
                                    <input id="tolak" type="hidden" name="tolak">
                                    <trix-editor input="tolak" class="trix-editor"
                                        placeholder="Masukkan alasan pendaftaran ditolak..."></trix-editor>
                                    <button type="submit"
                                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2 mt-5">
                                        KIRIM
                                    </button>
                                </div>
                            </form>
                        </dialog>
                    </div>
                    {{-- End Tolak Modal --}}
                    @if ($sempro->file_lampiran)
                        @php
                            $lampiran = json_decode($sempro->file_lampiran);
                            // $lampiranArray = (object) $lampiranArray;

                            $nilai1 = $lampiran[1] ?? null;
                            $nilai2 = $lampiran[2] ?? null;
                            $nilai3 = $lampiran[3] ?? null;
                            for ($i = 4; $i < count($lampiran); $i++) {
                                $nilai[$i] = isset($lampiran[$i]) ? $lampiran[$i] . ', ' : null;
                            }
                        @endphp
                    @endif
                    <tr>
                        <th style="width: 28%">Nama</th>
                        <td class="font-bold" style="width: 1%">:</td>
                        <td>{{ $sempro->mahasiswa->nama_lengkap }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>NIM</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->mahasiswa->nim }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->no_hp ?? '-' }}</td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->judul }}</td>
                        <td>
                        </td>
                    </tr>
                    <tr>
                        <th>Studi Kasus</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->studi_kasus }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Metode</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->metode }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing Akademik</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->dosen_PA->nama_dosen }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing 1</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Dosen Pembimbing 2</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Dosen Penguji 1</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->tugas_akhir->dosen_penguji_1_id === null)
                                -
                            @else
                                {{ $sempro->tugas_akhir->dosen_penguji_1->nama_dosen }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Dosen Penguji 2</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->tugas_akhir->dosen_penguji_2_id === null)
                                -
                            @else
                                {{ $sempro->tugas_akhir->dosen_penguji_2->nama_dosen }}
                            @endif
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <th>Notulen</th>
                        <td class="font-bold">:</td>
                        <td>{{ $sempro?->notulenMahasiswa?->nim }} - {{ $sempro?->notulenMahasiswa?->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->tempat_pelaksanaan === null)
                                -
                            @else
                                {{ $sempro->tempat_pelaksanaan }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->tanggal_pelaksanaan === null)
                                -
                            @else
                                {{ $sempro->tanggal_pelaksanaan }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Jam Pelaksanaan</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($sempro->jam_pelaksanaan === null)
                                -
                            @else
                                {{ $sempro->jam_pelaksanaan }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Dokumen Proposal</th>
                        <td class="font-bold">:</td>
                        <td>
                            <x-new-modal :file="$sempro->dokumen_ta" />
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Bukti menghadiri seminar proposal</th>
                        <td class="font-bold">:</td>
                        <td><x-new-modal :file="$nilai1" /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Bukti telah menjadi notulensi</th>
                        <td class="font-bold">:</td>
                        <td><x-new-modal :file="$nilai2" /></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>LIRS semester berjalan</th>
                        <td class="font-bold">:</td>
                        <td><x-new-modal :file="$nilai3" />
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>File Lainnya</th>
                        <td class="font-bold">:</td>
                        <td>
                            @if ($lampiran !== null)
                                @for ($i = 4; $i < count($lampiran); $i++)
                                    <x-new-modal :file="$lampiran[$i]" />
                                @endfor
                            @else
                                -
                            @endif
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>

            @if(
                $aksesAdmin &&
                $sempro->status_pendaftaran !== 'Dibatalkan' &&
                $sempro->status_pendaftaran !== 'Menunggu Verifikasi' &&
                $sempro->status_pendaftaran !== 'Ditolak' &&
                ($sempro->status_kelulusan == 'Menunggu Keputusan' || $sempro->status_kelulusan == 'LULUS')
            )
                <button onclick="openModal()"
                    class="float-end mt-3 me-2 text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Batalkan Seminar
                </button>
            @endif

            <div id="modalBatalkan"
                class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                    <h2 class="text-lg font-semibold mb-3 text-gray-700">
                        Batalkan Seminar
                    </h2>

                    <form method="POST" action="{{ route('sempro.batalkan', $sempro->id) }}">
                        @csrf

                        <textarea name="alasan_dibatalkan"
                            class="w-full border rounded-lg p-3 text-sm"
                            rows="4"
                            placeholder="Masukkan alasan pembatalan..."
                            required></textarea>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 text-sm bg-gray-300 rounded-lg">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600">
                                Ya, Batalkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @if ($sempro->status_pendaftaran === 'Menunggu Verifikasi')
                <a href="/admin/sempro" type="button"
                    class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                    Kembali
                </a>
                <div class="float-end">
                    <button type="button" onclick="showModalEdit('{{ $sempro->tugas_akhir->slug }}')"
                        class="cursor-pointer mt-3 focus:outline-none text-white bg-orange-400 hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">
                        Edit Data
                    </button>
                    <button type="button" onclick="showModalTerima('{{ $sempro->slug }}')"
                        class="cursor-pointer mt-3 focus:outline-none text-white bg-green-400 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Terima
                    </button>
                    <button type="button" onclick="showModalTolak('{{ $sempro->slug }}')"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                        Tolak
                    </button>
                </div>
            @elseif ($sempro->status_pendaftaran === 'Diterima' || $sempro->status_pendaftaran === 'Ditolak' || $sempro->status_pendaftaran === 'Dibatalkan')
                <a href="/admin/sempro/riwayat" type="button"
                    class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                    Kembali
                </a>
                    <button type="button" onclick="showModalEdit('{{ $sempro->tugas_akhir->slug }}')"
                        class="cursor-pointer mt-3 focus:outline-none text-white bg-orange-400 hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">
                        Edit Data
                    </button>
            @endif
        @endforeach
    </x-main-card>
    @section('script')

        <script>
            function openModal() {
                document.getElementById('modalBatalkan').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('modalBatalkan').classList.add('hidden');
            }
        </script>
        <script>
            function showModalEdit(slug) {
                var modal = document.getElementById('modalEdit_' + slug);
                modal.showModal();
            }
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

            function show(file) {
                var modal = document.getElementById('lampiran_' + file);
                modal.showModal();
            }

            function closeLampiran(file) {
                var modal = document.getElementById('lampiran_' + file);
                modal.close();
            }
        </script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Gunakan .each() agar bekerja dengan baik jika ada banyak modal di satu halaman
        $('.select2').each(function () {
            $(this).select2({
                width: '100%',
                placeholder: '-- Pilih Notulen --',
                allowClear: true,
                // WAJIB ADA: Menyuruh dropdown muncul di dalam div pembungkusnya, bukan di body
                dropdownParent: $(this).parent()
            });
        });
    });
</script>
    @endsection
</x-app-layout>
