<?php

namespace App\Http\Controllers;
use App\Role;

use Illuminate\Http\Request;

class RolesPermissionsController extends Controller
{
    function rolePermissions(Request $request) {
        // dd($request->all());

        $roles  = $request->roles;

        $rolePermissions = [];
        foreach($roles as $r) {
            $role = Role::find($r['id']);
            
            $flatten = $role->permissions->flatten();
            $rolePermissions[] = $flatten->all();
        }

        if (request()->ajax()) {
            return collect($rolePermissions)->flatten()->all();
        }
    }
}
