<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleRevision;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function all(): Collection
    {
        return Article::with(['category', 'author'])->get();
    }

    public function find(int $id): ?Article
    {
        return Article::with(['category', 'author', 'comments', 'revisions.editor'])->find($id);
    }

    public function findBySlug(string $slug): ?Article
    {
        return Article::with(['category', 'author', 'comments' => function ($query) {
            $query->where('status', 'approved')->orderBy('created_at', 'desc');
        }, 'revisions.editor'])
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Article
    {
        return Article::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $article = Article::find($id);
        if ($article) {
            return $article->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $article = Article::find($id);
        if ($article) {
            return $article->delete();
        }
        return false;
    }

    public function incrementViews(int $id): void
    {
        Article::where('id', $id)->increment('views');
    }

    public function getPublished(int $limit = 10): LengthAwarePaginator
    {
        return Article::with(['category', 'author'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate($limit);
    }

    public function getPopular(int $limit = 5): Collection
    {
        return Article::with(['category', 'author'])
            ->published()
            ->popular()
            ->limit($limit)
            ->get();
    }

    public function getHero(): ?Article
    {
        // The hero is the latest published article with high view count or just the absolute latest published article
        return Article::with(['category', 'author'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->first();
    }

    public function getLatest(int $limit = 6): Collection
    {
        return Article::with(['category', 'author'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $query = Article::with(['category', 'author'])->published();

        if (!empty($filters['query'])) {
            $q = $filters['query'];
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['author'])) {
            $query->where('author_id', $filters['author']);
        }

        if (!empty($filters['date'])) {
            $date = Carbon::parse($filters['date']);
            $query->whereDate('published_at', $date);
        }

        return $query->orderBy('published_at', 'desc')->paginate(10)->withQueryString();
    }

    public function getByAuthor(int $authorId): Collection
    {
        return Article::with(['category', 'author'])
            ->where('author_id', $authorId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getPendingReview(): Collection
    {
        return Article::with(['category', 'author'])
            ->where('status', 'pending_review')
            ->orderBy('updated_at', 'asc') // First in first out review queue
            ->get();
    }

    public function addRevision(int $articleId, int $editorId, string $note): void
    {
        ArticleRevision::create([
            'article_id' => $articleId,
            'editor_id' => $editorId,
            'note' => $note,
        ]);
    }

    public function getRevisionHistory(int $articleId): Collection
    {
        return ArticleRevision::with('editor')
            ->where('article_id', $articleId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTotalCount(): int
    {
        return Article::count();
    }

    public function getPublishedCount(): int
    {
        return Article::where('status', 'published')->count();
    }

    public function getPendingCount(): int
    {
        return Article::where('status', 'pending_review')->count();
    }

    public function getViewsOverTime(): array
    {
        // Views over the last 7 days grouped by date
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $views = Article::whereDate('published_at', $date)->sum('views');
            $data[$date] = (int)$views;
        }
        return [
            'labels' => array_keys($data),
            'data' => array_values($data)
        ];
    }

    public function getArticlesPerCategory(): array
    {
        $categories = DB::table('categories')
            ->leftJoin('articles', 'categories.id', '=', 'articles.category_id')
            ->select('categories.name', DB::raw('count(articles.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        return [
            'labels' => $categories->pluck('name')->toArray(),
            'data' => $categories->pluck('total')->toArray()
        ];
    }

    public function getMostViewed(int $limit = 5): Collection
    {
        return Article::with(['category', 'author'])
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }
}
