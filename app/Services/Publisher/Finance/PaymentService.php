<?php
namespace App\Services\Publisher\Finance;

use Illuminate\Http\Request;
use App\Helper\Static\Vars;
use App\Models\PaymentHistory;
use App\Models\Website;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PaymentService extends BaseService
{
    public function init(Request $request)
{
    $total = 0;
    $perPage = $request->get("per_page", 50);
    $page = $request->get("page", 1);
    $limit = Vars::DEFAULT_PUBLISHER_PAYMENT_PAGINATION;

    if (session()->has('publisher_payment_limit')) {
        $limit = session()->get('publisher_payment_limit');
    }

    $websites = Website::withAndWhereHas('users', function ($user) {
        return $user->where("id", auth()->user()->id);
    })->where("status", Website::ACTIVE)->count();

    $message = $type = null;

    if ($websites) {
        $paymentsQuery = PaymentHistory::with('payment_method')
            ->where("publisher_id", auth()->user()->id);

        if ($request->search_by_name) {
            $paymentsQuery = $paymentsQuery->where(function ($query) use ($request) {
                $query->orWhere("invoice_id", "LIKE", "%{$request->search_by_name}%")
                      ->orWhere("payment_id", "LIKE", "%{$request->search_by_name}%")
                      ->orWhere("transaction_id", "LIKE", "%{$request->search_by_name}%");
            });
        }

        $payments = $paymentsQuery->orderBy("created_at", "DESC")
            ->paginate($perPage, ["*"], "page", $page);

    } else {
        $url = route("publisher.profile.website");
        $message = "Please go to <a href='{$url}'>website settings</a> and verify your site to view Report Transactions.";
        $type = "error";

        // Return an empty paginator to avoid crashing
        $payments = new LengthAwarePaginator([], 0, $perPage, $page);
    }

    $from = ($payments->currentPage() - 1) * $payments->perPage() + 1;
    $to = min($payments->currentPage() * $payments->perPage(), $payments->total());
    $total = $payments->total();

    $title = "Payments";
    seo()->title(default: "{$title} — " . env("APP_NAME"));
    $headings = ['Finance', $title];

    if ($request->ajax()) {
        $view = view("publisher.payments.list_view", compact('payments', 'title', 'headings', 'to', 'from', 'total'))->render();
        return response()->json(['html' => $view]);
    }

    if ($type && $message) {
        Session::put($type, $message);
    }

    return view("publisher.payments.list", compact('payments', 'title', 'headings', 'to', 'from', 'total'));
}
    

    public function invoice(PaymentHistory $paymentHistory)
    {
        $paymentHistory->load(['payment_method', 'user']);
        $company = $paymentHistory->user->companies->last();
        return view("publisher.payments.invoice", compact('paymentHistory', 'company'));
    }
}
