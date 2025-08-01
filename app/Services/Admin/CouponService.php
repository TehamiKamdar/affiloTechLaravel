<?php
namespace App\Services\Admin;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class CouponService
{
    public function ajax(Request $request)
    {
        $permissions = Coupon::all();

        return DataTables::of($permissions)
            ->addColumn('action', fn($permission) => $this->getActionButtons($permission->id))
            ->rawColumns(['action'])
            ->make(true);
    }


    private function getActionButtons($permissionId)
    {
        //  $editUrl = route("admin.permissions.edit", ['permission' => $permissionId]);
        $viewUrl = route("admin.creatives.view", ['coupon' => $permissionId]);
        //  $deleteUrl = route("admin.permissions.delete", ['permission' => $permissionId]);

        // return '
        //     <a href="' . $editUrl . '" class="btn btn-sm btn-glow-primary btn-primary">Edit</a>
        //     <a href="' . $viewUrl . '" class="btn btn-sm btn-glow-secondary btn-secondary">View</a>
        //     <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
        //         ' . csrf_field() . '
        //         ' . method_field('DELETE') . '
        //         <button type="submit" class="btn btn-sm btn-glow-danger btn-danger">Delete</button>
        //     </form>';

        return '
       <a href="' . $viewUrl . '" class="btn btn-sm btn-secondary">View</a>';
    }
}
