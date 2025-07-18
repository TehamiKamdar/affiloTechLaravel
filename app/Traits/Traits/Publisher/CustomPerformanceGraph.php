<?php

namespace App\Traits\Publisher;

use App\Helper\GlobalData;
use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait CustomPerformanceGraph
{

    public function getCustomPerformanceOverview(Request $request)
    {
        $toDate = Carbon::parse($request->start_date);
        $fromDate = Carbon::parse($request->end_date);

        $days = $toDate->diffInDays($fromDate);
        $months = $toDate->diffInMonths($fromDate);
        $years = $toDate->diffInYears($fromDate);

        $toDate = $toDate->format("Y-m-d 00:00:00");
        $fromDate = $fromDate->format("Y-m-d 23:59:59");

        $type = "day";
        $labels = $this->getCustomDays($toDate, $fromDate);
        if($years > 0)
        {
            $labels = $this->getCustomYears($toDate, $fromDate);;
            $type = "year";
        }
        elseif($months > 0)
        {
            $labels = $this->getCustomMonths($toDate, $fromDate);
            $type = "month";
        }

        return [
            "commission" => $this->setCustomPerformanceCommission($toDate, $fromDate, $type),
            "transaction" => $this->setCustomPerformanceTransaction($toDate, $fromDate, $type),
            "sale" => $this->setCustomPerformanceSale($toDate, $fromDate, $type),
            "extra" => [
                "labels" => $labels
            ]
        ];
    }

    public function getCustomAccountSummary($advertiserData)
    {
        $user = auth()->user();

        $accountSummary = AdvertiserPublisher::select('status', DB::raw('count(*) as total'))
            ->where('publisher_id', $user->id)->where("website_id", $user->active_website_id)
            ->groupBy("status")
            ->get()->toArray();

        if(!in_array('joined', array_column($accountSummary, 'status')))
        {
            $accountSummary[] = [
                'status' => 'joined',
                'total' => 0
            ];
        }
        if(!in_array('pending', array_column($accountSummary, 'status')))
        {
            $accountSummary[] = [
                'status' => 'pending',
                'total' => 0
            ];
        }
        if(!in_array('hold', array_column($accountSummary, 'status')))
        {
            $accountSummary[] = [
                'status' => 'hold',
                'total' => 0
            ];
        }
        if(!in_array('rejected', array_column($accountSummary, 'status')))
        {
            $accountSummary[] = [
                'status' => 'rejected',
                'total' => 0
            ];
        }
        $accountSummary[] = [
            'status' => 'not joined',
            'total' => $advertiserData['count'] - (array_sum(array_column($accountSummary, 'total')))
        ];

        asort($accountSummary);

        return $accountSummary;
    }

    public function customEarningOverview($earningOverviews)
    {
        $earningOverviewsList = [];
        foreach ($earningOverviews as $earningOverview)
        {
            if($earningOverview['commission_status'] == Transaction::STATUS_PENDING) {
                $earningOverview = array_merge($earningOverview, $this->getCustomCommissionPercentage(Transaction::STATUS_PENDING));
            }
            elseif($earningOverview['commission_status'] == Transaction::STATUS_APPROVED) {
                $earningOverview = array_merge($earningOverview, $this->getCustomCommissionPercentage(Transaction::STATUS_APPROVED));
            }
            elseif($earningOverview['commission_status'] == Transaction::STATUS_DECLINED) {
                $earningOverview = array_merge($earningOverview, $this->getCustomCommissionPercentage(Transaction::STATUS_DECLINED));
            }
            $earningOverviewsList[$earningOverview['commission_status']] = $earningOverview;
        }
        return $earningOverviewsList;
    }

    private function getCustomDays($toDate, $fromDate)
    {
        $begin = new \DateTime($toDate);
        $end = new \DateTime($fromDate);

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        $days = [];
        foreach ($period as $dt) {
            $days[] = $dt->format("d");
        }
        return $days;
    }

    private function getCustomMonths($toDate, $fromDate)
    {
        $begin = new \DateTime($toDate);
        $end = new \DateTime($fromDate);

        $interval = \DateInterval::createFromDateString('1 Month');
        $period = new \DatePeriod($begin, $interval, $end);

        $months = [];
        foreach ($period as $dt) {
            $months[] = $dt->format("M");
        }
        return $months;
    }

    private function getCustomYears($toDate, $fromDate)
    {
        $begin = new \DateTime($toDate);
        $end = new \DateTime($fromDate);

        $interval = \DateInterval::createFromDateString('1 Year');
        $period = new \DatePeriod($begin, $interval, $end);

        $months = [];
        foreach ($period as $dt) {
            $months[] = $dt->format("Y");
        }
        return $months;
    }

}
