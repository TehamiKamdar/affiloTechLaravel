<?php

namespace App\Traits\Provider\Payment;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Admitad\TransactionJob;
use App\Models\AdvertiserConfig;
use App\Models\FetchDailyData;
use Carbon\Carbon;
use Plugins\Admitad\AdmitadTrait;

trait Rakuten
{

    public function handleRakuten($months = 1)
    {
        $date = Carbon::now();
        $end = $date->format("Ymd");
        $begin = $date->subMonths(12)->format("Ymd");

        $configs = $this->getRakutenConfigData();
        $token = $configs["security_token"];
        $source = Vars::RAKUTEN;

        FetchDailyData::updateOrCreate([
            "path" => "PaymentStepOneJob",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
            "start_date" => $begin,
            "end_date" => $end,
            "queue" => Vars::RAKUTEN_ON_QUEUE,
            "source" => $source,
            "type" => Vars::PAYMENT
        ], [
            "name" => "Rakuten Payment Step One Job",
            "payload" => json_encode(['start' => $begin, 'end' => $end, 'security_token' => $token]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "sort" => $this->setSortingFetchDailyData($source),
        ]);

        $this->paymentStatusUpdate($source, $months);

    }

    private function getRakutenConfigData(): array
    {
        $configs = AdvertiserConfig::select(["key", "value"])->where("name", Vars::RAKUTEN)->get()->pluck("value", "key")->toArray();

        $scope = $configs["scope"] ?? null;
        $type = $configs["grant_type"] ?? null;
        $token = $configs["token"] ?? null;
        $security_token = $configs["security_token"] ?? null;

        return [
            'scope' => $scope,
            'type' => $type,
            'token' => $token,
            'security_token' => $security_token
        ];
    }

}
