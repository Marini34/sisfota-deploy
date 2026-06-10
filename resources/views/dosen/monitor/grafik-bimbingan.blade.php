<x-app-layout>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

        <h2 class="text-xl font-bold mb-6">
            Grafik Bimbingan - {{ $mahasiswa->nama_lengkap }}
        </h2>

        {{-- Grafik --}}
        <div class="mb-6">
            <canvas id="chartBimbingan" height="100"></canvas>
        </div>

        {{-- Tombol kembali --}}
        <div class="mt-4">
            <button onclick="history.back()"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            Kembali
            </button>
        </div>

    </div>

    @section('script')

        {{-- Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            // 🔥 data dari controller (HARUS sudah array)
            const labels = @json($labels);
            const values = @json($values);

            const ctx = document.getElementById('chartBimbingan');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Bimbingan',
                        data: values,

                        borderWidth: 2,
                        tension: 0.3,
                        fill: true,

                        backgroundColor: 'rgba(59,130,246,0.2)',
                        borderColor: 'rgba(59,130,246,1)',

                        pointRadius: 4,
                        pointBackgroundColor: 'rgba(59,130,246,1)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
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

    @endsection

</x-app-layout>