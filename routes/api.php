<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — ContentImpact CMS
|--------------------------------------------------------------------------
| Endpoint ini bersifat publik (tidak memerlukan autentikasi).
| Dapat dikonsumsi oleh aplikasi Mobile (Android/iOS) atau klien pihak ketiga.
|
| Base URL: /api/v1/...
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ─── Artikel ─────────────────────────────────────────────────────────────
    // GET /api/v1/articles          → Daftar semua artikel (published), dengan search & filter kategori
    // GET /api/v1/articles/{slug}   → Detail artikel berdasarkan slug
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{slug}', [ArticleController::class, 'show']);

    // ─── Kategori ────────────────────────────────────────────────────────────
    // GET /api/v1/categories        → Daftar semua kategori beserta jumlah artikel
    Route::get('/categories', [ArticleController::class, 'categories']);

});
