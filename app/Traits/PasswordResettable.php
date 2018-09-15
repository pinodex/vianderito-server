<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\PasswordReset;

trait PasswordResettable
{
    /**
     * Request password change on model
     *
     * @param int $validity Password token validity in minutes
     */
    public function requestPasswordReset($validity = 30)
    {
        return $this->passwordResets()->create([
            'token' => str_random(16),
            'expires_at' => Carbon::now()->addMinutes($validity)
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
            ->where('expires_at', '>', Carbon::now())
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
