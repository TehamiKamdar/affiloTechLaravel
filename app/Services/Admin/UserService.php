<?php
namespace App\Services\Admin;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserService
{
    public function ajax(Request $request)
    {
        $users = User::select([
            'id', 'publisher_id', 'name', 'email', 'type', 'status','created_at'
        ]);

        return DataTables::of($users)
            ->addColumn('action', fn($user) => $this->getActionButtons($user->id))
            ->rawColumns(['action'])
            ->make(true);
    }

    private function getActionButtons($userId)
    {
        // $editUrl = route("admin.transactions.edit", ['transaction' => $transactionId]);
        $viewUrl = route("admin.users.view", ['user' => $userId]);
         $deleteUrl = route("admin.users.delete", ['user' => $userId]);

        // return '
        //     <a href="' . $editUrl . '" class="btn btn-sm btn-glow-primary btn-primary">Edit</a>
        //     <a href="' . $viewUrl . '" class="btn btn-sm btn-glow-secondary btn-secondary">View</a>
        //     <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
        //         ' . csrf_field() . '
        //         ' . method_field('DELETE') . '
        //         <button type="submit" class="btn btn-sm btn-glow-danger btn-danger">Delete</button>
        //     </form>';

        return '<a href="' . $viewUrl . '" class="btn btn-sm btn-glow-secondary btn-secondary">View</a>
        <form action="' . $deleteUrl . '" method="POST" style="display:inline;">
                 ' . csrf_field() . '
             <button type="submit" class="btn btn-sm btn-glow-danger btn-danger">Delete</button>
           </form>';
    }
}