<?php
namespace App\Services\Admin;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class PermissionService
{
    public function ajax(Request $request)
    {
        $permissions = Permission::select([
            'id', 'name'
        ]);

        return DataTables::of($permissions)
            ->addColumn('action', fn($permission) => $this->getActionButtons($permission->id))
            ->rawColumns(['action'])
            ->make(true);
    }

    private function getActionButtons($permissionId)
    {
         $editUrl = route("admin.permissions.edit", ['permission' => $permissionId]);
        $viewUrl = route("admin.permissions.view", ['permission' => $permissionId]);
         $deleteUrl = route("admin.permissions.delete", ['permission' => $permissionId]);

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