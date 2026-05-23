<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIMJURU') }}</title>
    <link rel="icon" type="image/png" href="/logo.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50"
      x-data="{
          mobileMenuOpen: false,
          desktopSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
          toggleSidebar() {
              this.desktopSidebarOpen = !this.desktopSidebarOpen;
              localStorage.setItem('sidebarOpen', this.desktopSidebarOpen);
          }
      }">
    <div class="h-screen flex w-full overflow-hidden">

        {{-- ======= DESKTOP SIDEBAR ======= --}}
        <aside class="bg-slate-900 text-white flex-shrink-0 transition-all duration-300 hidden md:flex flex-col h-full relative z-20"
               :class="desktopSidebarOpen ? 'w-64' : 'w-20'">

            {{-- Logo + Toggle Button --}}
            <div class="h-16 flex items-center justify-center border-b border-slate-800 flex-shrink-0 relative">
                <div x-show="desktopSidebarOpen" x-cloak class="flex items-center space-x-2.5">
                    <img src="/logo.png" alt="Logo" class="w-8 h-8 object-contain flex-shrink-0">
                    <span class="text-lg font-extrabold tracking-wider text-white">SIMJURU</span>
                </div>
                <img x-show="!desktopSidebarOpen" x-cloak src="/logo.png" alt="Logo" class="w-8 h-8 object-contain">

                <button @click="toggleSidebar()"
                        class="absolute -right-3 top-1/2 -translate-y-1/2 bg-slate-800 text-gray-400 hover:text-white rounded-full p-1 border border-slate-700 flex items-center justify-center shadow-md z-30">
                    <svg x-show="desktopSidebarOpen" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                    <svg x-show="!desktopSidebarOpen" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            {{-- Scrollable nav area --}}
            <div class="flex-1 overflow-y-auto overflow-x-hidden py-2">
                @include('layouts.navigation')
            </div>

            {{-- Fixed profile + logout at bottom --}}
            <div class="flex-shrink-0 border-t border-slate-800 p-3">
                <div x-show="desktopSidebarOpen" x-cloak class="px-2 mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->username ?? 'G', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->username ?? 'Guest' }}</p>
                            <p class="text-xs text-emerald-400 font-medium">{{ str_replace('_', ' ', Auth::user()->role ?? '') }}</p>
                        </div>
                    </div>
                </div>
                <div x-show="!desktopSidebarOpen" x-cloak class="flex justify-center mb-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(Auth::user()->username ?? 'G', 0, 1)) }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-lg text-red-400 hover:bg-red-950 hover:text-red-300 transition-colors"
                            :class="desktopSidebarOpen ? 'justify-start' : 'justify-center'">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span x-show="desktopSidebarOpen" x-cloak class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ======= MOBILE OVERLAY + SIDEBAR ======= --}}
        <div x-show="mobileMenuOpen"
             class="fixed inset-0 z-40 bg-gray-900/80 backdrop-blur-sm md:hidden"
             x-transition.opacity
             @click="mobileMenuOpen = false"></div>

        <aside x-show="mobileMenuOpen"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white flex flex-col md:hidden"
               x-transition:enter="transition ease-out duration-200"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">

            <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800 flex-shrink-0">
                <div class="flex items-center space-x-2.5">
                    <img src="/logo.png" alt="Logo" class="w-8 h-8 object-contain">
                    <span class="text-lg font-extrabold text-white">SIMJURU</span>
                </div>
                <button @click="mobileMenuOpen = false" class="text-gray-400 hover:text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto py-2">
                @include('layouts.navigation')
            </div>

            <div class="flex-shrink-0 border-t border-slate-800 p-3">
                <div class="px-2 mb-3 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->username ?? 'G', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->username ?? 'Guest' }}</p>
                        <p class="text-xs text-emerald-400 font-medium">{{ str_replace('_', ' ', Auth::user()->role ?? '') }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-lg text-red-400 hover:bg-red-950 hover:text-red-300 transition-colors">
                        <svg class="w-5 h-5 flex-shrink-0 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- ======= MAIN CONTENT ======= --}}
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

            {{-- Mobile topbar --}}
            <div class="md:hidden flex-shrink-0 flex items-center justify-between px-4 py-3 bg-white shadow-sm border-b border-gray-100 z-10">
                <div class="flex items-center space-x-2">
                    <img src="/logo.png" alt="Logo" class="w-7 h-7 object-contain">
                    <span class="text-lg font-extrabold text-slate-800 tracking-wide">SIMJURU</span>
                </div>
                <button @click="mobileMenuOpen = true" class="text-gray-600 hover:text-emerald-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <main class="flex-1 p-4 md:p-6 overflow-y-auto bg-gray-50">
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Global Cascade Delete Confirmation Modal --}}
    <div id="globalDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 m-4 transform scale-95 transition-transform duration-300 flex flex-col gap-4">
            
            {{-- Header --}}
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 border border-red-100 text-red-650">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-gray-900" id="globalDeleteTitle">Konfirmasi Hapus</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                </div>
            </div>

            {{-- Loading State --}}
            <div id="globalDeleteLoading" class="flex flex-col items-center justify-center py-6 gap-3">
                <div class="animate-spin rounded-full h-8 w-8 border-4 border-emerald-500 border-t-transparent"></div>
                <span class="text-xs font-semibold text-gray-500">Memeriksa relasi data...</span>
            </div>

            {{-- Impact / Warning State --}}
            <div id="globalDeleteImpact" class="hidden flex flex-col gap-3">
                <div class="p-3 bg-red-50 border border-red-150 rounded-lg text-[11px] text-red-800 leading-relaxed font-semibold">
                    ⚠️ <strong>Peringatan Penting!</strong> Data <span class="font-extrabold text-red-950" id="globalDeleteTargetName"></span> memiliki data terkait. Jika Anda melanjutkan, data berikut akan ikut terhapus secara otomatis:
                </div>
                
                {{-- List of affected items --}}
                <div class="flex flex-col gap-2 max-h-48 overflow-y-auto pr-1" id="globalDeleteAffectedList">
                    {{-- Dynamically populated --}}
                </div>
            </div>

            {{-- Safe Delete Confirmation Message --}}
            <div id="globalDeleteSafeMessage" class="hidden text-xs font-semibold text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-150">
                Apakah Anda yakin ingin menghapus data <span class="font-extrabold text-emerald-700" id="globalDeleteSafeName"></span>?
            </div>

            {{-- Footer Buttons --}}
            <div class="flex justify-end gap-2.5 mt-2 border-t border-gray-100 pt-4 flex-shrink-0">
                <button type="button" id="globalDeleteCancelBtn" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Batal
                </button>
                <button type="button" id="globalDeleteConfirmBtn" class="px-4 py-2 text-xs font-extrabold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors shadow-sm flex items-center gap-1.5">
                    Hapus Sekarang
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('globalDeleteModal');
        const modalContent = modal.querySelector('div');
        const targetNameEl = document.getElementById('globalDeleteTargetName');
        const safeNameEl = document.getElementById('globalDeleteSafeName');
        const affectedListEl = document.getElementById('globalDeleteAffectedList');
        const loadingEl = document.getElementById('globalDeleteLoading');
        const impactEl = document.getElementById('globalDeleteImpact');
        const safeMessageEl = document.getElementById('globalDeleteSafeMessage');
        const cancelBtn = document.getElementById('globalDeleteCancelBtn');
        const confirmBtn = document.getElementById('globalDeleteConfirmBtn');

        let activeForm = null;

        function openModal() {
            modal.classList.remove('pointer-events-none', 'opacity-0');
            modal.classList.add('opacity-100');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function closeModal() {
            modal.classList.remove('opacity-100');
            modal.classList.add('pointer-events-none', 'opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            activeForm = null;
        }

        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        confirmBtn.addEventListener('click', () => {
            if (activeForm) {
                activeForm.submit();
            }
            closeModal();
        });

        // Intercept native confirm alert popups from click to suppress ugly browser prompt
        document.addEventListener('click', function(event) {
            const btn = event.target.closest('button[type="submit"]');
            if (btn) {
                const form = btn.closest('form');
                if (form) {
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput && methodInput.value === 'DELETE') {
                        form.removeAttribute('onsubmit');
                    }
                }
            }
        });

        // Intercept form submissions
        document.addEventListener('submit', function(event) {
            const form = event.target;
            const methodInput = form.querySelector('input[name="_method"]');
            
            if (methodInput && methodInput.value === 'DELETE') {
                if (activeForm === form) {
                    return;
                }

                event.preventDefault();
                activeForm = form;

                // Reset modal states
                loadingEl.classList.remove('hidden');
                impactEl.classList.add('hidden');
                safeMessageEl.classList.add('hidden');
                affectedListEl.innerHTML = '';
                confirmBtn.disabled = true;
                confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');

                openModal();

                const action = form.getAttribute('action');
                let type = '';
                let id = '';

                // Map route strings to delete types
                if (action.includes('/admin/users/')) {
                    type = 'user';
                    id = action.split('/admin/users/')[1].split('?')[0].split('/')[0];
                } else if (action.includes('/admin/tahun-ajaran/')) {
                    type = 'tahun-ajaran';
                    id = action.split('/admin/tahun-ajaran/')[1].split('?')[0].split('/')[0];
                } else if (action.includes('/admin/kelas/')) {
                    type = 'kelas';
                    id = action.split('/admin/kelas/')[1].split('?')[0].split('/')[0];
                } else if (action.includes('/admin/mapel/')) {
                    type = 'mapel';
                    id = action.split('/admin/mapel/')[1].split('?')[0].split('/')[0];
                } else if (action.includes('/admin/jam-pelajaran/')) {
                    type = 'jam-pelajaran';
                    id = action.split('/admin/jam-pelajaran/')[1].split('?')[0].split('/')[0];
                } else if (action.includes('/admin/jadwal/')) {
                    type = 'jadwal';
                    id = action.split('/admin/jadwal/')[1].split('?')[0].split('/')[0];
                } else if (action.includes('/admin/guru-pengganti/')) {
                    type = 'guru-pengganti';
                    id = action.split('/admin/guru-pengganti/')[1].split('?')[0].split('/')[0];
                }

                if (!type || !id) {
                    loadingEl.classList.add('hidden');
                    safeNameEl.textContent = 'ini';
                    safeMessageEl.classList.remove('hidden');
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    return;
                }

                fetch(`/admin/check-delete/${type}/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    loadingEl.classList.add('hidden');
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');

                    if (data.error) {
                        safeNameEl.textContent = 'ini';
                        safeMessageEl.classList.remove('hidden');
                        return;
                    }

                    if (data.has_dependencies && data.affected && data.affected.length > 0) {
                        targetNameEl.textContent = '"' + data.name + '"';
                        impactEl.classList.remove('hidden');

                        data.affected.forEach(item => {
                            const row = document.createElement('div');
                            row.className = 'flex items-start gap-2.5 p-2 bg-red-50 rounded border border-red-100 text-[11px] leading-relaxed';
                            row.innerHTML = `
                                <div class="w-5 h-5 rounded bg-red-100 flex items-center justify-center text-red-700 font-extrabold flex-shrink-0 text-[10px]">
                                    ${item.count}
                                </div>
                                <div class="flex-1">
                                    <span class="font-bold text-gray-800">${item.label}</span>
                                    <p class="text-gray-500 mt-0.5">${item.description}</p>
                                </div>
                            `;
                            affectedListEl.appendChild(row);
                        });
                    } else {
                        safeNameEl.textContent = '"' + data.name + '"';
                        safeMessageEl.classList.remove('hidden');
                    }
                })
                .catch(err => {
                    console.error(err);
                    loadingEl.classList.add('hidden');
                    confirmBtn.disabled = false;
                    confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    safeNameEl.textContent = 'ini';
                    safeMessageEl.classList.remove('hidden');
                });
            }
        });
    });
    </script>
</body>
</html>
