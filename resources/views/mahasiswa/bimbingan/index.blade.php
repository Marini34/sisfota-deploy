<x-app-layout>
    @section('title', 'Kontrol Bimbingan')
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
        @if (
            $seminarSidang &&
                (($seminarSidang->tahapan_ta === 'Seminar Proposal' && $seminarSidang->status_kelulusan === 'LULUS') ||
                    $seminarSidang->tahapan_ta === 'Seminar Hasil' ||
                    $seminarSidang->tahapan_ta === 'Sidang Akhir'))
            {{-- Modal --}}
            <dialog id="modalForm" class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 sm:w-3/5 lg:w-2/5">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white uppercase">
                        Rekam Bimbingan Tugas Akhir
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
                <form action="{{ route('store.bimbingan') }}" method="post" enctype="multipart/form-data"
                    class="p-4 md:p-5">
                    @csrf
                    <label for="pembimbing">Dosen Pembimbing</label>
                    <div class="my-2">
                        <select name="pembimbing" id="pembimbing" class="w-full rounded-md border-slate-300" required>
                            <option value="" disabled selected hidden>Pilih Pembimbing</option>
                            <option value="{{ $tugasAkhir->dosen_pembimbing_1_id }}">
                                {{ $tugasAkhir->dosen_pembimbing_1->nama_dosen }}
                            </option>
                            <option value="{{ $tugasAkhir->dosen_pembimbing_2_id }}">
                                {{ $tugasAkhir->dosen_pembimbing_2->nama_dosen }}
                            </option>
                        </select>
                    </div>

                    <label for="jadwal_bimbingan_id">Jadwal Bimbingan Tersedia</label>
                    <div class="my-2">
                        <select name="jadwal_bimbingan_id" id="jadwal_bimbingan_id"
                            class="w-full rounded-md border-slate-300" required disabled>
                            <option value="">Pilih dosen dulu...</option>
                        </select>
                    </div>

                    <label for="kemajuan">Uraian Kemajuan Tugas Akhir</label>
                    <div class="my-2">
                        <textarea name="kemajuan" id="kemajuan" class="w-full rounded-md border-slate-300" required></textarea>
                        @error('kemajuan')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <label for="persentase_mhs">Persentase Progress %</label>
                    <div class="my-2">
                        <input type="number" name="persentase_mhs" id="persentase_mhs" min="0" max="100"
                            class="w-full rounded-md border-slate-300" required>
                        @error('persentase_mhs')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <label for="dokumen_bimbingan_mhs">Dokumen Bimbingan</label>
                    <div class="my-2">
                        <input type="file" name="dokumen_bimbingan_mhs" id="dokumen_bimbingan_mhs"
                            accept=".pdf,.doc,.docx" class="w-full rounded-md border-slate-300">
                        @error('dokumen_bimbingan_mhs')
                            <div>
                                <p class="text-red-500 text-base">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    {{-- <label for="pengerjaanSelanjutnya">Rencana Pengerjaan Selanjutnya</label>
                    <div class="my-2">
                        <textarea name="pengerjaanSelanjutnya" id="pengerjaanSelanjutnya" class="w-full rounded-md border-slate-300" required></textarea>
                        @error('pengerjaanSelanjutnya')
                        <div>
                            <p class="text-red-500 text-base">{{ $message }}</p>
                        </div>
                        @enderror
                    </div> --}}

                    <br>
                    <button type="submit"
                        class="w-full text-center text-white text-lg font-bold tracking-wider uppercase bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded-lg px-5 py-2.5 me-2 mb-2">
                        SIMPAN
                    </button>
                </form>
            </dialog>
            {{-- End Modal --}}


            <div class="text-gray-900 dark:text-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                        Rekam Bimbingan Tugas Akhir
                    </h5>
                    <div class="float-end text-gray-900 dark:text-gray-100">
                        <button type="button" onclick="modalForm.showModal()"
                            class="text-white bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Reservasi Bimbingan
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped table-hover table-bordered">
                        <thead class="text-base uppercase text-slate-800">
                            <tr class="bg-slate-300 rounded-2xl text-sm">
                                <th class="hidden">.</th>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th class="min-w-48">Kemajuan TA</th>
                                <th class="min-w-48">Pengerjaan Berikutnya</th>
                                <th>Persentase Progress</th>
                                <th>Dokumen</th>
                                <th>Komentar Dosen</th>
                                <th>Pembimbing</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rekamBimbingans as $index => $rekamBimbingan)
                                {{-- Confirmation Cancel Modal --}}
                                <dialog id="confirmDialog_{{ $rekamBimbingan->slug }}"
                                    class="fixed p-2 rounded-lg shadow-2xl w-full max-w-md max-h-full">
                                    <div class="relative">
                                        <button onclick="closeConfirmDialog('{{ $rekamBimbingan->slug }}')"
                                            type="button"
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
                                                Apakah kamu yakin untuk menghapus rekam bimbingan ini?
                                            </h3>

                                            <form action="{{ route('delete.bimbingan', $rekamBimbingan->slug) }}"
                                                method="POST" enctype="multipart/form-data" class="inline">
                                                @method('delete')
                                                @csrf
                                                <button type="submit"
                                                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                    Ya, saya yakin
                                                </button>
                                            </form>
                                            <button onclick="closeConfirmDialog('{{ $rekamBimbingan->slug }}')"
                                                type="button"
                                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-500 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">
                                                Tidak, batal
                                            </button>
                                        </div>
                                    </div>
                                </dialog>
                                {{-- End Confirmation Cancel Modal --}}
                                <tr>
                                    <td class="hidden">{{ $rekamBimbingan->tanggal_bimbingan }}</td>
                                    <td class="text-center">{{ $index + 1 }}.</td>
                                    <td>
                                        <div class="text-center">
                                            {{ \Carbon\Carbon::parse($rekamBimbingan->tanggal_bimbingan)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-justify">
                                            {{ $rekamBimbingan->uraian_kemajuan_ta }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-justify">
                                            {{ $rekamBimbingan->pengerjaan_selanjutnya }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="">
                                            {{ $rekamBimbingan->persentase_mhs }}%
                                        </div>
                                    </td>
                                    <td>
                                        @if ($rekamBimbingan->dokumen_bimbingan_mhs === null)
                                            <span>
                                                Tidak ada dokumen
                                            </span>
                                        @else
                                            <div class="">
                                                <a href="{{ asset('storage/' . $rekamBimbingan->dokumen_bimbingan_mhs) }}"
                                                    target="_blank" rel="noopener noreferrer">
                                                    <button style="color: blue"
                                                        class="relative inline-flex items-center justify-center overflow-hidden text-blue-900 rounded-lg group border-2 border-slate-500">
                                                        <span
                                                            class="relative px-1 py-0 rounded-md group-hover:bg-opacity-0">
                                                            <i
                                                                class="bi bi-file-earmark-text font-bold text-slate-500"></i>
                                                            Lihat Dokumen
                                                        </span>
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="">
                                            {{ $rekamBimbingan->komentar_dosen }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="">
                                            {{ $rekamBimbingan->pembimbing->nama_dosen }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            @if ($rekamBimbingan->status_rekam_bimbingan == 'Diterima')
                                                <button
                                                    class="relative inline-flex items-center justify-center overflow-hidden text-gray-900 rounded-lg group border-2 border-emerald-500">
                                                    <span
                                                        class="relative px-1 py-0 rounded-md group-hover:bg-opacity-0">
                                                        <i class="bi bi-check-lg font-bold text-emerald-500"></i>
                                                    </span>
                                                </button>
                                            @elseif ($rekamBimbingan->status_rekam_bimbingan == 'Ditolak')
                                                <button
                                                    class="relative inline-flex items-center justify-center overflow-hidden text-gray-900 rounded-lg group border-2 border-red-500">
                                                    <span
                                                        class="relative px-1 py-0 rounded-md group-hover:bg-opacity-0">
                                                        <i class="bi bi-x-lg font-black text-red-500"></i>
                                                    </span>
                                                </button>
                                            @elseif ($rekamBimbingan->status_rekam_bimbingan == 'Menunggu Verifikasi')
                                                <button
                                                    class="relative inline-flex items-center justify-center overflow-hidden text-gray-900 rounded-lg group border-2 border-slate-500">
                                                    <span
                                                        class="relative px-2 py-0 rounded-md group-hover:bg-opacity-0">
                                                        <i class="text-slate-500">?</i>
                                                    </span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap text-center">
                                        <a href="/mahasiswa/bimbingan/edit/{{ $rekamBimbingan->slug }}"
                                            data-tooltip-target="ubah_{{ $rekamBimbingan->slug }}"
                                            data-tooltip-placement="bottom" type="button"
                                            class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <div id="ubah_{{ $rekamBimbingan->slug }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Ubah
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>

                                        <button data-tooltip-target="hapus_{{ $rekamBimbingan->slug }}"
                                            data-tooltip-placement="bottom" type="button"
                                            onclick="showConfirm('{{ $rekamBimbingan->slug }}')"
                                            class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>

                                        <div id="hapus_{{ $rekamBimbingan->slug }}" role="tooltip"
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
            </div>
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
                    <h3 class="text-lg font-medium">Belum bisa melakukan rekam bimbingan tugas akhir</h3>
                </div>

                <div class="mt-2 mb-4 text-sm">
                    Untuk melakukan rekam bimbingan tugas akhir, kamu perlu lulus seminar proposal terlebih dahulu
                </div>

                <div class="flex">
                    <a href="/mahasiswa/sempro" type="button"
                        class="text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:outline-none focus:ring-blue-200 font-medium rounded-lg text-xs px-3 py-1.5 me-2 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-2 h-3 w-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 14">
                            <path
                                d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z" />
                        </svg>
                        Seminar Proposal
                    </a>
                </div>
            </div>
        @endif

    </x-main-card>
    @section('script')
        <script>
            dropDownBimbingan()

            function closeDialog() {
                var modal = document.getElementById('modalForm');
                modal.close();
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
        <script>
            $(function() {
                const $pembimbing = $('#pembimbing');
                const $jadwal = $('#jadwal_bimbingan_id');

                function resetJadwal(text) {
                    $jadwal.prop('disabled', true).html(`<option value="">${text}</option>`);
                }

                $pembimbing.on('change', function() {
                    const dosenId = $(this).val();
                    resetJadwal('Memuat jadwal...');

                    $.get("{{ route('mahasiswa.jadwal-dosen') }}", {
                            dosen_id: dosenId
                        })
                        .done(function(res) {
                            if (!res || res.length === 0) {
                                resetJadwal('Tidak ada jadwal tersedia');
                                return;
                            }

                            let opt = `<option value="" disabled selected hidden>Pilih Jadwal</option>`;
                            res.forEach(function(j) {
                                opt += `<option value="${j.id}">${j.label}</option>`;
                            });

                            $jadwal.prop('disabled', false).html(opt);
                        })
                        .fail(function() {
                            resetJadwal('Gagal memuat jadwal');
                        });
                });
            });
        </script>
        <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

        <script type="text/javascript">
            let table = new DataTable('#myTable', {
                // responsive: true,
                autoWidth: false,
                paging: true,
                searching: true,
                order: [
                    [0, "desc"]
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ data",
                    paginate: {
                        next: '<i class="bi bi-chevron-right"></i>',
                        previous: '<i class="bi bi-chevron-left"></i>'
                    },
                    emptyTable: "Data rekam bimbingan kamu belum ada"
                },
                columnDefs: [{
                        targets: -1,
                        orderable: false,
                        width: "8%"
                    },
                    {
                        targets: [2, 3, 4],
                        className: "dt-head-center",
                        orderable: false
                    },
                    {
                        width: "0%",
                        orderable: true,
                        targets: 0
                    },
                    {
                        width: "2%",
                        orderable: true,
                        targets: 1
                    },
                    {
                        width: "13%",
                        targets: 2
                    },
                    {
                        width: "30%",
                        targets: 3
                    },
                    {
                        width: "30%",
                        targets: 4
                    },
                    {
                        width: "16%",
                        orderable: true,
                        targets: 5
                    },
                    {
                        width: "2%",
                        orderable: true,
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
