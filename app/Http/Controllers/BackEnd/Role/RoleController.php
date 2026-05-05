<?php

namespace App\Http\Controllers\BackEnd\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        return Inertia::render('BackEnd/Roles/Role', [
            'roles' => Role::with('permissions')->latest()->paginate(10),
            'permissions' => Permission::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions);

        return back()->with('status', 'Role Created Successfully');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions);

        return back()->with('status', 'Role Updated Successfully');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return back()->with('status', 'Role Deleted Successfully');
    }
}
