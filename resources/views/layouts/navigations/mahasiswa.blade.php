<div class="">
    <div style="z-index: 20;"
        class="miniscrollbar sidebar lg:float-left fixed top-20 bottom-0 lg:left-0 left-[-300px] duration-1000
      p-2 w-[260px] overflow-y-auto text-center bg-white  h-auto">
        <div>
            <a href="{{ route('dashboard.mahasiswa') }}">
                <div
                    class="{{ Request::routeIs('dashboard.mahasiswa') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-house-door"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Dashboard Mahasiswa</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <a href="{{ route('pengumuman') }}">
                <div
                    class="{{ Request::routeIs('pengumuman') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-megaphone"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Pengumuman</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <a href="{{ route('jadwal-notulen') }}">
                <div
                    class="{{ Request::routeIs('jadwal-notulen') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-calendar"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Jadwal Notulen</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <a href="{{ route('view.judul') }}">
                <div
                    class="{{ Request::routeIs('view.judul') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-search"></i>
                    <div class="flex justify-between w-full items-center">
                        <span class="text-[15px] ml-4 text-gray-800">Pencarian Judul</span>
                        <span class="text-gray-800 text-sm">
                            <i class=""></i>
                        </span>
                    </div>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <a href="{{ route('view.sempro') }}">
                <div
                    class="{{ Request::routeIs('view.sempro') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <img src="{{ Vite::asset('resources/images/sempro.svg') }}" alt="Proposal" width="15rem">
                    <div class="flex justify-between w-full items-center">
                        <span class="text-[15px] ml-4 text-gray-800">Seminar Proposal</span>
                        <span class="text-gray-800 text-sm">
                            <i class=""></i>
                        </span>
                    </div>
                </div>
            </a>

            <a href="{{ route('view.semhas') }}">
                <div
                    class="{{ Request::routeIs('view.semhas') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <img src="{{ Vite::asset('resources/images/semhas.svg') }}" alt="Proposal" width="15rem">
                    <div class="flex justify-between w-full items-center">
                        <span class="text-[15px] ml-4 text-gray-800">Seminar Hasil</span>
                        <span class="text-gray-800 text-sm">
                            <i class=""></i>
                        </span>
                    </div>
                </div>
            </a>

            <a href="{{ route('view.sidang') }}">
                <div
                    class="{{ Request::routeIs('view.sidang') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-mortarboard"></i>
                    <div class="flex justify-between w-full items-center">
                        <span class="text-[15px] ml-4 text-gray-800">Sidang Akhir</span>
                        <span class="text-gray-800 text-sm">
                            <i class=""></i>
                        </span>
                    </div>
                </div>
            </a>


            <hr class="my-4 text-gray-900">

            <a href="{{ route('view.bimbingan') }}">
                <div
                    class="{{ Request::routeIs('view.bimbingan') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-chat-square-quote"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Kontrol Bimbingan</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <div :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="text-gray-800 p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Logout</span>
                </div>
            </form>
        </div>
    </div>
    <div id="overlay" class="z-10 bg-black w-full h-full fixed top-0 left-0 opacity-10" style="display: none"
        onclick="Openbar()"></div>
</div>
</div>

<script>
    // function dropDownSempro() {
    //   document.querySelector('#submenusempro').classList.toggle('hidden')
    //   document.querySelector('#arrowsempro').classList.toggle('rotate-180')
    // }
    // dropDownSempro()

    // function dropDownSemhas() {
    //   document.querySelector('#submenusemhas').classList.toggle('hidden')
    //   document.querySelector('#arrowsemhas').classList.toggle('rotate-180')
    // }
    // dropDownSemhas()

    // function dropDownSidang() {
    //   document.querySelector('#submenusidang').classList.toggle('hidden')
    //   document.querySelector('#arrowsidang').classList.toggle('rotate-180')
    // }
    // dropDownSidang()
</script>
