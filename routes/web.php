<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ManageArticleController;
use App\Http\Controllers\Dashboard\ManageCategoryController;
use App\Http\Controllers\Dashboard\ManageUserController;
use App\Http\Controllers\Dashboard\ManageCommentController;
use Illuminate\Support\Facades\Route;

// Public Portal Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('/articles/{slug}/comment', [ArticleController::class, 'storeComment'])->name('articles.comment.store');
Route::post('/articles/{slug}/like', [\App\Http\Controllers\LikeController::class, 'toggle'])->name('articles.like');

// CMS Dashboard Routes (Protected by Auth)
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    
    // Core Dashboard Index
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Article Workflow Routes (Accessible by all roles, authorized inside controller via Policies)
    Route::get('/articles', [ManageArticleController::class, 'index'])->name('dashboard.articles.index');
    Route::get('/articles/create', [ManageArticleController::class, 'create'])->name('dashboard.articles.create');
    Route::post('/articles', [ManageArticleController::class, 'store'])->name('dashboard.articles.store');
    Route::get('/articles/{id}', [ManageArticleController::class, 'show'])->name('dashboard.articles.show');
    Route::get('/articles/{id}/edit', [ManageArticleController::class, 'edit'])->name('dashboard.articles.edit');
    Route::put('/articles/{id}', [ManageArticleController::class, 'update'])->name('dashboard.articles.update');
    Route::delete('/articles/{id}', [ManageArticleController::class, 'destroy'])->name('dashboard.articles.destroy');
    Route::post('/articles/{id}/submit', [ManageArticleController::class, 'submit'])->name('dashboard.articles.submit');
    Route::post('/articles/{id}/approve', [ManageArticleController::class, 'approve'])->name('dashboard.articles.approve');
    Route::post('/articles/{id}/revision', [ManageArticleController::class, 'requestRevision'])->name('dashboard.articles.revision');

    // Category Management (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/categories', [ManageCategoryController::class, 'index'])->name('dashboard.categories.index');
        Route::post('/categories', [ManageCategoryController::class, 'store'])->name('dashboard.categories.store');
        Route::put('/categories/{id}', [ManageCategoryController::class, 'update'])->name('dashboard.categories.update');
        Route::delete('/categories/{id}', [ManageCategoryController::class, 'destroy'])->name('dashboard.categories.destroy');
    });

    // User Management (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', [ManageUserController::class, 'index'])->name('dashboard.users.index');
        Route::post('/users', [ManageUserController::class, 'store'])->name('dashboard.users.store');
        Route::put('/users/{id}', [ManageUserController::class, 'update'])->name('dashboard.users.update');
        Route::delete('/users/{id}', [ManageUserController::class, 'destroy'])->name('dashboard.users.destroy');
    });

    // Comment Moderation (Admin & Editor Only)
    Route::middleware(['role:admin,editor'])->group(function () {
        Route::get('/comments', [ManageCommentController::class, 'index'])->name('dashboard.comments.index');
        Route::delete('/comments/{id}', [ManageCommentController::class, 'destroy'])->name('dashboard.comments.destroy');
    });

    // Robust Logout (GET method)
    Route::get('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout.get');
});

require __DIR__.'/auth.php';
