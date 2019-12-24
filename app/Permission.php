<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    function permissions() {
        return $this->belongsToMany('App\Permission', 'role_has_permissions', 'permission_id', 'role_id');
    }
}
