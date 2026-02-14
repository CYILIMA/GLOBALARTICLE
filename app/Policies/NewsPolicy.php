<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Filtering is handled in the controller
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, News $news): bool
    {
        return true; // Public news
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor', 'author']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, News $news): bool
    {
        // Admin and Editor can update any
        if (in_array($user->role, ['admin', 'editor'])) {
            return true;
        }

        // Author can only update their own
        return $user->id === $news->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, News $news): bool
    {
        // Admin can delete any
        if ($user->role === 'admin') {
            return true;
        }

        // Author can delete their own
        return $user->id === $news->user_id;
    }

    /**
     * Determine whether the user can publish articles.
     */
    public function publish(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
