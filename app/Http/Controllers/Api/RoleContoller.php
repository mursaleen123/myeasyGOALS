<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DB;

class RoleContoller extends Controller
{

    public function getModePermissions($mode)
    {
        if (Role::where('name', '=', $mode)->exists()) {
            $role = Role::where('name', $mode)->first();
            $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                ->where("role_has_permissions.role_id", $role->id)
                ->get();
            $permissions = [];
            foreach ($rolePermissions as $permission) {
                array_push($permissions, $permission['name']);
            }
            return $this->responseApi($permissions, true, 'User Permissions Retrieved Successfully', 200);
        } else {
            return $this->responseApi([], true, 'User Permissions Retrieved Successfully', 200);
        }
    }

    public function responseApi($data, $status, $message, $code)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status
        ], $code);
    }
}
