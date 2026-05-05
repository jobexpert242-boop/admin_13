<?php

namespace App\Http\Controllers\BackEnd\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return Inertia::render('BackEnd/Permissions/Permission', [
            'permissions' => Permission::latest()->paginate(10),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $request->name,
        ]);

        return back()->with('status', 'Permission Created Successfully');
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return back()->with('status', 'Permission Updated Successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return back()->with('status', 'Permission Deleted Successfully');
    }
}
