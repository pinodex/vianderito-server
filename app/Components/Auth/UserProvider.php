<?php

/*
 * This file is part of the TAS System for STI College Novaliches
 *
 * (c) Raphael Marco <raphaelmarco@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Components\Auth;

use Hash;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as BaseUserProvider;

class UserProvider implements BaseUserProvider
{
    protected $relations = [];

    public function retrieveById($identifier)
    {
        return User::with($this->relations)->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {

    }

    public function updateRememberToken(Authenticatable $user, $token)
    {

    }

    public function retrieveByCredentials(array $credentials)
    {
        return User::with($this->relations)
            ->where('username', $credentials['id'])
            ->orWhere('email_address', $credentials['id'])
            ->orWhere('phone_number', $credentials['id'])
            ->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if ($user->password === null) {
            return false;
        }
        
        if (Hash::check($credentials['password'], $user->password)) {
            $user->timestamps = false;

            if (Hash::needsRehash($user->password)) {
                $user->password = Hash::make($credentials['password']);
            }

            $user->last_login_at = Carbon::now();
            $user->save();

            return true;
        }

        return false;
    }
}
