<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    protected $articleService;
    protected $commentService;

    public function __construct(ArticleService $articleService, CommentService $commentService)
    {
        $this->articleService = $articleService;
        $this->commentService = $commentService;
    }

    public function show(string $slug)
    {
        $article = $this->articleService->getArticleBySlug($slug);

        if (!$article) {
            abort(404, 'Artikel tidak ditemukan.');
        }

        // Policy check for draft / revision_required
        if (!Gate::allows('view', $article)) {
            abort(403, 'Halaman ini dilindungi.');
        }

        // Increment view count if published
        if ($article->isPublished()) {
            $this->articleService->incrementArticleViews($article->id);
        }

        // Fetch related articles (same category, published, excluding current)
        $filters = [
            'category' => $article->category_id,
        ];
        $related = $this->articleService->searchArticles($filters)->getCollection()
            ->filter(fn($art) => $art->id !== $article->id)
            ->take(3);

        // Approved comments
        $comments = $this->commentService->getApprovedCommentsForArticle($article->id);

        return view('articles.show', compact('article', 'related', 'comments'));
    }

    public function storeComment(Request $request, string $slug)
    {
        $article = $this->articleService->getArticleBySlug($slug);

        if (!$article || !$article->isPublished()) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'comment' => 'required|string|max:1000',
        ]);

        $validated['article_id'] = $article->id;

        $this->commentService->addComment($validated);

        return redirect()->back()->with('success', 'Komentar Anda telah berhasil ditambahkan.');
    }
}
