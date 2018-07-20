<?php

namespace App\Traits;

use Hash;

trait PasswordHash
{
    /**
     * Auto-hash incoming password
     * 
     * @param string $password Password
     */
    public function setPasswordAttribute($password)
    {
        if ($password !== null) {
            $password = Hash::make($password);
        }

        $this->attributes['password'] = $password;
    }

    /**
     * Check if password matches the hash in the database
     * 
     * @param  string $password Plain text password
     * @return boolean
     */
    public function checkPassword($password)
    {
        return Hash::check($password, $this->attributes['password']);
    }
}
