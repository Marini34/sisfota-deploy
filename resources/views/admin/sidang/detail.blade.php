<x-app-layout>
    @section('title', 'Detail Sidang Akhir')
    @section('head')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <style>
            .trix-editor {
                width: 100%;
                height: 12rem;
            }

            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
            }
        </style>
    @endsection
    <x-main-card>
        <table class="table table-striped table-hover" style="width: 100%">
            <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                Detail Pendaftaran Sidang Akhir
                <hr class="mt-2 border border-l border-slate-900">
            </caption>
            @if (session()->has('success'))
                <div class="alert alert-success bg-green-500">
                    {{ session('success') }}
                </div>
            @endif
            {{-- Terima Modal --}}
            <div class="modal-content">
                <dialog id="modalTerima_{{ $sidang->slug }}" class="miniscrollbar bg-white rounded-lg shadow-2xl w-2/5">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                            Terima Pendaftaran Sidang Akhir
                        </h3>
                        <button onclick="closeDialogTerima('{{ $sidang->slug }}')" type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="timeline-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form action="{{ route('terima.sidang', $sidang->slug) }}" method="post"
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
                                <input type="time" name="jam" id="jam"
                                    class="w-full rounded-md border-slate-300" required>
                            </div>

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
                <dialog id="modalTolak_{{ $sidang->slug }}"
                    class="miniscrollbar bg-white rounded-lg shadow-2xl w-2/3 h-2/3">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                            Tolak Pendaftaran
                        </h3>
                        <button onclick="closeDialogTolak('{{ $sidang->slug }}')" type="button"
                            class="float-end text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            data-modal-toggle="timeline-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <form action="{{ route('tolak.sidang', $sidang->slug) }}" method="post" class="p-2">
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
            <tr>
                <th style="width: 28%">Nama</th>
                <td class="font-bold" style="width: 1%">:</td>
                <td>{{ $sidang->mahasiswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <th>NIM</th>
                <td class="font-bold">:</td>
                <td>{{ $sidang->mahasiswa->nim }}</td>
            </tr>
            <tr>
                <th>Judul</th>
                <td class="font-bold">:</td>
                <td>{{ $sidang->tugas_akhir->judul }}</td>
            </tr>
            <tr>
                <th>Studi Kasus</th>
                <td class="font-bold">:</td>
                <td>{{ $sidang->tugas_akhir->studi_kasus }}</td>
            </tr>
            <tr>
                <th>Metode</th>
                <td class="font-bold">:</td>
                <td>{{ $sidang->tugas_akhir->metode }}</td>
            </tr>
            <tr>
                <th>Dosen Pembimbing Akademik</th>
                <td class="font-bold">:</td>
                <td>{{ $sidang->tugas_akhir->dosen_PA->nama_dosen }}</td>
            </tr>
            <tr>
                <th>Dosen Pembimbing 1</th>
                <td class="font-bold">:</td>
                <td>{{ $sidang->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</td>
            </tr>
            <tr>
                <th>Dosen Pembimbing 2</th>
                <td class="font-bold">:</td>
                <td>{{ $sidang->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</td>
            </tr>
            <tr>
                <th>Dosen Penguji 1</th>
                <td class="font-bold">:</td>
                <td>
                    @if ($sidang->tugas_akhir->dosen_penguji_1_id === null)
                        -
                    @else
                        {{ $sidang->tugas_akhir->dosen_penguji_1->nama_dosen }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Dosen Penguji 2</th>
                <td class="font-bold">:</td>
                <td>
                    @if ($sidang->tugas_akhir->dosen_penguji_2_id === null)
                        -
                    @else
                        {{ $sidang->tugas_akhir->dosen_penguji_2->nama_dosen }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Tempat Pelaksanaan</th>
                <td class="font-bold">:</td>
                <td>
                    @if ($sidang->tempat_pelaksanaan === null)
                        -
                    @else
                        {{ $sidang->tempat_pelaksanaan }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Tanggal Pelaksanaan</th>
                <td class="font-bold">:</td>
                <td>
                    @if ($sidang->tanggal_pelaksanaan === null)
                        -
                    @else
                        {{ $sidang->tanggal_pelaksanaan }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Jam Pelaksanaan</th>
                <td class="font-bold">:</td>
                <td>
                    @if ($sidang->jam_pelaksanaan === null)
                        -
                    @else
                        {{ $sidang->jam_pelaksanaan }}
                    @endif
                </td>
            </tr>
            @if ($sidang->file_lampiran)
                @php
                    $lampiranSidang = json_decode($sidang->file_lampiran ?? '[]', true);

                    $artikel = $lampiranSidang[0] ?? null;
                    $syaratSidang = $lampiranSidang[1] ?? null;
                    $accJadwalSidang = $lampiranSidang[2] ?? null;
                @endphp
            @endif
            <tr>
                <th style="width: 28%" class="font-medium">Dokumen Sidang Akhir</th>
                <td style="width: 1%" class="font-medium">:</td>
                <td>@php $dokumenSidang = $sidang ? $sidang->dokumen_ta : null; @endphp
                    <x-new-modal :file="$dokumenSidang" />
                </td>
            </tr>
            <tr>
                <th style="width: 28%" class="font-medium">Dokumen Artikel Jurnal</th>
                <td class="font-medium">:</td>
                <td><x-new-modal :file="$artikel" /></td>
            </tr>
            <tr>
                <th style="width: 28%" class="font-medium">Lembar Pemeriksaan Dokumen Persyaratan Sidang</th>
                <td class="font-medium">:</td>
                <td><x-new-modal :file="$syaratSidang" /></td>
            </tr>
            <tr>
                <th style="width: 28%" class="font-medium">Surat Acc Jadwal Sidang</th>
                <td class="font-medium">:</td>
                <td><x-new-modal :file="$accJadwalSidang" /></td>
            </tr>
            <tr>
                <th style="width: 28%" class="font-medium">File Lainnya</th>
                <td style="width: 1%" class="font-medium">:</td>
                <td>
                    @if (isset($lampiranSidang[3]))
                        @for ($i = 3; $i < count($lampiranSidang); $i++)
                            <x-new-modal :file="'sidang/lampiran/' . $lampiranSidang[$i]" />
                        @endfor
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
        @if ($sidang->status_kelulusan == 'LULUS')
            <table class="table table-striped table-hover table-auto" style="width: 100%">
                <caption class="caption-top font-bold text-black">
                    File Tugas Akhir
                </caption>
                @php
                    $fileTA = $sidang ? json_decode($sidang->file_notulensi) : null;
                    $artikel = $fileTA[1] ?? null;
                    $skpl = $fileTA[2] ?? null;
                    $kode = $fileTA[3] ?? null;
                    $SKPembimbing = $fileTA[4] ?? null;
                    $SKPengujiSempro = $fileTA[5] ?? null;
                    $SKPengujiSemhas = $fileTA[6] ?? null;
                    $lembarPengesahan = $fileTA[7] ?? null;
                    $undanganSemhas = $fileTA[8] ?? null;
                    $undanganSidang = $fileTA[9] ?? null;
                @endphp
                <tr>
                    <th style="width: 28%" class="font-medium">Artikel Jurnal</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$artikel" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">SKPL, DPPL, PDHUPL</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$skpl" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Source Code Program</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <a href="{{ asset('storage/' . $kode) }}"><u
                                class="text-blue-500">{{ $kode }}</u></a>
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">SK Pembimbing</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$SKPembimbing" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">SK Penguji Seminar Proposal</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$SKPengujiSempro" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">SK Penguji Seminar Hasil</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$SKPengujiSemhas" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Lembar Pengesahan Skripsi</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$lembarPengesahan" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Undangan Seminar Hasil</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$undanganSemhas" />
                    </td>
                </tr>
                <tr>
                    <th style="width: 28%" class="font-medium">Undangan Sidang Tugas Akhir</th>
                    <td style="width: 1%" class="font-medium">:</td>
                    <td>
                        <x-new-modal :file="$undanganSidang" />
                    </td>
                </tr>
                @if ($sidang->status_kelulusan == 'LULUS')
                    <tr>
                        <th style="width: 28%" class="font-medium">No Surat Berita Acara</th>
                        <td style="width: 1%" class="font-medium">:</td>
                        <td>

                            <form class="flex space-x-4"
                                action="{{ route('admin.sidang.update-no-surat-ba', ['slug' => $sidang->slug]) }}"
                                method="POST">
                                @csrf
                                <input type="number"
                                    class="px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring focus:border-blue-300"
                                    name="no_surat"
                                    @if ($sidang->no_surat) value = "{{ $sidang->no_surat }}" @endif
                                    style="width: 75px;">/DST/UN22/PK.03.05/{{ \Carbon\Carbon::parse($sidang->tanggal_pelaksanaan)->format('Y') }}
                                <button type="submit"
                                    class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Save</button>
                            </form>

                        </td>
                    </tr>
                @endif
            </table>
        @endif
        @if ($sidang->status_pendaftaran === 'Menunggu Verifikasi')
            <a href="/admin/sidang" type="button"
                class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                Kembali
            </a>
            <div class="float-end">
                <button type="button" onclick="showModalTerima('{{ $sidang->slug }}')"
                    class="cursor-pointer mt-3 focus:outline-none text-white bg-green-400 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    Terima
                </button>
                <button type="button" onclick="showModalTolak('{{ $sidang->slug }}')"
                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                    Tolak
                </button>
            </div>
        @elseif ($sidang->status_pendaftaran === 'Diterima' || $sidang->status_pendaftaran === 'Ditolak')
            <a href="/admin/sidang/riwayat" type="button"
                class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                Kembali
            </a>
        @endif

    </x-main-card>
    @section('script')
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
