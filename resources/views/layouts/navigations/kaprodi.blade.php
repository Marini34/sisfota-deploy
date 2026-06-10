<div class="miniscrollbar">
    <div
        class="z-50 sidebar miniscrollbar lg:float-left fixed top-20 bottom-0 lg:left-0 left-[-300px] duration-300 ease-linear
    p-2 w-[260px] overflow-y-auto text-center bg-white h-auto">
        <div>
            <a href="{{ route('dashboard.kaprodi') }}">
                <div
                    class="{{ Request::routeIs('dashboard.kaprodi') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-house-door"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Dashboard Kaprodi</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">
            <a href="{{ route('dashboard.dosen') }}">
                <div
                    class="{{ Request::routeIs('dashboard.dosen') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-house-door"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Dashboard Dosen</span>
                </div>
            </a>
            <hr class="my-4 text-gray-900">

            <div onclick="dropDownMonitoring()"
                class="{{ request()->routeIs('monitor.*') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-gray-300">
                <img src="{{ Vite::asset('resources/images/monitoring.png') }}" alt="Monitoring" width="15rem">
                <div class="flex justify-between w-full items-center">
                    <span class="text-[15px] ml-4 text-gray-800">Monitoring</span>
                    <span class="text-gray-800 text-sm" id="arrowmonitoring">
                        <i class="bi bi-chevron-up"></i>
                    </span>
                </div>
            </div>
            <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenumonitoring">
                <a href="{{ route('monitor.monBimbinganMhs') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('monitor.monBimbinganMhs') ? 'bg-gray-300' : '' }}">
                        Bimbingan</h1>
                </a>
                <a href="{{ route('monitor.repoMhs') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('monitor.repoMhs') ? 'bg-gray-300' : '' }}">
                        Repositori</h1>
                </a>
                <hr class="my-2 text-gray-900">
            </div>

            <hr class="my-4 text-gray-900">

            <a href="{{ url('kaprodi/penjadwalan') }}">
                <div
                    class="{{ Request::is('kaprodi/penjadwalan*') ? 'bg-gray-300' : '' }}  text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-calendar"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Penentuan Penguji</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <a href="{{ route('dosen.pengumuman') }}">
                <div
                    class="{{ Request::routeIs('dosen.pengumuman') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-megaphone"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Pengumuman</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <a href="{{ route('dosen.validasi-notulen') }}">
                <div
                    class="{{ Request::routeIs('dosen.validasi-notulen') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-check-circle"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Validasi Notulen</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <div onclick="dropDownSempro()"
                class="{{ Request::routeIs('dosen.sempro.penilaian', 'dosen.sempro.riwayat', 'dosen.sempro.rekap') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                <img src="{{ Vite::asset('resources/images/sempro.svg') }}" alt="Proposal" width="15rem">
                <div class="flex justify-between w-full items-center">
                    <span class="text-[15px] ml-4 text-gray-800">Seminar Proposal</span>
                    <span class="text-gray-800 text-sm" id="arrowsempro">
                        <i class="bi bi-chevron-up"></i>
                    </span>
                </div>
            </div>
            <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenusempro">
                <a href="{{ route('dosen.sempro.penilaian') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('dosen.sempro.penilaian') ? 'bg-gray-300' : '' }}">
                        Penilaian</h1>
                </a>
                <a href="{{ route('dosen.sempro.riwayat') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('dosen.sempro.riwayat') ? 'bg-gray-300' : '' }}">
                        Riwayat Penilaian</h1>
                </a>
                <a href="{{ route('dosen.sempro.dibatalkan') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1
                        {{ Request::routeIs('dosen.sempro.dibatalkan') ? 'bg-gray-300 font-semibold' : '' }}">
                        Riwayat Pembatalan
                    </h1>
                </a>
                <a href="{{ route('dosen.sempro.rekap') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('dosen.sempro.rekap') ? 'bg-gray-300' : '' }}">
                        Rekapitulasi</h1>
                </a>
                <hr class="my-2 text-gray-900">
            </div>

            <div onclick="dropDownSemhas()"
                class="{{ Request::routeIs('dosen.semhas.penilaian', 'dosen.semhas.riwayat') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                <img src="{{ Vite::asset('resources/images/semhas.svg') }}" alt="Proposal" width="15rem">
                <div class="flex justify-between w-full items-center">
                    <span class="text-[15px] ml-4 text-gray-800">Seminar Hasil</span>
                    <span class="text-gray-800 text-sm" id="arrowsemhas">
                        <i class="bi bi-chevron-up"></i>
                    </span>
                </div>
            </div>
            <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenusemhas">
                <a href="{{ route('dosen.semhas.penilaian') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('dosen.semhas.penilaian') ? 'bg-gray-300' : '' }}">
                        Penilaian</h1>
                </a>
                <a href="{{ route('dosen.semhas.riwayat') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('dosen.semhas.riwayat') ? 'bg-gray-300' : '' }}">
                        Riwayat Penilaian</h1>
                </a>
                <hr class="my-2 text-gray-900">
            </div>

            <div onclick="dropDownSidang()"
                class="{{ Request::routeIs('dosen.sidang.penilaian', 'dosen.sidang.riwayat') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                <i class="bi bi-mortarboard"></i>
                <div class="flex justify-between w-full items-center">
                    <span class="text-[15px] ml-4 text-gray-800">Sidang Akhir</span>
                    <span class="text-gray-800 text-sm" id="arrowsidang">
                        <i class="bi bi-chevron-up"></i>
                    </span>
                </div>
            </div>
            <div class=" leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenusidang">
                <a href="{{ route('dosen.sidang.penilaian') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('dosen.sidang.penilaian') ? 'bg-gray-300' : '' }}">
                        Penilaian</h1>
                </a>
                <a href="{{ route('dosen.sidang.riwayat') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('dosen.sidang.riwayat') ? 'bg-gray-300' : '' }}">
                        Riwayat Penilaian</h1>
                </a>
            </div>

            <hr class="my-4 text-gray-900">



            <div onclick="dropDownBimbingan()"
                class="{{ Request::routeIs('jadwal-bimbingan.*', 'dosen.bimbingan', 'dosen.aktif.bimbingan', 'dosen.lulus.bimbingan') ? 'bg-gray-300' : '' }}
            text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-gray-300">

                <i class="bi bi-chat-square-quote"></i>

                <div class="flex justify-between w-full items-center">
                    <span class="text-[15px] ml-4 text-gray-800">Kontrol Bimbingan</span>
                    <span class="text-gray-800 text-sm" id="arrowbimbingan">
                        <i class="bi bi-chevron-up"></i>
                    </span>
                </div>
            </div>

            <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenubimbingan">

                <a href="{{ route('jadwal-bimbingan.index') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1
                    {{ Request::routeIs('jadwal-bimbingan.*') ? 'bg-gray-300' : '' }}">
                        Kelola Jadwal Bimbingan
                    </h1>
                </a>

                <a href="{{ route('dosen.bimbingan') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1
                    {{ Request::routeIs('dosen.bimbingan') ? 'bg-gray-300' : '' }}">
                        Kelola Bimbingan
                    </h1>
                </a>

                <a href="{{ route('dosen.aktif.bimbingan') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1
                    {{ Request::routeIs('dosen.aktif.bimbingan') ? 'bg-gray-300' : '' }}">
                        Daftar Bimbingan Aktif
                    </h1>
                </a>

                <a href="{{ route('dosen.lulus.bimbingan') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1
                    {{ Request::routeIs('dosen.lulus.bimbingan') ? 'bg-gray-300' : '' }}">
                        Daftar Bimbingan Lulus
                    </h1>
                </a>

            </div>

            <hr class="my-4 text-gray-900">



            <div onclick="dropDownDiuji()"
                class="{{ Request::routeIs('dosen.lulus.diuji', 'dosen.lulus.diuji') ? 'bg-gray-300' : '' }}
            text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer hover:bg-gray-300">

                <i class="bi bi-chat-square-quote"></i>

                <div class="flex justify-between w-full items-center">
                    <span class="text-[15px] ml-4 text-gray-800">Daftar Uji</span>
                    <span class="text-gray-800 text-sm" id="arrowDiuji">
                        <i class="bi bi-chevron-up"></i>
                    </span>
                </div>
            </div>

            <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenuDiuji">


                <a href="{{ route('dosen.aktif.diuji') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1
                    {{ Request::routeIs('dosen.aktif.diuji') ? 'bg-gray-300' : '' }}">
                        Diuji Aktif
                    </h1>
                </a>

                <a href="{{ route('dosen.lulus.diuji') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1
                    {{ Request::routeIs('dosen.lulus.diuji') ? 'bg-gray-300' : '' }}">
                        Diuji Lulus
                    </h1>
                </a>

            </div>

            <hr class="my-4 text-gray-900">

            <a href="{{ route('dosen.repositori') }}">
                <div
                    class="{{ Request::routeIs('dosen.repositori') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                    <i class="bi bi-archive"></i>
                    <span class="text-[15px] ml-4 text-gray-800">Repositori File TA</span>
                </div>
            </a>

            <hr class="my-4 text-gray-900">

            <div onclick="dropDownParameter()"
                class="{{ Request::routeIs('admin.parameter.sempro', 'admin.parameter.semhas', 'admin.parameter.sidang') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
                <i class="bi bi-speedometer2"></i>
                <div class="flex justify-between w-full items-center">
                    <span class="text-[15px] ml-4 text-gray-800">Parameter Penilaian</span>
                    <span class="text-gray-800 text-sm" id="arrowparameter">
                        <i class="bi bi-chevron-up"></i>
                    </span>
                </div>
            </div>
            <div class=" leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenuparameter">
                <a href="{{ route('admin.parameter.sempro') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.parameter.sempro') ? 'bg-gray-300' : '' }}">
                        Seminar Proposal</h1>
                </a>
                <a href="{{ route('admin.parameter.semhas') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.parameter.semhas') ? 'bg-gray-300' : '' }}">
                        Seminar Hasil</h1>
                </a>
                <a href="{{ route('admin.parameter.sidang') }}">
                    <h1
                        class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.parameter.sidang') ? 'bg-gray-300' : '' }}">
                        Sidang Akhir</h1>
                </a>
            </div>

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
    function dropDownParameter() {
        document.querySelector('#submenuparameter').classList.toggle('hidden')
        document.querySelector('#arrowparameter').classList.toggle('rotate-180')
    }
    dropDownParameter()

    function dropDownMonitoring() {
        document.querySelector('#submenumonitoring').classList.toggle('hidden')
        document.querySelector('#arrowmonitoring').classList.toggle('rotate-180')
    }
    dropDownMonitoring()

    function dropDownSempro() {
        document.querySelector('#submenusempro').classList.toggle('hidden')
        document.querySelector('#arrowsempro').classList.toggle('rotate-180')
    }
    dropDownSempro()

    function dropDownSemhas() {
        document.querySelector('#submenusemhas').classList.toggle('hidden')
        document.querySelector('#arrowsemhas').classList.toggle('rotate-180')
    }
    dropDownSemhas()

    function dropDownSidang() {
        document.querySelector('#submenusidang').classList.toggle('hidden')
        document.querySelector('#arrowsidang').classList.toggle('rotate-180')
    }
    dropDownSidang()

    function dropDownBimbingan() {
        document.querySelector('#submenubimbingan').classList.toggle('hidden')
        document.querySelector('#arrowbimbingan').classList.toggle('rotate-180')
    }
    dropDownBimbingan()

    function dropDownDiuji() {
        document.querySelector('#submenuDiuji').classList.toggle('hidden')
        document.querySelector('#arrowDiuji').classList.toggle('rotate-180')
    }
    dropDownDiuji()
</script>
