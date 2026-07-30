<?php

namespace App\Policies;

use App\Models\User;

class CommentPolicy
{
    public function moderate(User $user): bool
    {
        return $user->isAdmin();
    }
}
