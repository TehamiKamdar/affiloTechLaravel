<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Hash;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return view('admin.user.index');
    }

    public function ajax(Request $request)
    {
        return $this->service->ajax($request);
    }

    public function view($id){
        $user = User::find($id);
        return view('admin.user.view',compact('user'));
    }

    public function status_pending($id){
        $user = User::find($id);
        $user->status = 'pending';
        $user->update();
        $user->save();
      
        return redirect()->route('admin.users.view',['user'=>$id]);
    }

    public function status_active($id){
        $user = User::find($id);
        $user->status = 'active';
        $user->update();
        $user->save();
        
        return redirect()->route('admin.users.view',['user'=>$id]);
    }

    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        dd($request);
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}
