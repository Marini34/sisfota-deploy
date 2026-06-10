@use('Illuminate\Support\Facades\Vite')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIMTA SISFO') - SIMTA Sistem Informasi</title>

    <link rel="icon" href="{{ Vite::asset('resources/images/Logo.png') }}" />

    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('head')
</head>

<body class="bg-gray-200">

    {{-- SIDEBAR --}}
    @if (auth()->user()->hasRole('dosen') && auth()->user()->hasRole('kaprodi'))
        @include('layouts.navigations.kaprodi')
    @elseif (auth()->user()->hasRole('dosen'))
        @include('layouts.navigations.dosen')
    @endif

    @role('mahasiswa')
        @include('layouts.navigations.mahasiswa')
    @endrole

    @role('admin')
        @include('layouts.navigations.admin')
    @endrole

    {{-- NAVBAR --}}
    <div class="fixed top-0 w-full bg-white h-20 border-b flex justify-between px-4 items-center">

        <div class="flex items-center gap-4">
            <span class="text-3xl cursor-pointer lg:hidden" onclick="Openbar()">
                <i class="bi bi-filter-left"></i>
            </span>

            <a href="/dashboard">
                <img src="{{ asset('img/LOGO SIMTA-black.png') }}" class="w-32">
            </a>
        </div>

        {{-- RIGHT --}}
        <div class="flex items-center gap-4 relative">

            @php
                $unread = $notifikasis->where('dibaca', false)->count();
                $sortedNotif = $notifikasis->sortByDesc('created_at')->sortBy('dibaca');
            @endphp

            {{-- 🔔 NOTIF --}}
            <div class="relative">

                <button onclick="toggleNotif()" class="relative">
                    <i class="bi bi-bell text-2xl"></i>

                    @if ($unread > 0)
                        <span id="notifBadge"
                            class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] px-1.5 rounded-full">
                            {{ $unread }}
                        </span>
                    @endif
                </button>

                    <div id="notifDropdown"
                        class="hidden fixed top-20 right-4 w-80 bg-white border rounded-xl shadow-2xl z-[9999]">

                    <div class="px-4 py-3 border-b font-semibold">
                        Notifikasi
                    </div>

                   <div class="h-72 overflow-y-auto pr-2 custom-scroll">

                        @forelse ($sortedNotif as $notif)
                            <div id="notif-{{ $notif->id }}"
                                class="px-4 py-3 border-b text-sm flex justify-between gap-2
                                {{ !$notif->dibaca ? 'bg-blue-50' : 'text-gray-500' }}">

                                <p class="flex-1 {{ !$notif->dibaca ? 'font-semibold text-gray-900' : '' }}">
                                    {{ $notif->keterangan }}
                                </p>

                                @if (!$notif->dibaca)
                                    <button onclick="markAsRead({{ $notif->id }})"
                                        class="text-xs text-blue-600 hover:underline">
                                        Tandai dibaca
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div class="px-4 py-3 text-center text-gray-400">
                                Tidak ada notifikasi
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

            {{-- PROFILE --}}
            <x-dropdown-link :href="route('profile.edit')">
                <div class="hover:bg-gray-100 p-2 rounded-lg">
                    <i class="bi bi-person-circle text-2xl"></i>
                    <p class="text-sm text-center">Pengaturan</p>
                </div>
            </x-dropdown-link>

        </div>
    </div>

    {{-- CONTENT --}}
    <main>
        <div class="pt-24 lg:w-4/5 lg:float-right">
            <div class="px-4">
                {{ $slot }}
            </div>
        </div>
    </main>

</body>

{{-- ================= SCRIPT ================= --}}

<script>
    function toggleNotif() {
        document.getElementById('notifDropdown').classList.toggle('hidden');
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('#notifDropdown') && !e.target.closest('button')) {
            document.getElementById('notifDropdown').classList.add('hidden');
        }
    });
</script>

<script>
    function Openbar() {
        document.querySelector('.sidebar')?.classList.toggle('left-[-300px]');
    }
</script>

{{-- 🔥 ROLE PREFIX --}}
<script>
    const ROLE_PREFIX = "{{ auth()->user()->hasRole('kaprodi') ? '/kaprodi' : '/dosen' }}";
</script>

{{-- 🔥 FIX TANDAI DIBACA --}}
<script>
    function markAsRead(id) {
        const el = document.querySelector(`#notif-${id}`);

        fetch(`${ROLE_PREFIX}/notifikasi/${id}/dibaca`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {

                    // update badge
                    const badge = document.getElementById('notifBadge');

                    if (badge) {
                        let count = parseInt(badge.innerText);
                        if (count > 1) {
                            badge.innerText = count - 1;
                        } else {
                            badge.remove();
                        }
                    }

                    // 🔥 UBAH JADI TERBACA (TIDAK DIHAPUS)
                    if (el) {
                        el.classList.remove('bg-blue-50');
                        el.classList.add('text-gray-500');

                        const text = el.querySelector('p');
                        if (text) {
                            text.classList.remove('font-semibold', 'text-gray-900');
                        }

                        const btn = el.querySelector('button');
                        if (btn) btn.remove();
                    }
                }
            })
            .catch(err => console.error(err));
    }
</script>

    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

@yield('script')

</html>