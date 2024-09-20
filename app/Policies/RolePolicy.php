<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    /**
     * Determine if the given user can access the resource based on role.
     */
    public function isMaker(User $user)
    {
        return $user->role === 'maker';
    }

    public function isChecker(User $user)
    {
        return $user->role === 'checker';
    }
}
