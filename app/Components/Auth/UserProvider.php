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
        if (!isset($credentials['id'])) {
            return null;
        }
        
        $query = User::with($this->relations)
            ->where('username', $credentials['id'])
            ->orWhere('email_address', $credentials['id']);

        $matches = [];

        if (preg_match('/^(09|\+639)(\d{9})$/', $credentials['id'], $matches)) {
            // Try to match against 09 and +63 formats of PH phone numbers
            
            if (count($matches) == 3) {
                $query->orWhere('phone_number', '09' . $matches[2])
                    ->orWhere('phone_number', '+63' . $matches[2]);
            }
        }

        return $query->first();
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
