@props(['file'])
@if (pathinfo($file, PATHINFO_EXTENSION) == 'zip')
    <a href="{{ asset('storage/' . $file) }}"><u class="text-blue-600">{{ $file }}</u></a>
@else
    <div class="flex justify-start flex-wrap">
        {{-- Lampiran Modal --}}
        <div class="modal-content">
            <dialog id="lampiran_{{ $file }}"
                class="miniscrollbar bg-white rounded-lg shadow-2xl w-11/12 h-screen">
                <div class="flex items-center px-4 py-2 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg text-center font-semibold text-gray-900 dark:text-white uppercase">
                        Lampiran
                    </h3>
                    <button onclick="closeLampiran('{{ $file }}')" type="button"
                        class="float-end text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="timeline-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div>
                    {{-- <iframe src="https://docs.google.com/viewer?url={{ asset('storage/' . $file) }}&embedded=true" class="w-full" style="height:85vh;"></iframe> --}}
                    <iframe src="{{ asset('storage/' . $file) }}" class="w-full" style="height:85vh;"></iframe>
                </div>
            </dialog>
        </div>
        {{-- End Lampiran Modal --}}
        <button onclick="show('{{ $file }}')" type="button" class="text-blue-600 underline text-left">
            {{ $file }}
        </button>
    </div>
@endif
