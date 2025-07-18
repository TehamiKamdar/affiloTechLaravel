<?php

namespace App\Service\Admin\Transactions;

use App\Models\Country;
use App\Models\PaymentHistory;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Action;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class IndexService
{
    use Action;

    public function init(Request $request)
    {

        if($request->ajax())
        {
            // EDIT
            $editGate      =  '';//'crm_transaction_edit';

            // VIEW
            $viewGate      =  'crm_transaction_show';

            // DELETE
            $deleteGate    =  '';//'crm_transaction_delete';

            // PERMISSIONS
            $crudRoutePart = 'admin.transactions';

            $actionData = [
                "crud_part" => $crudRoutePart,
                "view" => $viewGate,
                "edit" => $editGate,
                "delete" => $deleteGate,
            ];

            return $this->prepareListing($request, $actionData);
        }

        $columns = $this->makeColumns();

        SEOMeta::setTitle(trans('cruds.transaction.title') . " " . trans('global.list'));

        $countries = Country::orderBy("name", "ASC")->get()->toArray();

        if($request->route()->getName() == "admin.transactions.missing")
        {
            $publishers = User::whereType(User::PUBLISHER)->whereStatus(User::ACTIVE)->get();
            return view('template.admin.transaction_missing.index', compact('countries', 'publishers', 'columns'));
        }
        elseif($request->route()->getName() == "admin.transactions.missing.payment")
        {
            $publishers = User::whereType(User::PUBLISHER)->whereStatus(User::ACTIVE)->get();
            return view('template.admin.transaction_missing_payment.index', compact('countries', 'publishers', 'columns'));
        }
        else
        {
            return view('template.admin.transactions.index', compact('countries', 'columns'));
        }

    }

    private function prepareListing($request, $actionData)
    {
        $queryData = $this->query($request);

        $data = [];
        foreach ($queryData['query'] as $row)
        {
            $actionData['id'] = $row->id;
            $data[] = $this->prepareData($actionData, $row, $request);
        }

        // COMPILE DATA
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($queryData['total_data']),
            "recordsFiltered" => intval($queryData['total_filtered']),
            "data"            => $data
        );

        // RETURN RESPONSE
        return response()->json($json_data);
    }

    private function query(Request $request): Collection
    {
        // COLUMN NAMES
        $columns = $this->makeColumns();

        // REQUEST VARIABLES
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        // DEFINE
        $customQuery = Transaction::with('advertiser:id,sid,name')->select("*");

        if($request->input("payment_id"))
        {
            $payment = PaymentHistory::where('id', $request->input("payment_id"))->first();
            $customQuery = $customQuery->whereIn('transaction_id', $payment->transaction_idz);

            if($request->input("r_name") == "release")
            {
                $customQuery = $customQuery->where('payment_status', Transaction::PAYMENT_STATUS_RELEASE);
            }
            elseif($request->input("r_name") == "paid")
            {
                $customQuery = $customQuery->where('payment_status', Transaction::PAYMENT_STATUS_RELEASE_PAYMENT);
            }
        }

        if($request->input('route_name') == "admin.transactions.missing")
            $customQuery = $customQuery->whereNotIn('commission_status', [Transaction::STATUS_DELETED, Transaction::STATUS_DECLINED])->where(function($query) {
                $query->orWhere("website_id", '')->orWhereNull("website_id");
                $query->orWhere("publisher_id", '')->orWhereNull("publisher_id");
            });

        elseif($request->route()->getName() == "admin.transactions.missing.payment")
            $customQuery = $customQuery->where(function($query) {
                $query->orWhere("paid_to_publisher", Transaction::PAYMENT_STATUS_NOT_APPROVED);
                $query->orWhereNull("paid_to_publisher");
            });

        $source = $request->input('source');
        if(!empty($source))
            $customQuery = $customQuery->where("source", $source);

        $country = $request->input('country');
        if(!empty($country))
            $customQuery = $customQuery->where("customer_country", $country);

        // COUNT
        $totalData = $customQuery->count();

        $search_filter = trim($request->search_filter);

        // IF SEARCH
        if(!empty($search) && !empty($search_filter))
        {
            $customQuery = $customQuery->where("{$search_filter}", "LIKE","%{$search}%");
        }
        elseif ($request->route()->getName() == "admin.transactions.missing.payment")
        {
            $customQuery = $customQuery->where(function($query) use ($search) {
                $query->orWhere('transaction_id','LIKE',"%{$search}%")
                    ->orWhere('advertiser_name','LIKE',"%{$search}%")
                    ->orWhere('transaction_date','LIKE',"%{$search}%")
                    ->orWhere('commission_status','LIKE',"%{$search}%")
                    ->orWhere('received_commission_amount_currency','LIKE',"%{$search}%")
                    ->orWhere('order_ref','LIKE',"%{$search}%")
                    ->orWhere('source','LIKE',"%{$search}%");
            });
        }

        // THIS QUERY ONLY COUNT
        $totalFilteredQuery = $customQuery;

        // THIS QUERY GET RECORDS
        $query = $customQuery;

        // TOTAL FILTERED COUNT
        $totalFiltered = $totalFilteredQuery->count();

        // LIMIT AND OFFSET AND ORDER BY
        $query = $query->offset(intval($start))
            ->limit(intval($limit))
            ->orderBy($order,$dir)
            ->get();

        return collect([
            'total_filtered' => $totalFiltered,
            'total_data' => $totalData,
            'query' => $query,
        ]);
    }

    private function prepareData($actionData, $row, $request): array
    {
        $action = $this->prepareAction($actionData);

        // PUBLISHER ID
        $nestedData['DT_RowId'] = $row->id;

        // ADVERTISER ID
        $tid = $row->transaction_id ?? "-";
        $tid = new HtmlString("<div class='text-center'>{$tid}</div>");
        $nestedData['transaction_id'] = $tid->toHtml();

        if($request->input('route_name') == "admin.transactions.missing")
        {
            $id = $row->id;
            // ADVERTISER ID
            $nestedData['assign'] = '
                <a class="btn btn-primary btn-xs" href="javascript:void(0)" onclick="openModal(`'.$id.'`)" data-toggle="modal" data-target="#missing-modal">
                    Assign
                  </a>
            ';
        }

        // ADVERTISER ID
        $nestedData['advertiser_name'] = $row->advertiser_name ?? "-";

        // ADVERTISER ID
        $transaction_date = Carbon::parse($row->transaction_date)->format("Y-m-d H:i:s a");
        $transaction_date = new HtmlString("<div class='text-center'>{$transaction_date}</div>");
        $nestedData['transaction_date'] = $transaction_date->toHtml();

        // ADVERTISER ID
        $commission_status = ucwords($row->commission_status ?? "-");
        $commission_status = new HtmlString("<div class='text-center'>{$commission_status}</div>");
        $nestedData['commission_status'] = $commission_status->toHtml();

        $payment_status = "N/A";

        if($row->payment_status == Transaction::PAYMENT_STATUS_CONFIRM)
        {
            $payment_status = Transaction::STATUS_PAID;
        }
        elseif($row->payment_status == Transaction::PAYMENT_STATUS_REJECT)
        {
            $payment_status = Transaction::STATUS_DECLINED;
        }
        elseif($row->payment_status == Transaction::PAYMENT_STATUS_RELEASE)
        {
            $payment_status = Transaction::STATUS_PENDING_PAID;
        }
        elseif($row->payment_status == Transaction::PAYMENT_STATUS_RELEASE_PAYMENT)
        {
            $payment_status = Transaction::STATUS_PAID;
        }

        $payment_status = ucwords(str_replace('_', ' ', $payment_status));
        $payment_status = new HtmlString("<div class='text-center'>{$payment_status}</div>");
        $nestedData['payment_status'] = $payment_status->toHtml();

        // ADVERTISER ID
        $customer_country = $row->customer_country ?? "-";
        $customer_country = new HtmlString("<div class='text-center'>{$customer_country}</div>");
        $nestedData['customer_country'] = $customer_country->toHtml();

        // ADVERTISER ID
        $advertiser_country = $row->advertiser_country ?? "-";
        $advertiser_country = new HtmlString("<div class='text-center'>{$advertiser_country}</div>");
        $nestedData['advertiser_country'] = $advertiser_country->toHtml();

        // ADVERTISER ID
        $commission_amount = $row->commission_amount ?? "-";
        $commission_amount = new HtmlString("<div class='text-center'>{$commission_amount}</div>");
        $nestedData['commission_amount'] = $commission_amount->toHtml();

        // ADVERTISER ID
        $commission_amount_currency = $row->commission_amount_currency ?? "-";
        $commission_amount_currency = new HtmlString("<div class='text-center'>{$commission_amount_currency}</div>");
        $nestedData['commission_amount_currency'] = $commission_amount_currency->toHtml();

        // ADVERTISER ID
        $sale_amount = $row->sale_amount ?? "-";
        $sale_amount = new HtmlString("<div class='text-center'>{$sale_amount}</div>");
        $nestedData['sale_amount'] = $sale_amount->toHtml();

        // ADVERTISER ID
        $sale_amount = $row->received_commission_amount ?? "-";
        $sale_amount = new HtmlString("<div class='text-center'>{$sale_amount}</div>");
        $nestedData['received_commission_amount'] = $sale_amount->toHtml();

        // ADVERTISER ID
        $sale_amount = $row->received_sale_amount ?? "-";
        $sale_amount = new HtmlString("<div class='text-center'>{$sale_amount}</div>");
        $nestedData['received_sale_amount'] = $sale_amount->toHtml();

        // ADVERTISER ID
        $sale_amount_currency = $row->sale_amount_currency ?? "-";
        $sale_amount_currency = new HtmlString("<div class='text-center'>{$sale_amount_currency}</div>");
        $nestedData['sale_amount_currency'] = $sale_amount_currency->toHtml();

        // ADVERTISER ID
        $received_commission_amount_currency = $row->received_commission_amount_currency ?? "-";
        $received_commission_amount_currency = new HtmlString("<div class='text-center'>{$received_commission_amount_currency}</div>");
        $nestedData['received_commission_amount_currency'] = $received_commission_amount_currency->toHtml();

        // ADVERTISER ID
        $source = ucwords($row->source ?? "-");
        $source = new HtmlString("<div class='text-center'>{$source}</div>");
        $nestedData['source'] = $source->toHtml();

        // ACTIONS
        $nestedData['action'] = $action;

        return $nestedData;
    }

    private function makeColumns(): array
    {
        return [
            "transaction_id",
            "advertiser_name",
            "campaign_name",
            "transaction_date",
            "commission_status",
            "payment_status",
            "customer_country",
            "advertiser_country",
            "commission_amount",
            "commission_amount_currency",
            "sale_amount",
            "received_commission_amount",
            "received_sale_amount",
            "sale_amount_currency",
            "received_commission_amount_currency",
            "order_ref",
            "source",
            "id"
        ];
    }

}
