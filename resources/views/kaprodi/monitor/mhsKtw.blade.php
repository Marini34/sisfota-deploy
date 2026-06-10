<x-app-layout>
    @section('title', 'Dashboard')

    <div class="text-gray-900 dark:text-gray-100">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-4">
            <h5 class="mb-2 text-2xl font-medium">
                Data Mahasiswa Lulus Tepat Waktu
            </h5>
        </div>

        {{-- FILTER --}}
        <div class="flex flex-wrap items-center gap-2 mb-3">
            <form method="GET" action="{{ route('monitor.mhsKtw') }}" class="flex items-center gap-2">
                <label class="text-sm">Filter Angkatan:</label>

                <select name="angkatan"
                    class="border border-slate-300 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800">
                    <option value="">Semua</option>
                    @foreach ($listAngkatan as $a)
                        <option value="{{ $a }}"
                            {{ (string) $selectedAngkatan === (string) $a ? 'selected' : '' }}>
                            {{ $a }}
                        </option>
                    @endforeach
                </select>

                @if ($selectedAngkatan)
                    <a href="{{ route('monitor.mhsKtw') }}" style="color:red">Reset</a>
                @endif
            </form>

            <button onclick="showTab('tabel')" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                Tabel
            </button>

            <button onclick="showTab('grafik')" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm">
                Grafik
            </button>
        </div>

        {{-- ================= TABEL ================= --}}
        <div id="tab-tabel">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                <div class="overflow-x-auto">
                    <table id="myTable" class="min-w-full text-sm">
                        <thead class="text-xs uppercase bg-slate-200">
                            <tr>
                                <th class="px-3 py-2">No</th>
                                <th class="px-3 py-2">NIM</th>
                                <th class="px-3 py-2">Nama</th>
                                <th class="px-3 py-2">Tahun Masuk</th>
                                <th class="px-3 py-2">Tahun Lulus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($datamahasiswaTepatWaktu as $i => $mhs)
                                <tr>
                                    <td class="px-3 py-2 text-center">{{ $i + 1 }}</td>
                                    <td class="px-3 py-2">{{ $mhs->nim ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $mhs->nama_lengkap ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center">{{ $mhs->tahun_masuk ?? '-' }}</td>
                                    <td class="px-3 py-2 text-center">{{ $mhs->tahun_lulus ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ================= GRAFIK ================= --}}
        <div id="tab-grafik" style="display:none">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                <h2 class="text-lg font-semibold mb-4">
                    Grafik Mahasiswa Lulus Tepat Waktu (5 Tahun Terakhir)
                </h2>

                <canvas id="chartKTW" height="100"></canvas>

                <hr class="my-8">

                <h2 class="text-lg font-semibold mb-4">
                    Persentase Kelulusan & Belum Lulus (6 Tahun Terakhir)
                </h2>

                <canvas id="chartPersen" height="100"></canvas>

            </div>
        </div>

    </div>

    @section('script')

        {{-- Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        {{-- TAB --}}
        <script>
            function showTab(tab) {
                document.getElementById('tab-tabel').style.display = 'none';
                document.getElementById('tab-grafik').style.display = 'none';
                document.getElementById('tab-' + tab).style.display = 'block';
            }
        </script>

        {{-- ================= CHART 1 ================= --}}
        <script>
            const ctx = document.getElementById('chartKTW');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($tahunGrafik),
                    datasets: [{
                        label: 'Jumlah Mahasiswa Lulus Tepat Waktu',
                        data: @json(array_map('intval', $totalGrafik)),
                        backgroundColor: 'rgba(59,130,246,0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        {{-- ================= CHART 2 ================= --}}
        <script>
            const ctxPersen = document.getElementById('chartPersen');
            const dataPersen = @json($dataPersen);

            new Chart(ctxPersen, {
                type: 'bar',
                data: {
                    labels: @json($labelsPersen),
                    datasets: [
                        {
                            label: 'Persentase Lulus Tepat Waktu (%)',
                            data: dataPersen.map(d => d.persen),
                            backgroundColor: 'rgba(34,197,94,0.7)',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Belum Lulus',
                            data: @json(array_map('intval', $dataBelumLulus)),
                            backgroundColor: 'rgba(239,68,68,0.7)',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;

                                    if (context.dataset.label.includes('Persentase')) {
                                        const persen = dataPersen[index].persen;
                                        const total = dataPersen[index].total;
                                        return `${persen}% dari ${total} mahasiswa`;
                                    } else {
                                        return `Belum lulus: ${context.raw} mahasiswa`;
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Persen (%)'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            title: {
                                display: true,
                                text: 'Jumlah Mahasiswa'
                            }
                        }
                    }
                }
            });
        </script>

        {{-- AUTO SUBMIT --}}
        <script>
            document.querySelector('select[name="angkatan"]')?.addEventListener('change', function() {
                this.form.submit();
            });
        </script>

        {{-- jQuery --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        {{-- DataTables --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

        <script>
            new DataTable('#myTable', {
                responsive: true,
                pageLength: 10,
                language: {
                    searchPlaceholder: "Cari...",
                    emptyTable: "Belum ada data"
                }
            });
        </script>

    @endsection
</x-app-layout>