<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

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
        $hero = $this->articleService->getHeroArticle();
        
        // Exclude hero from latest if hero exists
        $latest = $this->articleService->getLatestArticles(7);
        if ($hero && $latest->contains('id', $hero->id)) {
            $latest = $latest->filter(fn($art) => $art->id !== $hero->id)->take(6);
        } else {
            $latest = $latest->take(6);
        }

        $popular = $this->articleService->getPopularArticles(5);
        $categories = $this->categoryService->getCategoriesWithArticleCount();

        return view('home', compact('hero', 'latest', 'popular', 'categories'));
    }
}
