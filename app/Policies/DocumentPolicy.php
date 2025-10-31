<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $document): bool
    {
        $wooRequest = $document->wooRequest;

        // Burgers can only view documents for their own requests
        if ($user->isBurger()) {
            return $user->id === $wooRequest->user_id;
        }

        // Case managers can view all documents
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Documents are created through upload portal or by case managers
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        // Only case managers can update documents
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        // Only case managers can delete documents
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->isCaseManager();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->isCaseManager();
    }
}
