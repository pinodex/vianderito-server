<?php

namespace App\Components;

class Acl
{
    protected $permissions = [];

    public function __construct($permissions)
    {
        $this->permissions = $permissions;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}
