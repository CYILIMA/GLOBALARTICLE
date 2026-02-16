<?php

namespace App\Policies;

use App\Models\Documentary;
use App\Models\User;

class DocumentaryPolicy
{
    /**
     * Determine whether the user can index any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor', 'author']);
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
    public function update(User $user, Documentary $documentary): bool
    {
        // Admin and Editor can update any
        if (in_array($user->role, ['admin', 'editor'])) {
            return true;
        }

        // Author can only update their own
        return $user->id === $documentary->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Documentary $documentary): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $documentary->user_id;
    }

    /**
     * Determine whether the user can publish.
     */
    public function publish(User $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
