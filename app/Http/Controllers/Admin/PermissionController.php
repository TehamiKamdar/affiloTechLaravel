<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    protected $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request){
        $permissions = Permission::orderBy('id', 'DESC')->paginate(5);
        return view('admin.permission.index', compact('permissions'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function ajax(Request $request)
    {
        return $this->service->ajax($request);
    }

    public function create()
    {
        return view('admin.permission.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
        ]);

        $input = $request->all();
        $input['guard_name'] = 'web';
       $permission = Permission::create($input);
        return redirect()->route('admin.permissions.index')->with('success', 'User created successfully');
    }

    public function edit($id){
        $permission = Permission::find($id);
        return view('admin.permission.edit',compact('permission'));
    }

    public function update(Request $request){
        $id = $request->id;
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->update();
        return redirect()->route('admin.permissions.index')->with('success', 'User edited successfully');
    }

    public function destroy($id)
    {
        Permission::find($id)->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'User deleted successfully');
    }
}
