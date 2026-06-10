<x-app-layout>
    @section('title', 'Detail Seminar Proposal')
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <table class="table table-striped table-hover" style="width: 100%">
                <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                    Detail Seminar Proposal
                    <hr class="mt-2 border border-l border-slate-900">
                </caption>
                <tr>
                    <th style="width: 28%">Nama</th>
                    <td class="font-bold" style="width: 1%">:</td>
                    <td>{{ $sempro->mahasiswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sempro->mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <th>Judul</th>
                    <td class="font-bold">:</td>
                    <td class="whitespace-pre-wrap">{{ $sempro->tugas_akhir->judul }}</td>
                </tr>
                <tr>
                    <th>Studi Kasus</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sempro->tugas_akhir->studi_kasus }}</td>
                </tr>
                <tr>
                    <th>Metode</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sempro->tugas_akhir->metode }}</td>
                </tr>
                @if ($sempro->status_kelulusan == 'LULUS' || $sempro->status_kelulusan == 'TIDAK LULUS')
                <tr>
                    <th>Berita Acara Sempro</th>
                    <td class="font-bold">:</td>
                    <td>
                        <a href="{{ route('dosen.cetak', $sempro->slug) }}" target="_blank" class="text-blue-600 underline">
                            Berita Acara Seminar Proposal.pdf
                        </a>
                    </td>
                </tr>
                @endif
                <tr>
                    <th>Video Presentasi</th>
                    <td class="font-bold">:</td>
                    <td>
                        @php
                            $video = $sempro->link_video;
                        @endphp
                        <div class="flex justify-start flex-wrap">
                            {{-- video Modal --}}
                            <div class="modal-content">
                                <dialog id="video_{{ $video }}"
                                    class="miniscrollbar bg-white rounded-lg shadow-2xl aspect-video h-screen">
                                    <div class="flex items-center px-4 py-2 border-b rounded-t dark:border-gray-600">
                                        <h3
                                            class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                                            video
                                        </h3>
                                        <button onclick="closeVideo('{{ $video }}')" type="button"
                                            class="float-end text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-toggle="timeline-modal">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <div>
                                        @php
                                            $url = $sempro->link_video;

                                            // Parsing URL
                                            $parsedUrl = parse_url($url);

                                            // Inisialisasi variabel untuk menyimpan URL baru
                                            $newUrl = '';

                                            // Periksa apakah kunci 'path' ada dalam array $parsedUrl
                                            if (isset($parsedUrl['path'])) {
                                                // Mendapatkan path dari URL
                                                $path = $parsedUrl['path'];

                                                // Periksa apakah ada query string dalam URL
                                                if (isset($parsedUrl['query']) && !empty($parsedUrl['query'])) {
                                                    // Jika ada query string, lakukan parsing
                                                    parse_str($parsedUrl['query'], $query);

                                                    // Mendapatkan bagian ID dari URL
                                                    $videoId = trim($path, '/');

                                                    // Membuat URL baru dengan menggabungkan path dan query yang diubah
                                                    $newUrl =
                                                        'https://www.youtube.com/embed/' .
                                                        $videoId .
                                                        '?' .
                                                        http_build_query($query);
                                                } else {
                                                    // Jika tidak ada query string, URL baru hanya akan berisi path
                                                    $newUrl = 'https://www.youtube.com/embed/' . trim($path, '/');
                                                }
                                            } else {
                                                // Kondisi saat kunci 'path' tidak terdefinisi
                                                $newUrl = asset('error.jpeg');
                                            }
                                        @endphp

                                        <iframe class="w-full aspect-video" src="{{ $newUrl }}"
                                            title="YouTube video player" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen></iframe>
                                    </div>
                                </dialog>
                            </div>
                            {{-- End video Modal --}}
                            <button onclick="showVideo('{{ $video }}')" type="button"
                                class="text-blue-600 text-lg">
                                <i class="bi bi-youtube"></i> Play
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing Akademik</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sempro->tugas_akhir->dosen_PA->nama_dosen }}</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing 1</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sempro->tugas_akhir->dosen_pembimbing_1->nama_dosen }}</td>
                </tr>
                <tr>
                    <th>Dosen Pembimbing 2</th>
                    <td class="font-bold">:</td>
                    <td>{{ $sempro->tugas_akhir->dosen_pembimbing_2->nama_dosen }}</td>
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
                </tr>
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
                    <th>Dokumen Proposal</th>
                    <td class="font-bold">:</td>
                    <td>
                        <x-new-modal :file="$sempro->dokumen_ta" />
                    </td>
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
                </tr>
            </table>
        </div>
        <!-- 🔴 Tombol Batalkan -->
        @if(
           ($aksesAdmin || $aksesKaprodi) &&
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
        @if ($sempro->tugas_akhir->dosen_pembimbing_1_id == $dosenId && $sempro->total_nilai_pembimbing_1 !== null)
        <a href="/dosen/penilaian-sempro/riwayat" type="button"
            class="float-end mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
            Kembali
        </a>
        @elseif ($sempro->tugas_akhir->dosen_pembimbing_2_id == $dosenId && $sempro->total_nilai_pembimbing_2 !== null)
        <a href="/dosen/penilaian-sempro/riwayat" type="button"
            class="float-end mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
            Kembali
        </a>
        @elseif ($sempro->tugas_akhir->dosen_penguji_1_id == $dosenId && $sempro->total_nilai_penguji_1 !== null)
        <a href="/dosen/penilaian-sempro/riwayat" type="button"
            class="float-end mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
            Kembali
        </a>
        @elseif ($sempro->tugas_akhir->dosen_penguji_2_id == $dosenId && $sempro->total_nilai_penguji_2 !== null)
            <a href="/dosen/penilaian-sempro/riwayat" type="button"
                class="float-end mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                Kembali
            </a>
        @else
            <a href="/dosen/penilaian-sempro" type="button"
                class="float-end mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                Kembali
            </a>
        @endif

        @if ($sempro->tugas_akhir->dosen_pembimbing_1_id == $dosenId && $sempro->total_nilai_pembimbing_1 == null)
            <a href="/dosen/penilaian-sempro/nilai/{{ $sempro->slug }}" type="button"
                class="float-end mt-3 text-white bg-gradient-to-r from-orange-500 to-orange-700 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                Nilai
            </a>
        @elseif ($sempro->tugas_akhir->dosen_pembimbing_2_id == $dosenId && $sempro->total_nilai_pembimbing_2 == null)
            <a href="/dosen/penilaian-sempro/nilai/{{ $sempro->slug }}" type="button"
                class="float-end mt-3 text-white bg-gradient-to-r from-orange-500 to-orange-700 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                Nilai
            </a>
        @elseif ($sempro->tugas_akhir->dosen_penguji_1_id == $dosenId && $sempro->total_nilai_penguji_1 == null)
            <a href="/dosen/penilaian-sempro/nilai/{{ $sempro->slug }}" type="button"
                class="float-end mt-3 text-white bg-gradient-to-r from-orange-500 to-orange-700 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                Nilai
            </a>
        @elseif ($sempro->tugas_akhir->dosen_penguji_2_id == $dosenId && $sempro->total_nilai_penguji_2 == null)
            <a href="/dosen/penilaian-sempro/nilai/{{ $sempro->slug }}" type="button"
                class="float-end mt-3 text-white bg-gradient-to-r from-orange-500 to-orange-700 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-orange-300 dark:focus:ring-orange-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2">
                Nilai
            </a>
        @endif
        <div id="modalBatalkan"
            class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
                <h2 class="text-lg font-semibold mb-3 text-gray-700">
                    Batalkan Seminar
                </h2>

                <form method="POST" action="{{ route('seminar.batalkan', $sempro->id) }}">
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
            // open dropdown menu
            dropDownSempro()

            function showVideo(video) {
                var modal = document.getElementById('video_' + video);
                modal.showModal();
            }

            function closeVideo(video) {
                var modal = document.getElementById('video_' + video);
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
