<?php

namespace App\Policies;

use App\Models\InternalRequest;
use App\Models\User;

class InternalRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Only case managers can view internal requests
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InternalRequest $internalRequest): bool
    {
        // Only case managers can view internal requests
        // Optionally: can only view their own requests
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only case managers can create internal requests
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InternalRequest $internalRequest): bool
    {
        // Only case managers can update internal requests
        // Optionally: only the creating case manager
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InternalRequest $internalRequest): bool
    {
        // Only case managers can delete internal requests
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InternalRequest $internalRequest): bool
    {
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InternalRequest $internalRequest): bool
    {
        return $user->isCaseManager();
    }
}
