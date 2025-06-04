<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UkmOrmawaController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\RegistrationOpeningController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UkmOrmawaRegistrationController;
use App\Http\Controllers\Pengurus\PengurusDashboardController;
use App\Http\Controllers\Pengurus\ManagedUkmOrmawaController; // Tambahkan ini di atas
use App\Http\Controllers\Pengurus\MemberManagementController; // <--- TAMBAHKAN INI



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::middleware(['auth', 'role:admin,mahasiswa'])->group(function () {
    Route::post('/articles/{article}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
});

/**
*    - Middleware: `auth` dan `role:admin`
*    - Prefix URL: `/admin`
*    - Rute-rute terkait CRUD artikel:
*        - GET `/articles` → `ArticleController@index` (route name: `admin.index`)
*        - GET `/articles/create` → `ArticleController@create` (route name: `admin.create`)
*        - POST `/articles` → `ArticleController@store` (route name: `admin.store`)
*        - GET `/articles/{article}/edit` → `ArticleController@edit` (route name: `admin.edit`)
*        - PUT `/articles/{article}` → `ArticleController@update` (route name: `admin.update`)
*        - DELETE `/articles/{article}` → `ArticleController@destroy` (route name: `admin.destroy`)
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index'])->name('admin.index');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('admin.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('admin.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('admin.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('admin.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('admin.destroy');
});

Route::middleware(['auth'])->group(function () {
    // ... rute lain yang sudah ada ...

    Route::get('/ukm-ormawa', [UkmOrmawaController::class, 'index'])->name('ukm-ormawa.index');
    Route::get('/ukm-ormawa/{slug}', [UkmOrmawaController::class, 'show'])->name('ukm-ormawa.show'); // Rute untuk halaman detail
    Route::get('/kegiatan-saya', [UserActivityController::class, 'index'])->name('my-activities.index');
    Route::get('/lowongan-pendaftaran', [RegistrationOpeningController::class, 'index'])->name('registration-openings.index');
    Route::get('/pengaturan', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/pengaturan/profil', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::post('/pengaturan/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::delete('/pengaturan/akun', [SettingsController::class, 'deleteAccount'])->name('settings.account.delete');
    Route::get('/apply/{ukm_ormawa_slug}', [UkmOrmawaRegistrationController::class, 'showApplicationForm'])->name('ukm-ormawa.apply.form');
    Route::post('/apply/{ukm_ormawa_slug}', [UkmOrmawaRegistrationController::class, 'submitApplication'])->name('ukm-ormawa.apply.submit');

});

Route::middleware(['auth', 'role:pengurus'])->prefix('pengurus')->name('pengurus.')->group(function () {
    Route::get('/dashboard', [PengurusDashboardController::class, 'index'])->name('dashboard');
    Route::get('/ukm-ormawa/kelola', [ManagedUkmOrmawaController::class, 'edit'])->name('ukm-ormawa.edit');
    Route::put('/ukm-ormawa/kelola', [ManagedUkmOrmawaController::class, 'update'])->name('ukm-ormawa.update');

    // --- RUTE BARU UNTUK MANAJEMEN ANGGOTA ---
    Route::get('/members', [MemberManagementController::class, 'index'])->name('members.index');
    Route::get('/members/{application}/show', [MemberManagementController::class, 'showApplication'])->name('members.show');
    Route::patch('/members/{application}/status', [MemberManagementController::class, 'updateStatus'])->name('members.updateStatus');
    // -------------------------------------------
    Route::resource('activities', App\Http\Controllers\Pengurus\ActivityManagementController::class)->except(['show']); // 'show' publik mungkin berbeda
// Jika Anda ingin method 'show' khusus untuk pengurus:
// Route::get('activities/{activity}', [App\Http\Controllers\Pengurus\ActivityManagementController::class, 'show'])->name('activities.show');
});

