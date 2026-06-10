<x-app-layout>
    @section('title', 'Edit Parameter Penilaian Sempro')
    @section('head')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <style>
            .trix-editor {
                width: 100%;
                min-height: 6.2rem;
            }

            trix-toolbar [data-trix-button-group="file-tools"] {
                display: none;
            }

            @media (max-width: 640px) {
                .trix-editor {
                    width: 100%;
                    min-height: 18rem;
                }
            }
        </style>
    @endsection
    <x-main-card>
        @if (session()->has('fail'))
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
        <form action="{{ route('edit.parameter.semhas', $parameter->id) }}" method="post">
            @csrf
            <label for="editNama">Nama Parameter<span class="text-red-600">*</span></label>
            <div class="my-2">
                <input type="text" name="editNama" id="editNama"
                    value="{{ old('editNama', $parameter->nama_parameter) }}" class="w-full rounded-md border-slate-300"
                    required></input>
            </div>
            <br>
            <label for="editDeskripsi">Deskripsi Parameter<span class="text-red-600">*</span></label>
            <div class="border border-slate-300 rounded-md">
                <input id="editDeskripsi" type="hidden" name="editDeskripsi">
                <trix-editor input="editDeskripsi" class="trix-editor"
                    placeholder="Masukkan deskripsi parameter...">@php
                        $formattedText = $parameter->deskripsi_parameter;
                        if (strpos($formattedText, '<ol>') !== false) {
                            $formattedText = str_replace('<ol>', '<ol class="list-decimal pl-7">', $formattedText);
                        }
                        if (strpos($formattedText, '<ul>') !== false) {
                            $formattedText = str_replace('<ul>', '<ul class="list-disc pl-7">', $formattedText);
                        }
                        echo $formattedText;
                    @endphp</trix-editor>
            </div>
            <br>
            <label for="editPersentase">Pesentase Parameter<span class="text-red-600">*</span></label>
            <div class="my-2">
                <input type="number" min="0" max="{{100- $totalPersentase}}" name="editPersentase"
                    value="{{ old('editPersentase', $parameter->persentase) }}" id="editPersentase"
                    class="w-full rounded-md border-slate-300" required>
            </div>

            <br>
            <div class="grid grid-cols-2 gap-10">
                <a href="/kaprodi/parameter-sempro" type="button"
                    class="cursor-pointer text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                    Kembali
                </a>
                <button type="submit" 
                    class="cursor-pointer text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-md px-5 py-2.5 text-center">
                    Edit
                </button>
            </div>
        </form>
    </x-main-card>
</x-app-layout>
