<x-app-layout>
    @section('title', 'Dashboard')

    <div class="text-gray-900 dark:text-gray-100">

        {{-- ========================= --}}
        {{-- 🔥 GRAFIK --}}
        {{-- ========================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-4">

            <h2 class="text-lg font-semibold mb-4">
                Grafik Tahapan TA per Angkatan
            </h2>

            <canvas id="chartTahapanTA" height="100"></canvas>

        </div>

        {{-- ========================= --}}
        {{-- 🔥 TABEL --}}
        {{-- ========================= --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">

            {{-- RATA-RATA --}}
            <div class="bg-white rounded-xl shadow p-4 mb-4">
                <h3 class="font-semibold mb-2">Rata-rata Lama Pengerjaan TA per Angkatan</h3>

                <table class="w-full text-sm">
                    <thead class="bg-slate-200 text-xs uppercase">
                        <tr>
                            <th class="px-3 py-2 text-center">Angkatan</th>
                            <th class="px-3 py-2 text-center">Rata-rata (Tahun)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rataPerAngkatan as $r)
                            <tr>
                                <td class="text-center">{{ $r->angkatan }}</td>
                                <td class="text-center">{{ $r->rata }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="text-sm text-slate-500">
                Rata-rata Global lama pengerjaan TA: <b>{{ $rataTotal }} tahun</b>
            </p>
            <br>

            {{-- FILTER --}}
            <form method="GET" class="flex items-center gap-2 mb-3">
                <label class="text-sm">Filter Angkatan:</label>

                <select name="angkatan" class="border rounded-lg px-3 py-2 text-sm" onchange="this.form.submit()">
                    <option value="">Semua</option>

                    @foreach ($listAngkatan as $a)
                        <option value="{{ $a }}" {{ $selectedAngkatan == $a ? 'selected' : '' }}>
                            {{ $a }}
                        </option>
                    @endforeach
                </select>

                @if ($selectedAngkatan)
                    <a href="{{ url()->current() }}" class="px-3 py-2 bg-slate-200 rounded-lg text-sm">
                        Reset
                    </a>
                @endif
            </form>

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table id="myTable" class="min-w-full text-sm">
                    <thead class="text-xs uppercase text-slate-800 bg-slate-200">
                        <tr>
                            <th class="px-3 py-2">No</th>
                            <th class="px-3 py-2">NIM</th>
                            <th class="px-3 py-2">Nama</th>
                            <th class="px-3 py-2">Judul Tugas Akhir</th>
                            <th class="px-3 py-2">Tgl Seminar Proposal</th>
                            <th class="px-3 py-2">Tgl Seminar Hasil</th>
                            <th class="px-3 py-2">Tgl Sidang Akhir</th>
                            <th class="px-3 py-2">Lama Pengerjaan TA</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $no = 1; @endphp
                        @forelse($rows as $r)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $r->mahasiswa?->nim }}</td>
                                <td>{{ $r->mahasiswa?->nama_lengkap }}</td>
                                <td>{{ $r->tugasAkhir?->judul }}</td>

                                <td>
                                    {{ $r->tgl_proposal ? \Carbon\Carbon::parse($r->tgl_proposal)->format('d-m-Y') : '-' }}
                                </td>
                                <td>
                                    {{ $r->tgl_hasil ? \Carbon\Carbon::parse($r->tgl_hasil)->format('d-m-Y') : '-' }}
                                </td>
                                <td>
                                    {{ $r->tgl_sidang ? \Carbon\Carbon::parse($r->tgl_sidang)->format('d-m-Y') : '-' }}
                                </td>

                                <td>{{ $r->durasi_ta }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- ========================= --}}
    {{-- 🔥 SCRIPT --}}
    {{-- ========================= --}}
    @section('script')

        {{-- Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const ctx = document.getElementById('chartTahapanTA');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($labelsGrafikTA),
                    datasets: [
                        {
                            label: 'Seminar Proposal',
                            data: @json($dataSempro),
                            borderWidth: 1
                        },
                        {
                            label: 'Seminar Hasil',
                            data: @json($dataSemhas),
                            borderWidth: 1
                        },
                        {
                            label: 'Sidang Akhir',
                            data: @json($dataSidang),
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        </script>

        {{-- DataTables --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

        <script>
            new DataTable('#myTable', {
                pageLength: 10,
                responsive: true
            });
        </script>

    @endsection

</x-app-layout>