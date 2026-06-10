<?php

use App\Http\Controllers\Admin\AdminPenggunaController;
use App\Http\Controllers\Admin\AdminPengumumanController;
use App\Http\Controllers\Admin\AdminSemhasController;
use App\Http\Controllers\Admin\AdminSemproController;
use App\Http\Controllers\Admin\AdminSidangController;
use App\Http\Controllers\Dosen\DosenBimbinganController;
use App\Http\Controllers\Dosen\DosenMonitorController;
use App\Http\Controllers\Dosen\DosenPengumumanController;
use App\Http\Controllers\Dosen\DosenSemhasController;
use App\Http\Controllers\Dosen\DosenSemproController;
use App\Http\Controllers\Dosen\DosenSidangController;
use App\Http\Controllers\Dosen\JadwalBimbinganController;
use App\Http\Controllers\Dosen\RepositoriController;
use App\Http\Controllers\Dosen\ValidasiNotulenController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Kaprodi\KaprodiParameterController;
use App\Http\Controllers\Kaprodi\KaprodiPenjadwalanController;
use App\Http\Controllers\Kaprodi\MonitorController;
use App\Http\Controllers\Kaprodi\MonitoringController;
use App\Http\Controllers\Mahasiswa\BimbinganController;
use App\Http\Controllers\Mahasiswa\JadwalNotulenController;
use App\Http\Controllers\Mahasiswa\JudulController;
use App\Http\Controllers\Mahasiswa\PengumumanController;
use App\Http\Controllers\Mahasiswa\SemhasController;
use App\Http\Controllers\Mahasiswa\SemproController;
use App\Http\Controllers\Mahasiswa\SidangController;
use App\Http\Controllers\Mahasiswa\TranskripNilaiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use App\Models\Dosen;
use App\Models\PassingGrade;
use App\Models\SeminarSidang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// Route::get('/storage-link', function () {
//     $targerFolder = base_path().'/storage/app/public';
//     $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';
//     symlink($targerFolder,$linkFolder);
// });

// Route::get('/tes', function () {
//     $seminarSidang = SeminarSidang::where('slug', '60ac6001-cc0c-4320-878d-c0d36f84b504')->first();
//     $passingGrade = PassingGrade::where('tahapan_ta', 'Seminar Proposal')->first()->nilai;
//     $kaprodiId = DB::table('role_user')->where('role_id', 4)->pluck('user_id');
//     $kaprodi = Dosen::where('user_id', $kaprodiId)->first();
//     return view('welcome', compact('seminarSidang', 'passingGrade', 'kaprodi'));
// });
Route::get('/', function () {
    if (Route::has('login')) {
        return redirect('/dashboard');
    } else {
        return view('welcome');
    }
})->middleware(['auth', 'verified']);

