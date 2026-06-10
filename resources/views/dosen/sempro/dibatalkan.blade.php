<x-app-layout>
    <div class="bg-white p-6 rounded-xl shadow">

        <h1 class="text-xl font-semibold mb-4">
            Riwayat Pembatalan Seminar
        </h1>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">No</th>
                        <th class="p-2 border">Mahasiswa</th>
                        <th class="p-2 border">NIM</th>
                        <th class="p-2 border">Tahapan</th>
                        <th class="p-2 border">Judul</th>
                        <th class="p-2 border">Tanggal</th>
                        <th class="p-2 border">Tempat</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Alasan Dibatalkan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $i => $r)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border text-center">{{ $i + 1 }}</td>
                            <td class="p-2 border">
                                {{ $r->mahasiswa->nama_lengkap ?? '-' }}
                            </td>
                            <td class="p-2 border">
                                {{ $r->mahasiswa->nim ?? '-' }}
                            </td>
                            <td class="p-2 border">
                                {{ $r->tahapan_ta }}
                            </td>
                            <td class="p-2 border">
                                {{ $r->tugasAkhir->judul ?? '-' }}
                            </td>
                            <td class="p-2 border">
                                {{ $r->tanggal_pelaksanaan ?? '-' }}
                            </td>
                            <td class="p-2 border">
                                {{ $r->tempat_pelaksanaan ?? '-' }}
                            </td>
                            <td class="p-2 border text-red-600">
                                {{ $r->status_pendaftaran ?? '-' }}
                            </td>
                            <td class="p-2 border">
                                {{ $r->alasan_dibatalkan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-4 text-gray-500">
                                Tidak ada data pembatalan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
