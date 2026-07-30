<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Comment;
    public function create(array $data): Comment;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getPending(): Collection;
    public function approve(int $id): bool;
    public function reject(int $id): bool;
    public function getApprovedForArticle(int $articleId): Collection;
}
