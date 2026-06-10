<?php

use App\Models\Role;
use App\Models\Dosen;
use App\Models\PassingGrade;
use App\Models\SeminarSidang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\pdfHandlingController;
use App\Http\Controllers\Mahasiswa\TranskripNilaiController;
use App\Http\Controllers\Mahasiswa\JudulController;
use App\Http\Controllers\Dosen\RepositoriController;
use App\Http\Controllers\Mahasiswa\SemhasController;
use App\Http\Controllers\Superman\ActAsController;

Route::group(['prefix' => 'superman', 'middleware' => ['role:dosen|admin']], function () {
    Route::get('/actas/index', [ActAsController::class, 'showActAsForm'])->name('superman.actasform');
    Route::get('/actas/disguise/{id}', [ActAsController::class, 'actas'])->name('superman.disguise');
    Route::get('/actas/unveiling', [ActAsController::class, 'stopActingAs'])->name('superman.unveiling');
});


