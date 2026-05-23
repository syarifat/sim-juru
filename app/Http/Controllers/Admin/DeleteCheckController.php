<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\MasterJamPelajaran;
use App\Models\Jadwal;
use App\Models\JurnalGuru;
use App\Models\GuruPengganti;
use Illuminate\Http\JsonResponse;

class DeleteCheckController extends Controller
{
    /**
     * Check what related records will be affected (cascade-deleted) if the target record is deleted.
     */
    public function check(string $type, int $id): JsonResponse
    {
        $affected = [];
        $hasDependencies = false;
        $name = '';

        switch ($type) {
            case 'user':
                $user = User::with('guru')->find($id);
                if (!$user) {
                    return response()->json(['error' => 'User tidak ditemukan.'], 404);
                }

                $name = $user->username;

                if ($user->role === 'Admin') {
                    $affected[] = [
                        'label' => 'Akun Administrator',
                        'count' => 1,
                        'description' => 'Akun login ini akan dihapus secara permanen.'
                    ];
                } else {
                    $guru = $user->guru;
                    if ($guru) {
                        $name = $guru->nama_lengkap;
                        $hasDependencies = true;

                        // Akun Login & Profil Guru
                        $affected[] = [
                            'label' => 'Profil Guru & Akun Login',
                            'count' => 1,
                            'description' => 'Akun login ' . $user->username . ' dan biodata guru akan dihapus permanen.'
                        ];

                        // Jadwal Pelajaran
                        $jadwalCount = Jadwal::where('guru_id', $guru->id)->count();
                        if ($jadwalCount > 0) {
                            $affected[] = [
                                'label' => 'Jadwal Mengajar',
                                'count' => $jadwalCount,
                                'description' => 'Jadwal pelajaran yang diajar oleh guru ini akan terhapus.'
                            ];
                        }

                        // Jurnal Mengajar (diisi oleh guru ini, ATAU diisi pada jadwal guru ini)
                        $jadwalIds = Jadwal::where('guru_id', $guru->id)->pluck('id');
                        $jurnalCount = JurnalGuru::where('guru_pengisi_id', $guru->id)
                            ->orWhereIn('jadwal_id', $jadwalIds)
                            ->count();
                        if ($jurnalCount > 0) {
                            $affected[] = [
                                'label' => 'Jurnal Mengajar Harian',
                                'count' => $jurnalCount,
                                'description' => 'Semua pengisian jurnal mengajar harian (oleh guru ini atau pada jadwalnya) akan terhapus.'
                            ];
                        }

                        // Guru Pengganti (Inval)
                        $invalCount = GuruPengganti::where('guru_pengganti_id', $guru->id)
                            ->orWhereIn('jadwal_id', $jadwalIds)
                            ->count();
                        if ($invalCount > 0) {
                            $affected[] = [
                                'label' => 'Tugas Guru Pengganti (Inval)',
                                'count' => $invalCount,
                                'description' => 'Tugas inval/guru pengganti yang dikaitkan dengan guru ini akan dibatalkan.'
                            ];
                        }
                    }
                }
                break;

            case 'tahun-ajaran':
                $ta = TahunAjaran::find($id);
                if (!$ta) {
                    return response()->json(['error' => 'Tahun Ajaran tidak ditemukan.'], 404);
                }

                $name = $ta->tahun . ' (' . $ta->semester . ')';
                
                // Jadwal
                $jadwalCount = Jadwal::where('tahun_ajaran_id', $id)->count();
                if ($jadwalCount > 0) {
                    $hasDependencies = true;
                    $affected[] = [
                        'label' => 'Jadwal Pelajaran',
                        'count' => $jadwalCount,
                        'description' => 'Jadwal mengajar yang terdaftar pada tahun ajaran & semester ini akan terhapus.'
                    ];

                    // Jurnal & Inval
                    $jadwalIds = Jadwal::where('tahun_ajaran_id', $id)->pluck('id');
                    $jurnalCount = JurnalGuru::whereIn('jadwal_id', $jadwalIds)->count();
                    if ($jurnalCount > 0) {
                        $affected[] = [
                            'label' => 'Jurnal Mengajar Harian',
                            'count' => $jurnalCount,
                            'description' => 'Jurnal mengajar harian guru yang dibuat pada tahun ajaran ini akan terhapus.'
                        ];
                    }

                    $invalCount = GuruPengganti::whereIn('jadwal_id', $jadwalIds)->count();
                    if ($invalCount > 0) {
                        $affected[] = [
                            'label' => 'Tugas Guru Pengganti (Inval)',
                            'count' => $invalCount,
                            'description' => 'Penugasan guru pengganti pada jadwal tahun ajaran ini akan dibatalkan.'
                        ];
                    }
                }
                break;

            case 'kelas':
                $kelas = Kelas::find($id);
                if (!$kelas) {
                    return response()->json(['error' => 'Kelas tidak ditemukan.'], 404);
                }

                $name = $kelas->nama_kelas;

                // Jadwal
                $jadwalCount = Jadwal::where('kelas_id', $id)->count();
                if ($jadwalCount > 0) {
                    $hasDependencies = true;
                    $affected[] = [
                        'label' => 'Jadwal Pelajaran Kelas',
                        'count' => $jadwalCount,
                        'description' => 'Semua jadwal mengajar guru di kelas ' . $kelas->nama_kelas . ' akan terhapus.'
                    ];

                    // Jurnal & Inval
                    $jadwalIds = Jadwal::where('kelas_id', $id)->pluck('id');
                    $jurnalCount = JurnalGuru::whereIn('jadwal_id', $jadwalIds)->count();
                    if ($jurnalCount > 0) {
                        $affected[] = [
                            'label' => 'Jurnal Mengajar Harian',
                            'count' => $jurnalCount,
                            'description' => 'Laporan jurnal mengajar guru untuk kelas ' . $kelas->nama_kelas . ' akan terhapus.'
                        ];
                    }

                    $invalCount = GuruPengganti::whereIn('jadwal_id', $jadwalIds)->count();
                    if ($invalCount > 0) {
                        $affected[] = [
                            'label' => 'Tugas Guru Pengganti (Inval)',
                            'count' => $invalCount,
                            'description' => 'Tugas guru pengganti di kelas ini akan dihapus.'
                        ];
                    }
                }
                break;

            case 'mapel':
                $mapel = MataPelajaran::find($id);
                if (!$mapel) {
                    return response()->json(['error' => 'Mata Pelajaran tidak ditemukan.'], 404);
                }

                $name = $mapel->nama_mapel;

                // Jadwal
                $jadwalCount = Jadwal::where('mata_pelajaran_id', $id)->count();
                if ($jadwalCount > 0) {
                    $hasDependencies = true;
                    $affected[] = [
                        'label' => 'Jadwal Pelajaran Terkait',
                        'count' => $jadwalCount,
                        'description' => 'Semua jadwal pelajaran untuk mata pelajaran ' . $mapel->nama_mapel . ' akan terhapus.'
                    ];

                    // Jurnal & Inval
                    $jadwalIds = Jadwal::where('mata_pelajaran_id', $id)->pluck('id');
                    $jurnalCount = JurnalGuru::whereIn('jadwal_id', $jadwalIds)->count();
                    if ($jurnalCount > 0) {
                        $affected[] = [
                            'label' => 'Jurnal Mengajar Harian',
                            'count' => $jurnalCount,
                            'description' => 'Semua riwayat jurnal mengajar untuk mata pelajaran ini akan terhapus.'
                        ];
                    }

                    $invalCount = GuruPengganti::whereIn('jadwal_id', $jadwalIds)->count();
                    if ($invalCount > 0) {
                        $affected[] = [
                            'label' => 'Tugas Guru Pengganti (Inval)',
                            'count' => $invalCount,
                            'description' => 'Penugasan guru pengganti untuk mata pelajaran ini akan dibatalkan.'
                        ];
                    }
                }
                break;

            case 'jam-pelajaran':
                $jam = MasterJamPelajaran::find($id);
                if (!$jam) {
                    return response()->json(['error' => 'Jam Pelajaran tidak ditemukan.'], 404);
                }

                $name = $jam->hari . ' (Jam ke-' . ($jam->jam_ke == 0 ? 'Istirahat' : $jam->jam_ke) . ')';

                // Jam Pelajaran tidak memakai foreign key di DB, tapi merujuk jam_ke di Jadwal pada hari yang sama.
                // Cari jadwal yang terpengaruh pada hari yang sama
                $jadwalCount = Jadwal::where('hari', $jam->hari)
                    ->where(function($query) use ($jam) {
                        $query->where('jam_ke_mulai', $jam->jam_ke)
                              ->orWhere('jam_ke_selesai', $jam->jam_ke);
                    })->count();

                if ($jadwalCount > 0) {
                    $hasDependencies = true;
                    $affected[] = [
                        'label' => 'Jadwal Pelajaran Aktif',
                        'count' => $jadwalCount,
                        'description' => 'Jadwal pelajaran aktif pada jam ini akan mengalami kekosongan waktu/rusak.'
                    ];
                }
                break;

            case 'jadwal':
                $jadwal = Jadwal::with(['kelas', 'mataPelajaran'])->find($id);
                if (!$jadwal) {
                    return response()->json(['error' => 'Jadwal tidak ditemukan.'], 404);
                }

                $name = $jadwal->kelas->nama_kelas . ' - ' . $jadwal->mataPelajaran->nama_mapel . ' (' . $jadwal->hari . ')';

                // Jurnal & Inval
                $jurnalCount = JurnalGuru::where('jadwal_id', $id)->count();
                if ($jurnalCount > 0) {
                    $hasDependencies = true;
                    $affected[] = [
                        'label' => 'Jurnal Mengajar Harian',
                        'count' => $jurnalCount,
                        'description' => 'Jurnal mengajar harian guru yang dibuat pada jadwal ini akan terhapus.'
                    ];
                }

                $invalCount = GuruPengganti::where('jadwal_id', $id)->count();
                if ($invalCount > 0) {
                    $hasDependencies = true;
                    $affected[] = [
                        'label' => 'Tugas Guru Pengganti (Inval)',
                        'count' => $invalCount,
                        'description' => 'Tugas guru pengganti untuk jadwal ini akan dibatalkan.'
                    ];
                }
                break;

            case 'guru-pengganti':
                $gp = GuruPengganti::with('jadwal.kelas')->find($id);
                if (!$gp) {
                    return response()->json(['error' => 'Penugasan guru pengganti tidak ditemukan.'], 404);
                }
                $name = 'Guru Pengganti ' . $gp->jadwal->kelas->nama_kelas;
                break;
        }

        return response()->json([
            'name' => $name,
            'has_dependencies' => $hasDependencies,
            'affected' => $affected
        ]);
    }
}
