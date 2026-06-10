<x-app-layout>

    @section('title', 'Detail Notulen')

    <x-main-card>

        <h2 class="text-xl font-bold mb-4">Detail Seminar</h2>

        <div class="space-y-4">

            <div>
                <label class="font-semibold">Mahasiswa</label>
                <div>
                    {{ $row->mahasiswa->nim }} - {{ $row->mahasiswa->nama_lengkap }}
                </div>
            </div>

            <div>
                <label class="font-semibold">Judul Tugas Akhir</label>
                <div>
                    {{ $row->tugasAkhir->judul }}
                </div>
            </div>

            <div>
                <label class="font-semibold">Notulen Mahasiswa</label>
                <div>
                    {{ $row->notulenMahasiswa->nim }} - {{ $row->notulenMahasiswa->nama_lengkap }}
                </div>
            </div>

            <hr>

            <h3 class="font-bold text-lg">Catatan Notulen</h3>

            <div class="grid grid-cols-2 gap-6">

                @php
                    $catatan = json_decode($row->catatan_ta_notulen_mahasiswa_id, true);
                @endphp
                <div>
                    <label class="font-semibold">Catatan Penguji 1</label>
                    <div class="p-3 bg-gray-100 rounded">
                        {{ data_get($catatan, 'catatan_penguji_1', 'Belum ada catatan') }}
                    </div>
                </div>

                <div>
                    <label class="font-semibold">Catatan Penguji 2</label>
                    <div class="p-3 bg-gray-100 rounded">
                        {{ data_get($catatan, 'catatan_penguji_2', 'Belum ada catatan') }}
                    </div>
                </div>

            </div>

            <hr>

            <form action="{{ route('dosen.validasi-notulen.update', $row->slug) }}" method="POST">

                @csrf
                @method('PUT')

                <label class="font-semibold">Status Validasi</label>

                <select name="status_validasi_notulen_mhs" class="w-full border rounded p-2 mt-2">

                    <option value="Menunggu" {{ $row->status_validasi_notulen_mhs == 'Menunggu' ? 'selected' : '' }}>
                        Menunggu
                    </option>

                    <option value="Disetujui" {{ $row->status_validasi_notulen_mhs == 'Disetujui' ? 'selected' : '' }}>
                        Disetujui
                    </option>

                    <option value="Ditolak" {{ $row->status_validasi_notulen_mhs == 'Ditolak' ? 'selected' : '' }}>
                        Ditolak
                    </option>

                </select>

                <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
                    Update Status
                </button>
                <a href="/dosen/validasi-notulen">
                    <button type="button" class="mt-4 bg-red-600 text-white px-4 py-2 rounded">
                        Kembali
                    </button>
                </a>

            </form>

        </div>

    </x-main-card>

</x-app-layout>
