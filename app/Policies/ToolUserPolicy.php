<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ToolUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class ToolUserPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ToolUser');
    }

    public function view(AuthUser $authUser, ToolUser $toolUser): bool
    {
        return $authUser->can('View:ToolUser');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ToolUser');
    }

    public function update(AuthUser $authUser, ToolUser $toolUser): bool
    {
        return $authUser->can('Update:ToolUser');
    }

    public function delete(AuthUser $authUser, ToolUser $toolUser): bool
    {
        return $authUser->can('Delete:ToolUser');
    }

    public function restore(AuthUser $authUser, ToolUser $toolUser): bool
    {
        return $authUser->can('Restore:ToolUser');
    }

    public function forceDelete(AuthUser $authUser, ToolUser $toolUser): bool
    {
        return $authUser->can('ForceDelete:ToolUser');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ToolUser');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ToolUser');
    }

    public function replicate(AuthUser $authUser, ToolUser $toolUser): bool
    {
        return $authUser->can('Replicate:ToolUser');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ToolUser');
    }

}