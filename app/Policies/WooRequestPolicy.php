<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WooRequest;

class WooRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view the list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WooRequest $wooRequest): bool
    {
        // Burgers can only view their own requests
        if ($user->isBurger()) {
            return $user->id === $wooRequest->user_id;
        }

        // Case managers can view all requests
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only burgers can create WOO requests
        return $user->isBurger();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WooRequest $wooRequest): bool
    {
        // Only case managers can update requests
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WooRequest $wooRequest): bool
    {
        // Burgers can delete their own requests if not yet processed
        if ($user->isBurger() && $user->id === $wooRequest->user_id) {
            return $wooRequest->status === 'submitted';
        }

        // Case managers can delete any request
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WooRequest $wooRequest): bool
    {
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WooRequest $wooRequest): bool
    {
        return $user->isCaseManager();
    }
}
