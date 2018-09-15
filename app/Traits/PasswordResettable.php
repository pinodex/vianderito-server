<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\PasswordReset;

trait PasswordResettable
{
    /**
     * Request password change on model
     */
    public function requestPasswordReset()
    {
        return $this->passwordResets()->create([
            'token' => str_random(16),
            'expires_at' => Carbon::now()->addMinutes(30)
        ]);
    }

    /**
     * Find password reset request
     * 
     * @param  string $id    Request ID
     * @param  string $token Request token
     * @return PasswordReset
     */
    public function findPasswordResetRequest($token)
    {
        return $this->passwordResets()
            ->where('token', $token)
            ->first();
    }

    /**
     * Get password resets
     */
    public function passwordResets()
    {
        return $this->morphMany(PasswordReset::class, 'entity');
    }
}
