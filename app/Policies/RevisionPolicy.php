<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Revision;
use Illuminate\Auth\Access\HandlesAuthorization;

class RevisionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Revision');
    }

    public function view(AuthUser $authUser, Revision $revision): bool
    {
        return $authUser->can('View:Revision');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Revision');
    }

    public function update(AuthUser $authUser, Revision $revision): bool
    {
        return $authUser->can('Update:Revision');
    }

    public function delete(AuthUser $authUser, Revision $revision): bool
    {
        return $authUser->can('Delete:Revision');
    }

    public function restore(AuthUser $authUser, Revision $revision): bool
    {
        return $authUser->can('Restore:Revision');
    }

    public function forceDelete(AuthUser $authUser, Revision $revision): bool
    {
        return $authUser->can('ForceDelete:Revision');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Revision');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Revision');
    }

    public function replicate(AuthUser $authUser, Revision $revision): bool
    {
        return $authUser->can('Replicate:Revision');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Revision');
    }

}