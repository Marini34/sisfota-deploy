<x-app-layout>
    @section('title', 'Monitoring')
    @section('head')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
        @include('styles.datatables')
    @endsection
    <x-main-card>
        <div class="mb-3">
            <h1 class="text-lg font-bold">Data Jumlah Seminar Sidang</h1>
        </div>
        <form action="/kaprodi/monitoring">
            <div class="flex flex-col md:flex-row mb-5">
                <div class="">
                    <label for="startDate" class="mr-6 md:mr-0 font-semibold">Dari</label>
                    <input type="date" id="startDate" name="startDate" value="{{ session('startDate') }}"
                        class="rounded-lg bg-slate-50 border border-gray-300 text-sm">
                </div>
                <div class="my-5 md:my-0 md:mx-5">
                    <label for="endDate" class="font-semibold">Sampai</label>
                    <input type="date" id="endDate" name="endDate" value="{{ session('endDate') }}"
                        class="rounded-lg bg-slate-50 border border-gray-300 text-sm">
                </div>
                <div class="">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm h-10 w-20 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Show
                    </button>
                </div>
            </div>
        </form>
        <div class="container mx-auto">
            {!! $chart->container() !!}
            {!! $chart->script() !!}
        </div>
        <div class="mt-5">
            <table class="">
                <caption class="caption-top text-sm text-black font-medium">
                    Total
                </caption>
                <tr class="font-medium text-sm">
                    <th>Seminar Proposal</th>
                    <td>:</td>
                    <td>{{ $jumlahSempro }}</td>
                </tr>
                <tr class="font-medium text-sm">
                    <th>Seminar Hasil</th>
                    <td>:</td>
                    <td>{{ $jumlahSemhas }}</td>
                </tr>
                <tr class="font-medium text-sm">
                    <th>Sidang Akhir</th>
                    <td>:</td>
                    <td>{{ $jumlahSidang }}</td>
                </tr>
            </table>
        </div>
    </x-main-card>
    <x-main-card>
        <div class="mb-3">
            <h1 class="text-lg font-bold">Data Lama Pengerjaan Tugas Akhir Mahasiswa</h1>
        </div>
        <div class="overflow-y-auto">
            <table id="myTable" class="table table-striped table-hover table-bordered">
                <thead class="text-sm uppercase text-slate-800">
                    <tr class="bg-slate-300 rounded-2xl">
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Angkatan</th>
                        <th>Status TA</th>
                        <th>Durasi TA</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($latestSeminarSidangs as $seminarSidang)
                        <tr>
                            <td>
                                {{ $seminarSidang->mahasiswa->nama_lengkap }}
                            </td>
                            <td>
                                {{ $seminarSidang->mahasiswa->nim }}
                            </td>
                            <td>
                                {{ $seminarSidang->mahasiswa->tahun_masuk }}
                            </td>
                            <td>
                                {{ $seminarSidang->tahapan_ta }}
                            </td>
                            <td>
                                @php
                                    $duration = null;
                                    if ($seminarSidang->tanggal_pelaksanaan) {
                                        $proposal = $latestSeminarProposals
                                            ->where('mahasiswa_id', $seminarSidang->mahasiswa_id)
                                            ->first()->tanggal_pelaksanaan;
                                            
                                        $startDate = new DateTime($proposal);
                                        if ($seminarSidang->tahapan_ta == 'Sidang Akhir' && $seminarSidang->status_kelulusan == 'LULUS' && $seminarSidang->tanggal_pelaksanaan != null) {
                                            $endDate = new DateTime($seminarSidang->tanggal_pelaksanaan);
                                        } else {
                                            $endDate = new DateTime();
                                        }

                                        $interval = $endDate->diff($startDate);
                                        $years = $interval->y;
                                        $months = $interval->m;
                                        $days = $interval->d;

                                        if ($years > 0) {
                                            $duration = "$years tahun $months bulan $days hari";
                                        } elseif ($months > 0) {
                                            $duration = "$months bulan $days hari";
                                        } else {
                                            $duration = "$days hari";
                                        }
                                    }
                                    echo $duration !== null ? $duration : '-';
                                @endphp

                            </td>
                            <td class="text-center">
                                <a href="{{route('monitoring.detail.mahasiswa', $seminarSidang->mahasiswa->slug)}}"
                                    data-tooltip-target="detail_{{$seminarSidang->slug}}"
                                    data-tooltip-placement="bottom" type="button"
                                    class="cursor-pointer text-white bg-gradient-to-br from-green-400 to-blue-600 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-green-200 dark:focus:ring-green-800 font-medium rounded-lg text-sm w-8 h-7 inline-flex items-center justify-center">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                <div id="detail_{{$seminarSidang->slug}}" role="tooltip"
                                    class="absolute z-10 invisible px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Detail
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
            function clearDate(inputId) {
                document.getElementById(inputId).value = '';
            }

            var startDateInput = document.getElementById('startDate');
            var endDateInput = document.getElementById('endDate');

            startDateInput.addEventListener('change', function() {
                var startDate = new Date(startDateInput.value);

                if (endDateInput.value !== '' && new Date(endDateInput.value) < startDate) {
                    endDateInput.value = startDateInput.value;
                }

                endDateInput.setAttribute('min', startDateInput.value);
            });

            endDateInput.addEventListener('change', function() {
                var endDate = new Date(endDateInput.value);

                if (startDateInput.value !== '' && new Date(startDateInput.value) > endDate) {
                    startDateInput.value = endDateInput.value;
                }

                startDateInput.setAttribute('max', endDateInput.value);
            });

            const urlParams = new URLSearchParams(window.location.search);
            const startDateParam = urlParams.get('startDate');
            const endDateParam = urlParams.get('endDate');

            if (startDateParam) {
                startDateInput.value = startDateParam;
            }

            if (endDateParam) {
                endDateInput.value = endDateParam;
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
                    emptyTable: "Tidak ada data yang dapat ditampilkan"
                },
                columnDefs: [{
                        targets: [0, 1, 2, 3, 4, 5],
                        className: "dt-head-center",
                    },
                    {
                        targets: 0,
                        orderable: true,
                        width: "27%",
                    },
                    {
                        targets: 1,
                        orderable: true,
                        width: "13%",
                    },
                    {
                        width: "10%",
                        orderable: true,
                        className: "text-center",
                        targets: 2
                    },
                    {
                        width: "15%",
                        orderable: true,
                        targets: 3
                    },
                    {
                        width: "25%",
                        orderable: false,
                        targets: 4
                    },
                    {
                        width: "10%",
                        orderable: false,
                        targets: 5
                    }
                ],
                search: {
                    search: ""
                }
            });
        </script>
    @endsection
</x-app-layout>
