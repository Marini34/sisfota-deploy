<x-app-layout>
    @section('title', 'Kelola Rekam Bimbingan')
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


        <div class="text-gray-900 dark:text-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h5 class="mb-2 text-2xl font-medium tracking-tight text-gray-900 dark:text-white">
                    Kelola Rekam Bimbingan Tugas Akhir
                </h5>
            </div>
            <div class="overflow-y-auto">
                <table id="myTable" class="table table-striped table-hover table-bordered">
                    <thead class="text-sm uppercase text-slate-800">
                        <tr class="bg-slate-300 rounded-2xl">
                            <th>Mahasiswa</th>
                            <th>Tanggal</th>
                            <th class="min-w-10">Kemajuan TA</th>
                            <th class="min-w-10">Pengerjaan Berikutnya</th>
                            <th class="min-w-10">Progress</th>
                            <th class="min-w-10">Dokumen </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($kelolaBimbingans as $index => $kelolaBimbingan)
                            <tr>
                                <td>
                                    <div class="">
                                        {{ $kelolaBimbingan->mahasiswa->nama_lengkap }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        {{ \Carbon\Carbon::parse($kelolaBimbingan->tanggal_bimbingan)->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-justify text-base">
                                        {{ $kelolaBimbingan->uraian_kemajuan_ta }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-justify text-base min-w-20">
                                        {{ $kelolaBimbingan->pengerjaan_selanjutnya }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-justify text-base min-w-20">
                                        {{ $kelolaBimbingan->persentase_mhs }}%
                                    </div>
                                </td>
                                <td>
                                    @if ($kelolaBimbingan->dokumen_bimbingan_mhs === null)
                                        <span>
                                            Tidak ada dokumen
                                        </span>
                                    @else
                                        <div class="">
                                            <a href="{{ asset('storage/' . $kelolaBimbingan->dokumen_bimbingan_mhs) }}"
                                                target="_blank" rel="noopener noreferrer">
                                                <button style="color: blue"
                                                    class="relative inline-flex items-center justify-center overflow-hidden text-blue-900 rounded-lg group border-2 border-slate-500">
                                                    <span
                                                        class="relative px-1 py-0 rounded-md group-hover:bg-opacity-0">
                                                        <i class="bi bi-file-earmark-text font-bold text-slate-500"></i>
                                                        Lihat Dokumen
                                                    </span>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="flex flex-row">

                                    <button onclick="openModal('{{ $kelolaBimbingan->id }}','Diterima')"
                                        class="cursor-pointer text-white bg-gradient-to-r from-cyan-400 via-cyan-500 to-cyan-600 rounded-lg text-sm px-2 py-1 mx-1">
                                        <i class="bi bi-check-lg text-2xl"></i>
                                    </button>

                                    <button onclick="openModal('{{ $kelolaBimbingan->id }}','Ditolak')"
                                        class="cursor-pointer text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 rounded-lg text-sm px-2 py-1 mx-1">
                                        <i class="bi bi-x-lg text-2xl"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div id="modalBimbingan" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm">

            <div class="flex items-center justify-center min-h-screen p-4">

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">

                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                        Verifikasi Rekam Bimbingan
                    </h3>

                    <form id="formVerifikasi" method="POST">
                        @csrf

                        <input type="hidden" name="status_rekam_bimbingan" id="status_rekam_bimbingan">

                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            Komentar Dosen (Ootional)
                        </label>

                        <textarea name="komentar_dosen"
                            class="w-full border border-slate-300 rounded-lg p-2 mt-1 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                            rows="4" placeholder="Tulis komentar untuk mahasiswa..."></textarea>

                        <div class="flex justify-end gap-2 mt-5">

                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg text-sm">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                                Simpan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </x-main-card>
    @section('script')
        <script>
            function openModal(id, status) {
                document.getElementById('modalBimbingan').classList.remove('hidden');

                document.getElementById('status_rekam_bimbingan').value = status;

                document.getElementById('formVerifikasi').action =
                    '/dosen/kelola-bimbingan/verifikasi/' + id;
            }

            function closeModal() {
                document.getElementById('modalBimbingan').classList.add('hidden');
            }
        </script>
        <script>
            dropDownBimbingan()
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
                    emptyTable: "Belum ada data rekam bimbingan yang perlu di kelola"
                },
                columnDefs: [{
                        targets: [0, 1, 2, 3, 4],
                        className: "dt-head-center",
                        orderable: false
                    },
                    {
                        targets: 0,
                        width: "17%",
                    },
                    {
                        targets: 1,
                        width: "13%",
                    },
                    {
                        width: "30%",
                        targets: 2
                    },
                    {
                        width: "30%",
                        targets: 3
                    },
                    {
                        width: "10%",
                        targets: 4
                    }
                ],
                search: {
                    search: ""
                }
            });
        </script>
    @endsection
</x-app-layout>