Route::get('/dashboard', [HomeController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/judul', [JudulController::class, 'viewIndex'])->name('view.judul');
    Route::get('/judul/detail/{judul}', [JudulController::class, 'viewDetail'])->name('view.judul.detail');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route Mahasiswa
Route::group(['prefix' => 'mahasiswa', 'middleware' => ['role:mahasiswa', 'web']], function () {
    Route::get('/dashboard', [HomeController::class, 'mahasiswa'])->name('dashboard.mahasiswa');
    // Marini
    // jadwal notulen
    Route::get('/jadwal-notulen', [JadwalNotulenController::class, 'index'])->name('jadwal-notulen');
    Route::get('/jadwal-notulen/{slug}/edit', [JadwalNotulenController::class, 'edit'])->name('jadwal-notulen.edit');
    Route::put('/jadwal-notulen/{slug}', [JadwalNotulenController::class, 'update'])->name('jadwal-notulen.update');
    // marini end
    // Sempro
    Route::get('/sempro', [SemproController::class, 'viewIndex'])->name('view.sempro');
    Route::post('/sempro/daftar', [SemproController::class, 'storePendaftaran'])->name('store.sempro');
    Route::get('/sempro/detail/{slug}', [SemproController::class, 'viewDetail'])->name('view.sempro.detail');
    Route::get('/sempro/ubah/{slug}', [SemproController::class, 'viewUbah'])->name('view.sempro.ubah');
    Route::post('/sempro/ubah/{slug}/store', [SemproController::class, 'storeUbah'])->name('store.sempro.ubah');
    Route::delete('/sempro/hapus/{slug}', [SemproController::class, 'delete'])->name('view.sempro.delete');
    Route::get('/sempro/hasil/{slug}', [SemproController::class, 'viewHasil'])->name('view.sempro.hasil');
    Route::post('/sempro/video/{slug}', [SemproController::class, 'uploadVideo'])->name('store.video.sempro');
    Route::post('/sempro/revisi/{slug}', [SemproController::class, 'uploadRevisi'])->name('store.revisi.sempro');
    Route::post('/sempro/revisi/ubah/{slug}', [SemproController::class, 'ubahRevisi'])->name('ubah.revisi.sempro');
    // Semhas
    Route::get('/semhas', [SemhasController::class, 'viewIndex'])->name('view.semhas');
    Route::post('/semhas/daftar', [SemhasController::class, 'storePendaftaran'])->name('store.semhas');
    Route::get('/semhas/hasil/{slug}', [SemhasController::class, 'viewHasil'])->name('view.semhas.hasil');
    Route::get('/semhas/detail/{slug}', [SemhasController::class, 'viewDetail'])->name('view.semhas.detail');
    Route::get('/semhas/ubah/{slug}', [SemhasController::class, 'viewubah'])->name('view.semhas.ubah');
    Route::post('/semhas/ubah/{slug}/store', [SemhasController::class, 'storeUbah'])->name('store.semhas.ubah');
    Route::post('/semhas/revisi/{slug}', [SemhasController::class, 'uploadRevisi'])->name('store.revisi.semhas');
    Route::post('/semhas/revisi/ubah/{slug}', [SemhasController::class, 'ubahRevisi'])->name('ubah.revisi.semhas');
    Route::delete('/semhas/hapus/{slug}', [SemhasController::class, 'delete'])->name('semhas.delete');
    // Sidang
    Route::get('/sidang', [SidangController::class, 'viewIndex'])->name('view.sidang');
    Route::post('/sidang/daftar', [SidangController::class, 'storePendaftaran'])->name('store.sidang');
    Route::get('/sidang/hasil/{slug}', [SidangController::class, 'viewHasil'])->name('view.sidang.hasil');
    Route::get('/sidang/detail/{slug}', [SidangController::class, 'viewDetail'])->name('view.sidang.hasil');
    Route::get('/sidang/ubah/{slug}', [SidangController::class, 'viewubah'])->name('view.sidang.ubah');
    Route::post('/sidang/ubah/{slug}/store', [SidangController::class, 'storeUbah'])->name('store.sidang.ubah');
    Route::post('/sidang/revisi/{slug}', [SidangController::class, 'uploadFileTA'])->name('store.revisi.sidang');
    Route::post('/sidang/revisi/ubah/{slug}', [SidangController::class, 'ubahFileTA'])->name('ubah.revisi.sidang');
    Route::delete('/sidang/hapus/{slug}', [SidangController::class, 'delete'])->name('sidang.delete');
    // Transkrip
    Route::post('/sidang/store-transkrip', [TranskripNilaiController::class, 'storeTranskrip'])->name('store.transkrip');
    Route::post('/sidang/store-makul', [TranskripNilaiController::class, 'storeMakul'])->name('store.makul');
    Route::post('/sidang/konfirmasi-transkrip', [TranskripNilaiController::class, 'konfirmasiTranskrip'])->name('konfirmasi.transkrip');
    Route::post('/sidang/edit-transkrip/{uuid}', [TranskripNilaiController::class, 'editTranskrip'])->name('edit.transkrip');
    Route::delete('/sidang/hapus-transkrip/{uuid}', [TranskripNilaiController::class, 'hapusTranskrip'])->name('hapus.transkrip');
    Route::get('/sidang/cari-makul', [TranskripNilaiController::class, 'cariMakul'])->name('cari.makul');
    // Pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'viewPengumuman'])->name('pengumuman');
    // Kontrol Bimbingan
    Route::get('/jadwal-bimbingan-dosen', [BimbinganController::class, 'getJadwalBimbinganDosen'])
        ->name('mahasiswa.jadwal-dosen');
    Route::get('/bimbingan', [BimbinganController::class, 'viewBimbingan'])->name('view.bimbingan');
    Route::post('/bimbingan/tambah', [BimbinganController::class, 'storeBimbingan'])->name('store.bimbingan');
    Route::get('/bimbingan/edit/{slug}', [BimbinganController::class, 'viewEdit'])->name('view.bimbingan.edit');
    Route::post('/bimbingan/edit/{slug}/store', [BimbinganController::class, 'storeEdit'])->name('view.bimbingan.edit.store');
    Route::delete('/bimbingan/{slug}/delete', [BimbinganController::class, 'delete'])->name('delete.bimbingan');
});

// Route Admin
Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
    Route::get('/dashboard', [HomeController::class, 'admin'])->name('dashboard.admin');
    // Pengumuman
    Route::get('/pengumuman', [AdminPengumumanController::class, 'viewPengumuman'])->name('admin.pengumuman');
    Route::get('/pengumuman/edit/{id}', [AdminPengumumanController::class, 'viewEdit'])->name('edit.pengumuman');
    Route::post('/pengumuman/tambah', [AdminPengumumanController::class, 'storePengumuman'])->name('store.pengumuman');
    Route::post('/pengumuman/edit/{id}/store', [AdminPengumumanController::class, 'editPengumuman'])->name('edit.pengumuman.store');
    Route::delete('/pengumuman/hapus/{id}', [AdminPengumumanController::class, 'deletePengumuman'])->name('delete.pengumuman');
    // Sempro
    Route::get('/sempro', [AdminSemproController::class, 'viewPendaftaran'])->name('admin.sempro');
    Route::get('/sempro/detail/{slug}', [AdminSemproController::class, 'viewDetail'])->name('admin.sempro.detail');
    Route::post('/sempro/{id}/batalkan', [AdminSemproController::class, 'batalkan'])
        ->name('sempro.batalkan');
    Route::post('/sempro/edit/{slug}', [AdminSemproController::class, 'editMahasiswa'])->name('edit.sempro');
    Route::post('/sempro/terima/{slug}', [AdminSemproController::class, 'terima'])->name('terima.sempro');
    Route::post('/sempro/tolak/{slug}', [AdminSemproController::class, 'tolak'])->name('tolak.sempro');
    Route::post('/sempro/batal/{slug}', [AdminSemproController::class, 'batal'])->name('batal.sempro');
    Route::delete('/sempro/hapus/{slug}', [AdminSemproController::class, 'delete'])->name('admin.sempro.delete');
    Route::get('/sempro/riwayat', [AdminSemproController::class, 'viewRiwayat'])->name('admin.sempro.riwayat');
    Route::get('/sempro/berita-acara', [AdminSemproController::class, 'viewBeritaAcara'])->name('admin.sempro.berita-acara');
    Route::get('/sempro/berita-acara/{slug}', [AdminSemproController::class, 'cetakBeritaAcara'])->name('admin.sempro.berita-acara.cetak');
    Route::get('/sempro/rekapitulasi', [AdminSemproController::class, 'viewRekap'])->name('admin.sempro.rekap');
    Route::post('/sempro/rekapitulasi/cetak', [AdminSemproController::class, 'cetakRekap'])->name('admin.sempro.rekap.cetak');
    // Semhas
    Route::get('/semhas', [AdminSemhasController::class, 'viewPendaftaran'])->name('admin.semhas');
    Route::get('/semhas/detail/{slug}', [AdminSemhasController::class, 'viewDetail'])->name('admin.semhas.detail');
    Route::get('/semhas/cek-kuota', [AdminSemhasController::class, 'cekKuota']);
    Route::post('/semhas/terima/{slug}', [AdminSemhasController::class, 'terima'])->name('terima.semhas');
    Route::post('/semhas/tolak/{slug}', [AdminSemhasController::class, 'tolak'])->name('tolak.semhas');
    Route::post('/semhas/batal/{slug}', [AdminSemhasController::class, 'batal'])->name('batal.semhas');
    Route::get('/semhas/riwayat', [AdminSemhasController::class, 'viewRiwayat'])->name('admin.semhas.riwayat');
    Route::get('/semhas/berita-acara', [AdminSemhasController::class, 'viewBeritaAcara'])->name('admin.semhas.berita-acara');
    Route::get('/semhas/berita-acara/{slug}', [AdminSemhasController::class, 'cetakBeritaAcara'])->name('admin.semhas.berita-acara.cetak');
    Route::post('/semhas/update-ta/{slug}', [AdminSemhasController::class, 'updateTA'])->name('admin.semhas.update-ta');
    // Sidang
    Route::get('/sidang', [AdminSidangController::class, 'viewPendaftaran'])->name('admin.sidang');
    Route::get('/sidang/detail/{slug}', [AdminSidangController::class, 'viewDetail'])->name('admin.sidang.detail');
    Route::post('/sidang/update-no-surat-ba/{slug}', [AdminSidangController::class, 'updateNoSuratBA'])->name('admin.sidang.update-no-surat-ba');
    Route::post('/sidang/terima/{slug}', [AdminSidangController::class, 'terima'])->name('terima.sidang');
    Route::post('/sidang/tolak/{slug}', [AdminSidangController::class, 'tolak'])->name('tolak.sidang');
    Route::get('/sidang/riwayat', [AdminSidangController::class, 'viewRiwayat'])->name('admin.sidang.riwayat');
    Route::post('/sidang/batal/{slug}', [AdminSidangController::class, 'batal'])->name('batal.sidang');
    Route::get('/sidang/berita-acara', [AdminSidangController::class, 'viewBeritaAcara'])->name('admin.sidang.berita-acara');
    Route::get('/sidang/berita-acara/{slug}', [AdminSidangController::class, 'cetakBeritaAcara'])->name('admin.sidang.berita-acara.cetak');
    // Transkrip
    Route::get('/sidang/transkrip', [AdminSidangController::class, 'viewTranskrip'])->name('admin.transkrip');
    Route::post('/sidang/transkrip/terima/{id}', [AdminSidangController::class, 'terimaTranskrip'])->name('admin.transkrip.terima');
    Route::post('/sidang/transkrip/tolak/{id}', [AdminSidangController::class, 'tolakTranskrip'])->name('admin.transkrip.tolak');
    Route::get('/sidang/transkrip/detail/{id}', [AdminSidangController::class, 'viewDetailTranskrip'])->name('admin.transkrip.detail');
    Route::post('/sidang/transkrip/batal/{id}', [AdminSidangController::class, 'batalkanTranskrip'])->name('admin.transkrip.batal');
    Route::get('/sidang/transkrip/riwayat', [AdminSidangController::class, 'viewRiwayatTranskrip'])->name('admin.transkrip.riwayat');
    Route::post('/sidang/transkrip/tambah-makul', [AdminSidangController::class, 'storeMakul'])->name('admin.transkrip.store.makul');
    Route::post('/sidang/transkrip/edit-makul/{id}', [AdminSidangController::class, 'editMakul'])->name('admin.transkrip.edit.makul');
    Route::delete('/sidang/transkrip/hapus-makul/{id}', [AdminSidangController::class, 'hapusMakul'])->name('admin.transkrip.hapus.makul');
    // Kelola Pengguna
    Route::get('/kelola-mahasiswa', [AdminPenggunaController::class, 'viewKelolaMahasiswa'])->name('admin.kelola.mahasiswa');
    Route::post('/kelola-mahasiswa/terima/{id}', [AdminPenggunaController::class, 'terimaPengguna'])->name('admin.kelola.mahasiswa.terima');
    Route::post('/kelola-mahasiswa/tolak/{id}', [AdminPenggunaController::class, 'tolakPengguna'])->name('admin.kelola.mahasiswa.tolak');
    Route::post('/kelola-mahasiswa/store', [AdminPenggunaController::class, 'storeMahasiswa'])->name('admin.kelola.mahasiswa.store');
    Route::get('/kelola-mahasiswa/detail/{id}', [AdminPenggunaController::class, 'viewDetailMahasiswa']);
    Route::get('/kelola-mahasiswa/ubah/{id}', [AdminPenggunaController::class, 'viewEditMahasiswa']);
    Route::post('/kelola-mahasiswa/ubah/{id}/store', [AdminPenggunaController::class, 'storeEditMahasiswa'])->name('admin.kelola.mahasiswa.edit.store');
    Route::delete('/kelola-mahasiswa/delete/{id}', [AdminPenggunaController::class, 'deleteMahasiswa'])->name('admin.kelola.mahasiswa.delete');
    Route::get('/kelola-dosen', [AdminPenggunaController::class, 'viewKelolaDosen'])->name('admin.kelola.dosen');
    Route::post('/kelola-dosen/store', [AdminPenggunaController::class, 'storeDosen'])->name('admin.kelola.dosen.store');
    Route::get('/kelola-dosen/detail/{id}', [AdminPenggunaController::class, 'viewDetailDosen']);
    Route::get('/kelola-dosen/ubah/{id}', [AdminPenggunaController::class, 'viewEditDosen']);
    Route::post('/kelola-dosen/ubah/{id}/store', [AdminPenggunaController::class, 'storeEditDosen'])->name('admin.kelola.dosen.edit.store');
    Route::delete('/kelola-dosen/delete/{id}', [AdminPenggunaController::class, 'deleteDosen'])->name('admin.kelola.dosen.delete');
    Route::post('/kelola-dosen/ganti-kaprodi', [AdminPenggunaController::class, 'gantiKaprodi'])->name('admin.kelola.dosen.ganti.kaprodi');
    Route::post('/kelola-dosen/ganti-dekan', [AdminPenggunaController::class, 'gantiDekan'])->name('admin.kelola.dosen.ganti.dekan');
});

