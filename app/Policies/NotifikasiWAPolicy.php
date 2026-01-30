<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NotifikasiWA;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotifikasiWAPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_notifikasi::w::a');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, NotifikasiWA $notifikasiWA): bool
    {
        return $user->can('view_notifikasi::w::a');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_notifikasi::w::a');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, NotifikasiWA $notifikasiWA): bool
    {
        return $user->can('update_notifikasi::w::a');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, NotifikasiWA $notifikasiWA): bool
    {
        return $user->can('delete_notifikasi::w::a');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_notifikasi::w::a');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, NotifikasiWA $notifikasiWA): bool
    {
        return $user->can('force_delete_notifikasi::w::a');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_notifikasi::w::a');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, NotifikasiWA $notifikasiWA): bool
    {
        return $user->can('restore_notifikasi::w::a');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_notifikasi::w::a');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, NotifikasiWA $notifikasiWA): bool
    {
        return $user->can('replicate_notifikasi::w::a');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_notifikasi::w::a');
    }
}
