<x-app-layout>
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.jadwal.index') }}" class="p-2 text-gray-500 hover:text-gray-700 bg-white rounded-lg shadow-sm border border-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Edit Jadwal Pelajaran</h2>
                <p class="text-sm text-gray-500">Ubah jadwal mengajar kelas <strong>{{ $jadwal->kelas->nama_kelas }}</strong> pada hari <strong>{{ $jadwal->hari }}</strong>.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
             x-data="{
                hari: '{{ $jadwal->hari }}',
                kelas_id: '{{ $jadwal->kelas_id }}',
                rows: [
                    @foreach($batchJadwals as $bj)
                    { 
                        id: {{ $bj->id }}, 
                        jam_ke_mulai: {{ $bj->jam_ke_mulai }}, 
                        jam_ke_selesai: {{ $bj->jam_ke_selesai }}, 
                        mata_pelajaran_id: '{{ $bj->mata_pelajaran_id }}', 
                        guru_id: '{{ $bj->guru_id }}',
                        error: ''
                    },
                    @endforeach
                ],
                maxJam: {{ $maxJam }},
                isJamDisabled(jam, currentIndex) {
                    jam = parseInt(jam);
                    for (let i = 0; i < this.rows.length; i++) {
                        if (i === currentIndex) continue;
                        let mulai = parseInt(this.rows[i].jam_ke_mulai);
                        let selesai = parseInt(this.rows[i].jam_ke_selesai);
                        if (!isNaN(mulai) && !isNaN(selesai)) {
                            let min = Math.min(mulai, selesai);
                            let max = Math.max(mulai, selesai);
                            if (jam >= min && jam <= max) {
                                return true;
                            }
                        }
                    }
                    return false;
                },
                isMapelDisabled(mapelId, currentIndex) {
                    for (let i = 0; i < this.rows.length; i++) {
                        if (i === currentIndex) continue;
                        if (this.rows[i].mata_pelajaran_id == mapelId && mapelId !== '') {
                            return true;
                        }
                    }
                    return false;
                },
                addRow() {
                    let nextJam = 1;
                    while(this.isJamDisabled(nextJam, -1) && nextJam <= this.maxJam) {
                        nextJam++;
                    }
                    if (nextJam > this.maxJam) {
                        alert('Semua jam pelajaran sudah terisi.');
                        return;
                    }
                    this.rows.push({ id: null, jam_ke_mulai: nextJam, jam_ke_selesai: nextJam, mata_pelajaran_id: '', guru_id: '', error: '' });
                },
                removeRow(index) {
                    if (confirm('Yakin ingin menghapus baris jadwal ini? Jika sudah tersimpan sebelumnya, data akan ikut terhapus dari sistem saat disimpan.')) {
                        this.rows.splice(index, 1);
                    }
                },
                checkRowBentrok(row, currentIndex) {
                    let hari = this.hari;
                    let kelasId = this.kelas_id;
                    let taId = '{{ $jadwal->tahun_ajaran_id }}';
                    
                    if (!hari || !row.guru_id || !row.jam_ke_mulai || !row.jam_ke_selesai) {
                        row.error = '';
                        return;
                    }
                    
                    let url = `{{ route('admin.jadwal.check-bentrok', [], false) }}?tahun_ajaran_id=${taId}&hari=${hari}&kelas_id=${kelasId}&guru_id=${row.guru_id}&jam_ke_mulai=${row.jam_ke_mulai}&jam_ke_selesai=${row.jam_ke_selesai}`;
                    if (row.id) {
                        url += `&exclude_id=${row.id}`;
                    }
                    
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            if (data.bentrok) {
                                row.error = data.pesan;
                            } else {
                                row.error = '';
                            }
                        })
                        .catch(err => {
                            console.error(err);
                        });
                }
             }">
             
            <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="p-4 bg-red-50 text-red-700 rounded-lg text-sm font-medium">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Tahun Ajaran</p>
                        <p class="font-bold text-gray-800">{{ $jadwal->tahunAjaran->tahun }} ({{ $jadwal->tahunAjaran->semester }})</p>
                    </div>
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Kelas</p>
                        <p class="font-bold text-gray-800">{{ $jadwal->kelas->nama_kelas }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-sm">
                        <p class="text-xs text-gray-500 uppercase font-semibold">Hari</p>
                        <p class="font-bold text-gray-800">{{ $jadwal->hari }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-md font-bold text-gray-800">Daftar Jadwal Mengajar</h3>
                        <button type="button" @click="addRow()" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 font-semibold rounded-lg text-sm transition-colors border border-emerald-200">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Jadwal
                        </button>
                    </div>
                    
                    <div class="hidden md:grid grid-cols-12 gap-3 mb-2 px-2">
                        <div class="col-span-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Mulai <span class="text-red-500">*</span></div>
                        <div class="col-span-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Selesai <span class="text-red-500">*</span></div>
                        <div class="col-span-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Mata Pelajaran <span class="text-red-500">*</span></div>
                        <div class="col-span-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Guru Utama <span class="text-red-500">*</span></div>
                        <div class="col-span-1 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</div>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(row, index) in rows" :key="index">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 p-4 md:p-0 border border-gray-200 md:border-transparent rounded-lg bg-gray-50 md:bg-transparent items-center" x-effect="checkRowBentrok(row, index)">
                                
                                <input type="hidden" x-bind:name="`jadwals[${index}][id]`" x-model="row.id">
                                
                                <div class="md:col-span-2 flex flex-col md:block">
                                    <label class="md:hidden text-xs font-semibold text-gray-500 uppercase mb-1">Jam Ke- Mulai</label>
                                    <select x-bind:name="`jadwals[${index}][jam_ke_mulai]`" x-model="row.jam_ke_mulai" @change="checkRowBentrok(row, index)" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" required>
                                        @for($i = 1; $i <= $maxJam; $i++)
                                            @php
                                                $jamObj = $jamPelajarans[$i] ?? null;
                                                $jamStr = $jamObj ? ' (' . \Carbon\Carbon::parse($jamObj->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($jamObj->jam_selesai)->format('H:i') . ')' : '';
                                            @endphp
                                            <option value="{{ $i }}" x-bind:hidden="isJamDisabled({{ $i }}, index)" x-bind:disabled="isJamDisabled({{ $i }}, index)">Jam Ke-{{ $i }}{{ $jamStr }}</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div class="md:col-span-2 flex flex-col md:block">
                                    <label class="md:hidden text-xs font-semibold text-gray-500 uppercase mb-1">Jam Ke- Selesai</label>
                                    <select x-bind:name="`jadwals[${index}][jam_ke_selesai]`" x-model="row.jam_ke_selesai" @change="checkRowBentrok(row, index)" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" required>
                                        @for($i = 1; $i <= $maxJam; $i++)
                                            @php
                                                $jamObj = $jamPelajarans[$i] ?? null;
                                                $jamStr = $jamObj ? ' (' . \Carbon\Carbon::parse($jamObj->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($jamObj->jam_selesai)->format('H:i') . ')' : '';
                                            @endphp
                                            <option value="{{ $i }}" x-bind:hidden="isJamDisabled({{ $i }}, index)" x-bind:disabled="isJamDisabled({{ $i }}, index)">Jam Ke-{{ $i }}{{ $jamStr }}</option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div class="md:col-span-4 flex flex-col md:block">
                                    <label class="md:hidden text-xs font-semibold text-gray-500 uppercase mb-1">Mata Pelajaran</label>
                                    <select x-bind:name="`jadwals[${index}][mata_pelajaran_id]`" x-model="row.mata_pelajaran_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($mataPelajarans as $mapel)
                                            <option value="{{ $mapel->id }}" x-bind:hidden="isMapelDisabled({{ $mapel->id }}, index)" x-bind:disabled="isMapelDisabled({{ $mapel->id }}, index)">{{ $mapel->nama_mapel }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-3 flex flex-col md:block">
                                    <label class="md:hidden text-xs font-semibold text-gray-500 uppercase mb-1">Guru Utama</label>
                                    <select x-bind:name="`jadwals[${index}][guru_id]`" x-model="row.guru_id" @change="checkRowBentrok(row, index)" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" required>
                                        <option value="">-- Pilih --</option>
                                        @foreach($gurus as $guru)
                                            <option value="{{ $guru->id }}">{{ $guru->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="md:col-span-1 text-right md:text-center mt-2 md:mt-0">
                                    <button type="button" @click="removeRow(index)" class="inline-flex items-center justify-center p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Baris">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span class="md:hidden ml-1 text-sm font-medium">Hapus</span>
                                    </button>
                                </div>

                                {{-- Real-time Conflict Alert --}}
                                <div x-show="row.error" class="md:col-span-12 text-xs font-semibold text-red-650 bg-red-50/70 border border-red-100 rounded-lg px-3 py-2 mt-1 flex items-center shadow-sm">
                                    <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span x-text="row.error"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.jadwal.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm transition-colors">
                        Simpan Perubahan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
