<?php
namespace App\Services\Admin;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RoleService
{
    public function ajax(Request $request)
    {
        $roles = Role::with('permissions')->select(['id', 'name', 'guard_name']);

        return DataTables::of($roles)
        ->addColumn('permissions', function ($role) {
            if ($role->permissions->isNotEmpty()) {
                return $role->permissions->pluck('name')
                    ->map(fn($permission) => "<span class='badge badge-sm' style='color: white;background: #f6903f;'>$permission</span>")
                    ->implode(' ');
            }
            return '<span class="badge badge-sm" style="color: white;background:rgb(228, 22, 22);">No permissions assigned</span>';
        })
        ->addColumn('action', fn($role) => $this->getActionButtons($role->id))
        ->rawColumns(['permissions', 'action']) // Ensure the HTML is rendered for these columns
        ->make(true);
    }

    private function getActionButtons($roleId)
    {
        $editUrl = route("admin.roles.edit", ['role' => $roleId]);
        // $viewUrl = route("admin.roles.view", ['role' => $roleId]);
         $deleteUrl = route("admin.roles.delete", ['role' => $roleId]);

        // return '
        //     <a href="' . $editUrl . '" class="btn btn-sm btn-glow-primary btn-primary">Edit</a>
        //     <a href="' . $viewUrl . '" class="btn btn-sm btn-glow-secondary btn-secondary">View</a>
        //     <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
        //         ' . csrf_field() . '
        //         ' . method_field('DELETE') . '
        //         <button type="submit" class="btn btn-sm btn-glow-danger btn-danger">Delete</button>
        //     </form>';

        return '
         <a href="' . $editUrl . '" class="btn btn-sm btn-glow-primary btn-primary">Edit</a>
        <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                 ' . csrf_field() . '
             <button type="submit" class="btn btn-sm btn-glow-danger btn-danger">Delete</button>
           </form>';
    }
}
