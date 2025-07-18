<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Transaction;
use App\Services\Admin\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return view('admin.transaction.index');
    }

    public function ajax(Request $request)
    {
        return $this->service->ajax($request);
    }

    public function view($id){
        $transaction = Transaction::find($id);
        if($transaction){
          $advertiser = Advertiser::where('advertiser_id',$transaction->advertiser_id);
          $transaction['advertiser'] = $advertiser;
        }
        return view('admin.transaction.view',compact('transaction'));
    }
}
