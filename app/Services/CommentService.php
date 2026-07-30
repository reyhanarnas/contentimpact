<?php

namespace App\Services;

use App\Repositories\CommentRepositoryInterface;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function getPendingComments(): Collection
    {
        return $this->commentRepository->getPending();
    }

    public function getAllComments(): Collection
    {
        return $this->commentRepository->all();
    }

    public function addComment(array $data): Comment
    {
        // By default comments are created with status 'approved'
        $data['status'] = 'approved';
        return $this->commentRepository->create($data);
    }

    public function approveComment(int $id): bool
    {
        return $this->commentRepository->approve($id);
    }

    public function deleteComment(int $id): bool
    {
        return $this->commentRepository->delete($id);
    }

    public function getApprovedCommentsForArticle(int $articleId): Collection
    {
        return $this->commentRepository->getApprovedForArticle($articleId);
    }
}
