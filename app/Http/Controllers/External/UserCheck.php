<?php

namespace App\Http\Controllers\External;

use App\User;

class UserCheck
{
    public function __construct()
    {
    }

    public function checkUserExists($username, $password)
    {
            $user = User::where([['username', $username], ['password', md5($password)], ['role', 'healthworker']])->get()->first();
        if (!empty($user))
            return true;
        return false;
    }
}
