<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function view(?User $user, Article $article): bool
    {
        if ($article->isPublished()) {
            return true;
        }

        // If not published, must be logged in
        if (!$user) {
            return false;
        }

        // Author, Editor, and Admin can view
        return $user->isAdmin() || $user->isEditor() || $article->author_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isJournalist();
    }

    public function update(User $user, Article $article): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isJournalist() && $article->author_id === $user->id) {
            // Journalist can only edit if status is draft or revision_required
            return $article->isDraft() || $article->isRevisionRequired();
        }

        return false;
    }

    public function delete(User $user, Article $article): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isJournalist() && $article->author_id === $user->id) {
            // Journalist can only delete if status is draft
            return $article->isDraft();
        }

        return false;
    }

    public function submit(User $user, Article $article): bool
    {
        if ($user->isJournalist() && $article->author_id === $user->id) {
            return $article->isDraft() || $article->isRevisionRequired();
        }

        return false;
    }

    public function review(User $user): bool
    {
        return $user->isAdmin() || $user->isEditor();
    }
}
