<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Services\UserService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $articleService;
    protected $categoryService;
    protected $userService;

    public function __construct(ArticleService $articleService, CategoryService $categoryService, UserService $userService)
    {
        $this->articleService = $articleService;
        $this->categoryService = $categoryService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['query', 'category', 'author', 'date']);
        
        $articles = $this->articleService->searchArticles($filters);
        $categories = $this->categoryService->getAllCategories();
        
        // Get all authors (journalists) for filter
        $authors = $this->userService->getAllUsers()->filter(function ($u) {
            return $u->isJournalist() || $u->isAdmin();
        });

        return view('search', compact('articles', 'categories', 'authors', 'filters'));
    }
}
