<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class PlatformUserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'company'])
            ->latest()
            ->paginate(20);
        
        return view('super-admin.users.index', compact('users'));
    }

    public function superAdmins()
    {
        $superAdminRole = Role::where('slug', 'super_admin')->first();
        
        $users = User::with(['role', 'company'])
            ->where('role_id', $superAdminRole->id ?? null)
            ->latest()
            ->paginate(20);
        
        return view('super-admin.users.super-admins', compact('users'));
    }
}
