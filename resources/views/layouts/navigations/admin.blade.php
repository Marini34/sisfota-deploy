<div class="">
    <div style="z-index: 20;" class="miniscrollbar sidebar lg:float-left fixed top-20 bottom-0 lg:left-0 left-[-300px] duration-1000 p-2 w-[260px] overflow-y-auto text-center bg-white h-auto">
        <div>
          <a href="{{ route('dashboard.admin') }}">
            <div class="{{ Request::routeIs('dashboard.admin') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
              <i class="bi bi-house-door"></i>
              <span class="text-[15px] ml-4 text-gray-800">Dashboard Admin</span>
            </div>
          </a>

          <hr class="my-4 text-gray-900">

          <a href="{{route('admin.pengumuman')}}">
            <div class="{{ Request::routeIs('admin.pengumuman') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
              <i class="bi bi-megaphone"></i>
              <span class="text-[15px] ml-4 text-gray-800">Kelola Pengumuman</span>
            </div>
          </a>

          <hr class="my-4 text-gray-900">

          <a href="{{ route('view.judul') }}">
            <div class="{{ Request::routeIs('view.judul') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
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

          <div onclick="dropDownSempro()" class="{{ Request::routeIs('admin.sempro','admin.sempro.riwayat','admin.sempro.berita-acara', 'admin.sempro.rekap') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
            <img src="{{ Vite::asset('resources/images/semhas.svg') }}" alt="Proposal" width="15rem">
            <div class="flex justify-between w-full items-center">
              <span class="text-[15px] ml-4 text-gray-800">Seminar Proposal</span>
              <span class="text-gray-800 text-sm" id="arrowsempro">
                <i class="bi bi-chevron-up"></i>
              </span>
            </div>
          </div>
          <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenusempro">
            <a href="{{ route('admin.sempro') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.sempro') ? 'bg-gray-300' : '' }}">Kelola Pendaftaran</h1></a>
            <a href="{{ route('admin.sempro.riwayat') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.sempro.riwayat') ? 'bg-gray-300' : '' }}">Riwayat Pengelolaan</h1></a>
            <a href="{{ route('admin.sempro.berita-acara') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.sempro.berita-acara') ? 'bg-gray-300' : '' }}">Unduh Berita Acara</h1></a>
            <a href="{{ route('admin.sempro.rekap') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.sempro.rekap') ? 'bg-gray-300' : '' }}">Rekapitulasi</h1></a>
            <hr class="my-2 text-gray-900">
          </div>

          <div onclick="dropDownSemhas()" class="{{ Request::routeIs('admin.semhas','admin.semhas.riwayat','admin.semhas.berita-acara') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
            <img src="{{ Vite::asset('resources/images/semhas.svg') }}" alt="Proposal" width="15rem">
            <div class="flex justify-between w-full items-center">
              <span class="text-[15px] ml-4 text-gray-800">Seminar Hasil</span>
              <span class="text-gray-800 text-sm" id="arrowsemhas">
                <i class="bi bi-chevron-up"></i>
              </span>
            </div>
          </div>
          <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenusemhas">
            <a href="{{ route('admin.semhas') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.semhas') ? 'bg-gray-300' : '' }}">Kelola Pendaftaran</h1></a>
            <a href="{{ route('admin.semhas.riwayat') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.semhas.riwayat') ? 'bg-gray-300' : '' }}">Riwayat Pengelolaan</h1></a>
            <a href="{{ route('admin.semhas.berita-acara') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.semhas.berita-acara') ? 'bg-gray-300' : '' }}">Unduh Berita Acara</h1></a>
            <hr class="my-2 text-gray-900">
          </div>
  
          <div onclick="dropDownSidang()" class="{{ Request::routeIs('admin.sidang','admin.sidang.riwayat','admin.sidang.berita-acara') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
            <i class="bi bi-mortarboard"></i>
            <div class="flex justify-between w-full items-center">
              <span class="text-[15px] ml-4 text-gray-800">Sidang Akhir</span>
              <span class="text-gray-800 text-sm" id="arrowsidang">
                <i class="bi bi-chevron-up"></i>
              </span>
            </div>
          </div>
          <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenusidang">
            <a href="{{route('admin.sidang')}}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.sidang') ? 'bg-gray-300' : '' }}">Kelola Pendaftaran</h1></a>
            <a href="{{route('admin.sidang.riwayat')}}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.sidang.riwayat') ? 'bg-gray-300' : '' }}">Riwayat Pengelolaan Sidang</h1></a>
            <a href="{{route('admin.sidang.berita-acara')}}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.sidang.berita-acara') ? 'bg-gray-300' : '' }}">Unduh Berita Acara</h1></a>
          </div>

          <hr class="my-4 text-gray-900">

          <div onclick="dropDownTranskrip()" class="{{ Request::routeIs('admin.transkrip', 'admin.transkrip.riwayat') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
            <i class="bi bi-file-earmark-ruled"></i>
            <div class="flex justify-between w-full items-center">
              <span class="text-[15px] ml-4 text-gray-800">Kelola Transkrip</span>
              <span class="text-gray-800 text-sm" id="arrowTranskrip">
                <i class="bi bi-chevron-up"></i>
              </span>
            </div>
          </div>
          <div class="leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenuTranskrip">
            <a href="{{route('admin.transkrip')}}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.transkrip') ? 'bg-gray-300' : '' }}">Kelola Transkrip</h1></a>
            <a href="{{route('admin.transkrip.riwayat')}}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.transkrip.riwayat') ? 'bg-gray-300' : '' }}">Riwayat Pengelolaan Transkrip</h1></a>
          </div>

          <hr class="my-4 text-gray-900">

          <div onclick="dropDownPengguna()" class="{{ Request::routeIs('admin.kelola.mahasiswa', 'admin.kelola.dosen') ? 'bg-gray-300' : '' }} text-gray-800 p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
            <i class="bi bi-people"></i>
            <div class="flex justify-between w-full items-center">
              <span class="text-[15px] ml-4 text-gray-800">Kelola Pengguna</span>
              <span class="text-gray-800 text-sm" id="arrowpengguna">
                <i class="bi bi-chevron-up"></i>
              </span>
            </div>
          </div>
          <div class=" leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenupengguna">
            <a href="{{ route('admin.kelola.mahasiswa') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.kelola.mahasiswa') ? 'bg-gray-300' : '' }}">Mahasiswa</h1></a>
            <a href="{{ route('admin.kelola.dosen') }}"><h1 class="cursor-pointer p-2 hover:bg-gray-300 rounded-md mt-1 {{ Request::routeIs('admin.kelola.dosen') ? 'bg-gray-300' : '' }}">Dosen</h1></a>
          </div>

          <hr class="my-4 text-gray-900">
          
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <div :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-gray-800 p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-gray-300">
              <i class="bi bi-box-arrow-in-right"></i>
                <span class="text-[15px] ml-4 text-gray-800">Logout</span>
            </div>
          </form>
        </div>
      </div>
      <div id="overlay" class="z-10 bg-black w-full h-full fixed top-0 left-0 opacity-10" style="display: none" onclick="Openbar()"></div>
    </div>
</div>

<script>
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

  function dropDownTranskrip() {
    document.querySelector('#submenuTranskrip').classList.toggle('hidden')
    document.querySelector('#arrowTranskrip').classList.toggle('rotate-180')
  }
  dropDownTranskrip()

  function dropDownPengguna() {
    document.querySelector('#submenupengguna').classList.toggle('hidden')
    document.querySelector('#arrowpengguna').classList.toggle('rotate-180')
  }
  dropDownPengguna()
</script>