// Route Dosen
Route::group(['prefix' => 'dosen', 'middleware' => ['role:dosen']], function () {
    Route::get('/dashboard', [HomeController::class, 'dosen'])->name('dashboard.dosen');
    Route::post('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'markAsRead'])
        ->name('notifikasi.dibaca');

    Route::prefix('dosen')->middleware(['auth'])->group(function () {
        Route::post('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'dibacaDosen'])
            ->name('dosen.notifikasi.dibaca');
    });
    // start marini fitur dosen
    Route::get('/monitor/bimbingan-aktif', [DosenMonitorController::class, 'bimbinganAktif'])->name('dosen.aktif.bimbingan');
    Route::get('/monitor/bimbingan-aktif/{mahasiswa}', [DosenMonitorController::class, 'grafikBimbingan'])
        ->name('monitor.grafikBimbingan');
    Route::get('/monitor/bimbingan-lulus', [DosenMonitorController::class, 'bimbinganLulus'])->name('dosen.lulus.bimbingan');
    Route::get('/monitor/diuji-aktif', [DosenMonitorController::class, 'diujiAktif'])->name('dosen.aktif.diuji');
    Route::get('/monitor/diuji-lulus', [DosenMonitorController::class, 'diujiLulus'])->name('dosen.lulus.diuji');
    // end marini dashboard dosen

    // marini - validasi notulen mahasiswa
    Route::get('/validasi-notulen', [ValidasiNotulenController::class, 'index'])
        ->name('dosen.validasi-notulen');
    Route::get('/validasi-notulen/detail/{slug}', [ValidasiNotulenController::class, 'detail'])
        ->name('dosen.validasi-notulen.detail');
    Route::put('/validasi-notulen/{slug}', [ValidasiNotulenController::class, 'update'])
        ->name('dosen.validasi-notulen.update');
    // end marini - validasi notulen

    // Pengumuman
    Route::get('/pengumuman', [DosenPengumumanController::class, 'viewPengumuman'])->name('dosen.pengumuman');
    // Sempro
    Route::get('/penilaian-sempro', [DosenSemproController::class, 'viewPenilaian'])->name('dosen.sempro.penilaian');

    Route::get('/penilaian-sempro/detail/{slug}', [DosenSemproController::class, 'viewPenilaianDetail'])->name('dosen.sempro.penilaian.detail');
    Route::post('/dosen/seminar/{id}/batalkan', [DosenSemproController::class, 'batalkan'])
        ->name('seminar.batalkan');
    Route::get('/sempro/dibatalkan', [DosenSemproController::class, 'dibatalkan'])
        ->name('dosen.sempro.dibatalkan');
    Route::get('/penilaian-sempro/nilai/{slug}', [DosenSemproController::class, 'viewFormPenilaian']);
    Route::post('/penilaian-sempro/nilai/{slug}/store', [DosenSemproController::class, 'storePenilaianSempro'])->name('dosen.sempro.penilaian.store');
    Route::get('/penilaian-sempro/riwayat', [DosenSemproController::class, 'viewRiwayat'])->name('dosen.sempro.riwayat');
    Route::get('/penilaian-sempro/edit/{slug}', [DosenSemproController::class, 'viewEditNilai']);
    Route::post('/penilaian-sempro/edit/{slug}/store', [DosenSemproController::class, 'storeEditNilai'])->name('dosen.sempro.penilaian.edit.store');
    Route::post('/penilaian-sempro/batal/{slug}', [DosenSemproController::class, 'batalkanPenilaian'])->name('dosen.sempro.penilaian.batal');
    Route::get('/rekapitulasi-sempro', [DosenSemproController::class, 'viewRekap'])->name('dosen.sempro.rekap');
    // Semhas
    Route::get('/penilaian-semhas', [DosenSemhasController::class, 'viewPenilaian'])->name('dosen.semhas.penilaian');
    Route::get('/penilaian-semhas/detail/{slug}', [DosenSemhasController::class, 'viewPenilaianDetail'])->name('dosen.semhas.penilaian.detail');
    Route::get('/penilaian-semhas/nilai/{slug}', [DosenSemhasController::class, 'viewFormPenilaian']);
    Route::post('/penilaian-semhas/nilai/{slug}/store', [DosenSemhasController::class, 'storePenilaianSemhas'])->name('dosen.semhas.penilaian.store');
    Route::get('/penilaian-semhas/riwayat', [DosenSemhasController::class, 'viewRiwayat'])->name('dosen.semhas.riwayat');
    Route::get('/penilaian-semhas/edit/{slug}', [DosenSemhasController::class, 'viewEditNilai']);
    Route::post('/penilaian-semhas/edit/{slug}/store', [DosenSemhasController::class, 'storeEditNilai'])->name('dosen.semhas.penilaian.edit.store');
    Route::post('/penilaian-semhas/batal/{slug}', [DosenSemhasController::class, 'batalkanPenilaian'])->name('dosen.semhas.penilaian.batal');
    // Sidang
    Route::get('/penilaian-sidang', [DosenSidangController::class, 'viewPenilaian'])->name('dosen.sidang.penilaian');
    Route::get('/penilaian-sidang/detail/{slug}', [DosenSidangController::class, 'viewPenilaianDetail'])->name('dosen.sidang.penilaian.detail');
    Route::get('/penilaian-sidang/nilai/{slug}', [DosenSidangController::class, 'viewFormPenilaian']);
    Route::get('/penilaian-sidang/publish/{slug}', [DosenSidangController::class, 'publishNilai']);
    Route::get('/penilaian-sidang/unpublish/{slug}/false', [DosenSidangController::class, 'unpublishNilai']);
    Route::post('/penilaian-sidang/nilai/{slug}/store', [DosenSidangController::class, 'storePenilaianSidang'])->name('dosen.sidang.penilaian.store');
    Route::get('/penilaian-sidang/riwayat', [DosenSidangController::class, 'viewRiwayat'])->name('dosen.sidang.riwayat');
    Route::get('/penilaian-sidang/edit/{slug}', [DosenSidangController::class, 'viewEditNilai']);
    Route::post('/penilaian-sidang/edit/{slug}/store', [DosenSidangController::class, 'storeEditNilai'])->name('dosen.sidang.penilaian.edit.store');
    Route::post('/penilaian-sidang/batal/{slug}', [DosenSidangController::class, 'batalkanPenilaian'])->name('dosen.sidang.penilaian.batal');
    // Kontrol Bimbingan

    // marini
    Route::resource('jadwal-bimbingan', JadwalBimbinganController::class);
    // marini

    // marini - notif dosen
    Route::post('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'markAsRead'])
        ->name('notifikasi.dibaca');
    // end

    Route::get('/kelola-bimbingan', [DosenBimbinganController::class, 'viewBimbingan'])->name('dosen.bimbingan');
    Route::post('/kelola-bimbingan/verifikasi/{id}', [DosenBimbinganController::class, 'verifikasi'])->name('dosen.bimbingan.verifikasi');
    Route::post('/kelola-bimbingan/terima/{id}', [DosenBimbinganController::class, 'terima'])->name('dosen.bimbingan.terima');
    Route::post('/kelola-bimbingan/tolak/{id}', [DosenBimbinganController::class, 'tolak'])->name('dosen.bimbingan.tolak');
    Route::get('/daftar-bimbingan', [DosenBimbinganController::class, 'viewDaftarBimbingan'])->name('dosen.daftar.bimbingan');
    Route::get('/daftar-bimbingan/{slug}', [DosenBimbinganController::class, 'detail'])->name('dosen.daftar.bimbingan.detail');
    Route::get('/daftar-bimbingan/informasi-mahasiswa/{slug}', [DosenBimbinganController::class, 'informasiMahasiswa'])->name('dosen.daftar.bimbingan.informasi');
    Route::post('/daftar-bimbingan/{id}/store', [DosenBimbinganController::class, 'storeBimbingan'])->name('dosen.daftar.bimbingan.store');
    Route::get('/daftar-bimbingan/edit/{slug}', [DosenBimbinganController::class, 'viewEdit'])->name('dosen.daftar.bimbingan.edit');
    Route::post('/daftar-bimbingan/edit/{slug}/store', [DosenBimbinganController::class, 'storeEdit'])->name('dosen.daftar.bimbingan.edit.store');
    Route::delete('/daftar-bimbingan/delete/{slug}', [DosenBimbinganController::class, 'delete'])->name('dosen.daftar.bimbingan.delete');
    // Repositori
    Route::get('/repositori', [RepositoriController::class, 'viewIndex'])->name('dosen.repositori');
    Route::get('/repositori/detail/{slug}', [RepositoriController::class, 'viewDetail'])->name('dosen.repositori.detail');
    Route::get('/repositori/cetak/{slug}', [RepositoriController::class, 'cetakBeritaAcara'])->name('dosen.cetak');
});

// Route Kaprodi
Route::group(['prefix' => 'kaprodi', 'middleware' => ['role:kaprodi']], function () {
    Route::get('/dashboard', [HomeController::class, 'kaprodi'])->name('dashboard.kaprodi');
    Route::post('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'markAsRead'])
        ->name('notifikasi.dibaca');
    Route::get('/monitoring', [MonitoringController::class, 'viewMonitoring'])->name('monitoring');
    Route::get('/monitoring/detail-mahasiswa/{slug}', [MonitoringController::class, 'viewDetail'])->name('monitoring.detail.mahasiswa');

    // Marini
    // dashboard kaprodi
    Route::get('/monitor/lama-pengerjaan-ta', [MonitorController::class, 'pengerjaanTa'])->name('monitor.pengerjaanTa');
    Route::get('/monitor/mhs-aktif-ta', [MonitorController::class, 'mhsAktifTa'])->name('monitor.mhsAktifTa');
    Route::get('/monitor/ktw', [MonitorController::class, 'mhsKtw'])->name('monitor.mhsKtw');
    Route::get('/monitor/lms', [MonitorController::class, 'mhsLms'])->name('monitor.mhsLms');
    // Menu khusus monitoring (repositori jurnal dan bimbingan)
    Route::get('/monitor/mon-bimbingan', [MonitorController::class, 'monBimbinganMhs'])->name('monitor.monBimbinganMhs');
    Route::get('/monitor/mon-bimbingan-mhs/{dosen}', [MonitorController::class, 'monBimbinganMhsDetail'])
        ->name('monitor.monBimbinganMhs.detail');
    Route::get('/monitor/rekam-bimbingan/{dosen}/{mahasiswa}', [MonitorController::class, 'rekamBimbinganMhs'])
        ->name('monitor.rekamBimbinganMhs');
    Route::get('/monitor/repo', [MonitorController::class, 'repoMhs'])->name('monitor.repoMhs');

    // Penjadwalan dan penentuan penguji
    Route::resource('/penjadwalan', KaprodiPenjadwalanController::class);

    // Marini  end
    // Parameter Penilaian
    Route::get('/parameter-sempro', [KaprodiParameterController::class, 'viewParameterSempro'])->name('admin.parameter.sempro');
    Route::post('/parameter-sempro/store', [KaprodiParameterController::class, 'storeParameterSempro'])->name('store.parameter.sempro');
    Route::get('/parameter-sempro/edit/{id}', [KaprodiParameterController::class, 'viewEditParameterSempro']);
    Route::post('/parameter-sempro/passing-grade/edit', [KaprodiParameterController::class, 'editPassingGradeSempro'])->name('edit.passing.grade.sempro');
    Route::post('/parameter-sempro/edit/{id}/store', [KaprodiParameterController::class, 'EditParameterSempro'])->name('edit.parameter.sempro');
    Route::delete('/parameter/delete/{id}', [KaprodiParameterController::class, 'delete'])->name('delete.parameter');
    Route::get('/parameter-semhas', [KaprodiParameterController::class, 'viewParameterSemhas'])->name('admin.parameter.semhas');
    Route::post('/parameter-semhas/store', [KaprodiParameterController::class, 'storeParameterSemhas'])->name('store.parameter.semhas');
    Route::get('/parameter-semhas/edit/{id}', [KaprodiParameterController::class, 'viewEditParameterSemhas']);
    Route::post('/parameter-semhas/passing-grade/edit', [KaprodiParameterController::class, 'editPassingGradeSemhas'])->name('edit.passing.grade.semhas');
    Route::post('/parameter-semhas/edit/{id}/store', [KaprodiParameterController::class, 'editParameterSemhas'])->name('edit.parameter.semhas');
    Route::get('/parameter-sidang', [KaprodiParameterController::class, 'viewParameterSidang'])->name('admin.parameter.sidang');
    Route::post('/parameter-sidang/skripsi/store', [KaprodiParameterController::class, 'storeParameterSkripsi'])->name('store.parameter.sidang');
    Route::post('/parameter-sidang/artikel/store', [KaprodiParameterController::class, 'storeParameterArtikel'])->name('store.parameter.artikel');
    Route::post('/parameter-sidang/presentasi/store', [KaprodiParameterController::class, 'storeParameterPresentasi'])->name('store.parameter.presentasi');
    Route::get('/parameter-sidang/edit/{id}', [KaprodiParameterController::class, 'viewEditParameterSidang']);
    Route::post('/parameter-sidang/edit/{id}/store', [KaprodiParameterController::class, 'editParameterSidang'])->name('edit.parameter.sidang');
    Route::post('/parameter-sidang/passing-grade/edit', [KaprodiParameterController::class, 'editPassingGradeSidang'])->name('edit.passing.grade.sidang');
});

require __DIR__.'/auth.php';

require __DIR__.'/superman.php';
