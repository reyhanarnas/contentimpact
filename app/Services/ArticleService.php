<?php

namespace App\Services;

use App\Repositories\ArticleRepositoryInterface;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleService
{
    protected $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getArticleById(int $id): ?Article
    {
        return $this->articleRepository->find($id);
    }

    public function getArticleBySlug(string $slug): ?Article
    {
        return $this->articleRepository->findBySlug($slug);
    }

    public function getPublishedArticles(int $limit = 10): LengthAwarePaginator
    {
        return $this->articleRepository->getPublished($limit);
    }

    public function getPopularArticles(int $limit = 5): Collection
    {
        return $this->articleRepository->getPopular($limit);
    }

    public function getHeroArticle(): ?Article
    {
        return $this->articleRepository->getHero();
    }

    public function getLatestArticles(int $limit = 6): Collection
    {
        return $this->articleRepository->getLatest($limit);
    }

    public function searchArticles(array $filters): LengthAwarePaginator
    {
        return $this->articleRepository->search($filters);
    }

    public function getArticlesByAuthor(int $authorId): Collection
    {
        return $this->articleRepository->getByAuthor($authorId);
    }

    public function getPendingArticles(): Collection
    {
        return $this->articleRepository->getPendingReview();
    }

    public function createArticle(array $data, $coverImageFile = null): Article
    {
        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }
        
        if ($coverImageFile) {
            $path = $coverImageFile->store('covers', 'public');
            $data['cover_image'] = $path;
        }

        // Excerpt generation if empty
        if (empty($data['excerpt'])) {
            $data['excerpt'] = Str::limit(strip_tags($data['content']), 150);
        }

        $data['status'] = $data['status'] ?? 'draft';
        $data['views'] = 0;

        return $this->articleRepository->create($data);
    }

    public function updateArticle(int $id, array $data, $coverImageFile = null): bool
    {
        $article = $this->articleRepository->find($id);
        if (!$article) {
            return false;
        }

        if (!empty($data['title']) && $data['title'] !== $article->title && empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['title']);
        }

        if ($coverImageFile) {
            // Delete old cover image if it exists
            if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $path = $coverImageFile->store('covers', 'public');
            $data['cover_image'] = $path;
        }

        if (isset($data['content']) && empty($data['excerpt'])) {
            $data['excerpt'] = Str::limit(strip_tags($data['content']), 150);
        }

        return $this->articleRepository->update($id, $data);
    }

    public function deleteArticle(int $id): bool
    {
        $article = $this->articleRepository->find($id);
        if (!$article) {
            return false;
        }

        if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
            Storage::disk('public')->delete($article->cover_image);
        }

        return $this->articleRepository->delete($id);
    }

    public function submitForReview(int $id): bool
    {
        return $this->articleRepository->update($id, [
            'status' => 'pending_review',
        ]);
    }

    public function approveArticle(int $id): bool
    {
        return $this->articleRepository->update($id, [
            'status' => 'published',
            'published_at' => Carbon::now(),
        ]);
    }

    public function requestRevision(int $id, int $editorId, string $note): bool
    {
        // Update article status and note
        $updated = $this->articleRepository->update($id, [
            'status' => 'revision_required',
            'revision_note' => $note,
        ]);

        if ($updated) {
            // Log in article_revisions table
            $this->articleRepository->addRevision($id, $editorId, $note);
            return true;
        }

        return false;
    }

    public function resubmitArticle(int $id, array $data, $coverImageFile = null): bool
    {
        // Update content first
        $data['status'] = 'pending_review';
        return $this->updateArticle($id, $data, $coverImageFile);
    }

    public function incrementArticleViews(int $id): void
    {
        $this->articleRepository->incrementViews($id);
    }

    public function getRevisionHistory(int $articleId): Collection
    {
        return $this->articleRepository->getRevisionHistory($articleId);
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
