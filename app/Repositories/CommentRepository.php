<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository implements CommentRepositoryInterface
{
    public function all(): Collection
    {
        return Comment::with('article')->orderBy('created_at', 'desc')->get();
    }

    public function find(int $id): ?Comment
    {
        return Comment::find($id);
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $comment = $this->find($id);
        if ($comment) {
            return $comment->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $comment = $this->find($id);
        if ($comment) {
            return $comment->delete();
        }
        return false;
    }

    public function getPending(): Collection
    {
        return Comment::with('article')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function approve(int $id): bool
    {
        return $this->update($id, ['status' => 'approved']);
    }

    public function reject(int $id): bool
    {
        return $this->update($id, ['status' => 'rejected']);
    }

    public function getApprovedForArticle(int $articleId): Collection
    {
        return Comment::where('article_id', $articleId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
