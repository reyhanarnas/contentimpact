<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    protected $articleService;
    protected $categoryService;

    public function __construct(ArticleService $articleService, CategoryService $categoryService)
    {
        $this->articleService = $articleService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        // Cache homepage data selama 5 menit untuk kurangi beban DB
        $hero = Cache::remember('home.hero', 300, function () {
            return $this->articleService->getHeroArticle();
        });

        $latest = Cache::remember('home.latest', 300, function () {
            return $this->articleService->getLatestArticles(7);
        });

        // Exclude hero dari latest jika ada
        if ($hero && $latest->contains('id', $hero->id)) {
            $latest = $latest->filter(fn($art) => $art->id !== $hero->id)->take(6);
        } else {
            $latest = $latest->take(6);
        }

        $popular = Cache::remember('home.popular', 300, function () {
            return $this->articleService->getPopularArticles(5);
        });

        $categories = Cache::remember('home.categories', 600, function () {
            return $this->categoryService->getCategoriesWithArticleCount();
        });

        return view('home', compact('hero', 'latest', 'popular', 'categories'));
    }
}
