<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ArticleRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Article;
    public function findBySlug(string $slug): ?Article;
    public function create(array $data): Article;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function incrementViews(int $id): void;
    public function getPublished(int $limit = 10): LengthAwarePaginator;
    public function getPopular(int $limit = 5): Collection;
    public function getHero(): ?Article;
    public function getLatest(int $limit = 6): Collection;
    public function search(array $filters): LengthAwarePaginator;
    public function getByAuthor(int $authorId): Collection;
    public function getPendingReview(): Collection;
    public function addRevision(int $articleId, int $editorId, string $note): void;
    public function getRevisionHistory(int $articleId): Collection;
    
    // Metrics
    public function getTotalCount(): int;
    public function getPublishedCount(): int;
    public function getPendingCount(): int;
    
    // Analytics
    public function getViewsOverTime(): array;
    public function getArticlesPerCategory(): array;
    public function getMostViewed(int $limit = 5): Collection;
}
