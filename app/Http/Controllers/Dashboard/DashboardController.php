<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use App\Services\UserService;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $articleService;
    protected $userService;
    protected $articleRepo;
    protected $userRepo;

    public function __construct(
        ArticleService $articleService,
        UserService $userService,
        ArticleRepositoryInterface $articleRepo,
        UserRepositoryInterface $userRepo
    ) {
        $this->articleService = $articleService;
        $this->userService = $userService;
        $this->articleRepo = $articleRepo;
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Metrics
        $metrics = [
            'total_users' => $this->userRepo->getTotalCount(),
            'active_users' => $this->userRepo->getActiveCount(),
            'suspended_users' => $this->userRepo->getSuspendedCount(),
            'total_articles' => $this->articleRepo->getTotalCount(),
            'published_articles' => $this->articleRepo->getPublishedCount(),
            'pending_articles' => $this->articleRepo->getPendingCount(),
        ];

        // Chart Data (Views over time, categories)
        $viewsChart = $this->articleRepo->getViewsOverTime();
        $categoriesChart = $this->articleRepo->getArticlesPerCategory();
        $popularArticles = $this->articleRepo->getMostViewed(5);

        // Role specific articles
        $myArticlesCount = 0;
        $pendingArticles = collect();

        if ($user->isJournalist()) {
            $myArticlesCount = $user->articles()->count();
        } elseif ($user->isAdmin() || $user->isEditor()) {
            $pendingArticles = $this->articleService->getPendingArticles();
        }

        return view('dashboard.index', compact('metrics', 'viewsChart', 'categoriesChart', 'popularArticles', 'myArticlesCount', 'pendingArticles'));
    }
}
