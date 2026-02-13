<?php

namespace App\Policies;

use App\Quotation;
use App\User;
use Illuminate\Auth\Access\Response;

class QuotationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view a list of quotations.
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Quotation $quotation): bool
    {
        // The user can view the quotation if they are the creator of the quotation.
        return $user->id === $quotation->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create a quotation.
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quotation $quotation): bool
    {
        // The user can update the quotation if they are the creator of the quotation.
        return $user->id === $quotation->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quotation $quotation): bool
    {
        // The user can delete the quotation if they are the creator of the quotation.
        return $user->id === $quotation->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Quotation $quotation): bool
    {
        // Restore functionality is not currently envisioned, deny by default.
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Quotation $quotation): bool
    {
        // Force delete functionality is not currently envisioned, deny by default.
        return false;
    }
}
