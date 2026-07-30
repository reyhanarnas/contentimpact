<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\ArticleRevisionRequested;
use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ManageArticleController extends Controller
{
    protected $articleService;
    protected $categoryService;

    public function __construct(ArticleService $articleService, CategoryService $categoryService)
    {
        $this->articleService = $articleService;
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $user = Auth::user();
        $pendingArticles = collect();
        
        if ($user->isAdmin()) {
            $articles = $this->articleService->getPublishedArticles(100)->getCollection();
            // Also get draft, pending, revision ones for admin
            $allArticles = Article::with(['category', 'author'])->orderBy('updated_at', 'desc')->get();
            $pendingArticles = $this->articleService->getPendingArticles();
        } elseif ($user->isEditor()) {
            $allArticles = Article::with(['category', 'author'])->orderBy('updated_at', 'desc')->get();
            $pendingArticles = $this->articleService->getPendingArticles();
        } else {
            // Journalist
            $allArticles = $this->articleService->getArticlesByAuthor($user->id);
        }

        return view('dashboard.articles.index', compact('allArticles', 'pendingArticles'));
    }

    public function create()
    {
        $this->authorizeAction('create', Article::class);
        $categories = $this->categoryService->getAllCategories();
        return view('dashboard.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create', Article::class);

        $validated = $request->validate([
            'title' => 'required|string|max:200|unique:articles,title',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:300',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,pending_review',
        ]);

        $validated['author_id'] = Auth::id();

        $this->articleService->createArticle($validated, $request->file('cover_image'));

        return redirect()->route('dashboard.articles.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function show(int $id)
    {
        $article = $this->articleService->getArticleById($id);
        if (!$article) {
            abort(404);
        }

        if (!Gate::allows('view', $article)) {
            abort(403);
        }

        $revisions = $this->articleService->getRevisionHistory($id);

        return view('dashboard.articles.show', compact('article', 'revisions'));
    }

    public function edit(int $id)
    {
        $article = $this->articleService->getArticleById($id);
        if (!$article) {
            abort(404);
        }

        $this->authorizeAction('update', $article);

        $categories = $this->categoryService->getAllCategories();
        return view('dashboard.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $article = $this->articleService->getArticleById($id);
        if (!$article) {
            abort(404);
        }

        $this->authorizeAction('update', $article);

        $validated = $request->validate([
            'title' => 'required|string|max:200|unique:articles,title,' . $id,
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:300',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // If resubmitting (Journalist resubmits if status is revision_required)
        if ($article->isRevisionRequired()) {
            $validated['status'] = 'pending_review';
        }

        $this->articleService->updateArticle($id, $validated, $request->file('cover_image'));

        return redirect()->route('dashboard.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $article = $this->articleService->getArticleById($id);
        if (!$article) {
            abort(404);
        }

        $this->authorizeAction('delete', $article);

        $this->articleService->deleteArticle($id);

        return redirect()->route('dashboard.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }

    public function submit(int $id)
    {
        $article = $this->articleService->getArticleById($id);
        if (!$article) {
            abort(404);
        }

        $this->authorizeAction('submit', $article);

        $this->articleService->submitForReview($id);

        return redirect()->route('dashboard.articles.index')->with('success', 'Artikel berhasil diajukan untuk review.');
    }

    public function approve(int $id)
    {
        $this->authorizeAction('review', Article::class);

        $this->articleService->approveArticle($id);

        return redirect()->route('dashboard.articles.index')->with('success', 'Artikel telah disetujui dan dipublikasikan.');
    }

    public function requestRevision(Request $request, int $id)
    {
        $this->authorizeAction('review', Article::class);

        $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        $note = $request->input('note');
        $this->articleService->requestRevision($id, Auth::id(), $note);

        // Kirim notifikasi real-time ke Jurnalis via broadcasting
        $article = $this->articleService->getArticleById($id);
        if ($article) {
            event(new ArticleRevisionRequested($article, Auth::user(), $note));
        }

        return redirect()->route('dashboard.articles.index')->with('success', 'Catatan revisi berhasil dikirim ke jurnalis.');
    }

    private function authorizeAction(string $ability, $arguments)
    {
        if (!Gate::allows($ability, $arguments)) {
            abort(403, 'Anda tidak memiliki wewenang untuk melakukan tindakan ini.');
        }
    }
}
