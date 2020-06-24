<?php

namespace App\Libraries\Gate;

use App\Models\User;

class GateLibrary
{
    /**
     * @param User $user
     * @return bool
     */
    public function userCanCreateShortUrlDomains(User $user): bool
    {
        return true;
    }
}
