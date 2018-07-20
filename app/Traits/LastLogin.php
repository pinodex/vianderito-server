<?php

namespace App\Traits;

use Carbon\Carbon;

trait LastLogin
{
    /**
     * Update last login column
     * 
     * @param  boolean $save Save model on invoke
     */
    public function updateLastLogin($save = false)
    {
        $this->last_login_at = Carbon::now();

        if ($save) {
            $this->save();
        }
    }
}