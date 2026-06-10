<x-app-layout>
    @section('title', 'Hasil Semhas')
    <x-main-card>
        <div class="overflow-x-auto miniscrollbar">
            <table class="table table-striped table-hover" style="width: 100%">
                <caption class="caption-top font-bold text-xl text-slate-900 uppercase mb-2 tracking-wide">
                    Hasil Sidang Akhir
                    <hr class="mt-2 border border-l border-slate-900">
                </caption>

                <tr>
                    <th style="width: 26%">Judul</th>
                    <td class="font-bold" style="width: 1%">:</td>
                    <td>{{ $sidang->tugas_akhir->judul }}</td>
                </tr>
                @if ($sidang->is_publish)
                    <tr>
                        <th>Nilai</th>
                        <td class="font-bold">:</td>
                        <td>{{ number_format($semhas->total_nilai_akhir * (40 / 100) + $sidang->total_nilai_akhir * (60 / 100), 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td class="font-bold">:</td>
                        <td>
                            <div class="flex items-center tracking-wider font-medium px-2 py-1 w-fit text-sm text-green-500 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 dark:border-green-800"
                                role="alert">
                                {{ $sidang->status_kelulusan }}
                            </div>
                        </td>
                    </tr>
                @endif
                <tr>
                    <th>Catatan Dosen Pembimbing 1</th>
                    <td class="font-bold">:</td>
                    <td>
                        @php
                            $formattedText = $sidang->catatan_ta_pembimbing_1;
                            if (strpos($formattedText, '<ol>') !== false) {
                                $formattedText = str_replace('<ol>', '<ol class="list-decimal pl-7">', $formattedText);
                            }
                            if (strpos($formattedText, '<ul>') !== false) {
                                $formattedText = str_replace('<ul>', '<ul class="list-disc pl-7">', $formattedText);
                            }
                            echo $formattedText;
                        @endphp
                    </td>
                </tr>
                <tr>
                    <th>Catatan Dosen Pembimbing 2</th>
                    <td class="font-bold">:</td>
                    <td>
                        @php
                            $formattedText = $sidang->catatan_ta_pembimbing_2;
                            if (strpos($formattedText, '<ol>') !== false) {
                                $formattedText = str_replace('<ol>', '<ol class="list-decimal pl-7">', $formattedText);
                            }
                            if (strpos($formattedText, '<ul>') !== false) {
                                $formattedText = str_replace('<ul>', '<ul class="list-disc pl-7">', $formattedText);
                            }
                            echo $formattedText;
                        @endphp
                    </td>
                </tr>
                <tr>
                    <th>Catatan Dosen Penguji 1</th>
                    <td class="font-bold">:</td>
                    <td>
                        @php
                            $formattedText = $sidang->catatan_ta_penguji_1;
                            if (strpos($formattedText, '<ol>') !== false) {
                                $formattedText = str_replace('<ol>', '<ol class="list-decimal pl-7">', $formattedText);
                            }
                            if (strpos($formattedText, '<ul>') !== false) {
                                $formattedText = str_replace('<ul>', '<ul class="list-disc pl-7">', $formattedText);
                            }
                            echo $formattedText;
                        @endphp
                    </td>
                </tr>
                <tr>
                    <th>Catatan Dosen Penguji 2</th>
                    <td class="font-bold">:</td>
                    <td>
                        @php
                            $formattedText = $sidang->catatan_ta_penguji_2;
                            if (strpos($formattedText, '<ol>') !== false) {
                                $formattedText = str_replace('<ol>', '<ol class="list-decimal pl-7">', $formattedText);
                            }
                            if (strpos($formattedText, '<ul>') !== false) {
                                $formattedText = str_replace('<ul>', '<ul class="list-disc pl-7">', $formattedText);
                            }
                            echo $formattedText;
                        @endphp
                    </td>
                </tr>

            </table>
        </div>
        <div class="float-end">
            <a href="/mahasiswa/sidang" type="button"
                class="mt-3 text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                Kembali
            </a>
        </div>
    </x-main-card>
</x-app-layout>
