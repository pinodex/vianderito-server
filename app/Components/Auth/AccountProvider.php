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
use App\Models\Account;
use App\Exceptions\AccountDisabledException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as BaseUserProvider;

class AccountProvider implements BaseUserProvider
{
    public function retrieveById($identifier)
    {
        return Account::find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {

    }

    public function updateRememberToken(Authenticatable $user, $token)
    {

    }

    public function retrieveByCredentials(array $credentials)
    {
        if (!isset($credentials['id'])) {
            return null;
        }

        return Account::where('username', $credentials['id'])
            ->orWhere('email', $credentials['id'])->first();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!isset($credentials['id'])) {
            return false;
        }
        
        if (!isset($credentials['id'])) {
            return false;
        }

        if ($user->password === null) {
            return false;
        }

        if (!$user->is_enabled) {
            throw new AccountDisabledException();
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
