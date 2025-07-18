<?php

namespace App\Http\Controllers;

use App\Services\Admin\RoleService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    protected $service;
    public function __construct(RoleService $service)
    {
        $this->service = $service;
        // $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        // $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request): View
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(5);
        return view('admin.role.index', compact('roles'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create(): View
    {
        $permission = Permission::get();
        return view('admin.role.create', compact('permission'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required'
        ]);

        $permissionsID = array_map(fn($value) => (int) $value, $request->input('permission'));
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionsID);

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role created successfully');
    }

    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                                     ->where('role_has_permissions.role_id', $id)
                                     ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id): View
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')
                              ->where('role_has_permissions.role_id', $id)
                              ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                              ->all();

        return view('admin.role.edit', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required'
        ]);
        $id = $request->id;

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $permissionsID = array_map(fn($value) => (int) $value, $request->input('permission'));
        $role->syncPermissions($permissionsID);

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DB::table('roles')->where('id', $id)->delete();

        return redirect()->route('admin.roles.index')
                         ->with('success', 'Role deleted successfully');
    }

    public function ajax(Request $request)
    {
        return $this->service->ajax($request);
    }
}
