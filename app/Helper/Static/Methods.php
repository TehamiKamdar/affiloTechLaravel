<?php

namespace App\Helper\Static;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Jobs\Admitad\MultiStatusCheckJob;
use App\Models\AdvertiserApply;
use App\Models\City;
use App\Models\Clicks;
use App\Models\Country;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\History;
use App\Models\Notification;
use App\Models\State;
use App\Models\Transaction;
use App\Models\TransactionConversation;
use App\Models\User;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Predis\Client;
use Spatie\SlackAlerts\Facades\SlackAlert;

class Methods
{
    public static function staticAsset($path): string
    {
        return asset($path, env("HTTP_SECURE"));
    }
    public static function staticMainAsset($path): string
    {
        return env("APP_URL") . $path;
    }

    public static function extAsset($path): string
    {
        return env("HTTP_EXT_URL") . $path;
    }

    public static function encrypt($val)
    {
        return Crypt::encryptString($val);
    }

    public static function decrypt($val)
    {
        return Crypt::decryptString($val);
    }

    public static function numberFormatShort($n, $precision = 1) {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } elseif ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n * 0.001, $precision);
            $suffix = 'K';
        } elseif ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n * 0.000001, $precision);
            $suffix = 'M';
        } elseif ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n * 0.000000001, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n * 0.000000000001, $precision);
            $suffix = 'T';
        }

        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }

        return $n_format . $suffix;
    }

    public static function returnPerGrowth($previousMonthSum, $currentMonthSum)
    {
        // Calculate the growth percentage
        if ($previousMonthSum != 0) {
            $growthPercentage = (($currentMonthSum - $previousMonthSum) / $previousMonthSum) * 100;
        } else {
            $growthPercentage = 0;
        }

        // Check if the growth is positive or negative
        if ($growthPercentage > 0) {
            $growthStatus = "up";
        } elseif ($growthPercentage < 0) {
            $growthStatus = "down";
        } else {
            $growthStatus = "-";
        }

        return [
            'percentage' => number_format($growthPercentage, 2) . "%",
            'growth' => $growthStatus
        ];

    }

    public static function getAdvertiserIDz($startDate, $endDate)
    {
        return Transaction::select('internal_advertiser_id')
            ->fetchPublisher(auth()->user())
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->groupBy("internal_advertiser_id")
            ->get()
            ->toArray();
    }

    public static function getCountryByID($id)
    {
        return Country::select(['id', 'name'])->where("id", $id)->first();
    }

    public static function getStateByID($id)
    {
        return State::select(['id', 'name'])->where("id", $id)->first();
    }

    public static function getCityByID($id)
    {
        return City::select(['id', 'name'])->where("id", $id)->first();
    }

    public static function productionCurrencyConverter($amount, $from, $date, $to = "USD")
    {
        $conversation = TransactionConversation::where([
                            "to" => $to,
                            "from" => $from,
                            "date" => $date
                        ])->first();

        if($conversation)
        {
            return $conversation->rate * $amount;
        }
        else
        {
            $key = env("EXCHANGE_RATES_API_KEY");

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/convert?to={$to}&from={$from}&amount={$amount}&date={$date}",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: text/plain",
                    "apikey: {$key}"
                ),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
            ));

            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                Log::error(curl_error($curl));
            }

            curl_close($curl);

            $response = json_decode($response, true);

            if(isset($response['info']['rate']))
            {
                $rate = $response['info']['rate'];
                TransactionConversation::updateOrCreate([
                    "to" => $to,
                    "from" => $from,
                    "date" => $date
                ],
                [
                    "actual_rate" => $rate,
                    "rate" => ($rate - ($rate * 0.05))
                ]);
            }

            return $response['result'] ?? null;
        }

    }

    public static function localCurrencyConverter($amount, $from, $date, $to = "USD")
    {
        return Currency::convert()
            ->from($from)
            ->to($to)
            ->amount($amount)
            ->date($date)
            ->get();
    }

    public static function returnAdvertiserQueue($source)
    {
        $queue = "default";
        if($source == Vars::ADMITAD)
            $queue = Vars::ADMITAD_ON_QUEUE;
        elseif ($source == Vars::AWIN)
            $queue = Vars::AWIN_ON_QUEUE;
        elseif ($source == Vars::IMPACT_RADIUS)
            $queue = Vars::IMPACT_RADIUS_ON_QUEUE;
        elseif ($source == Vars::RAKUTEN)
            $queue = Vars::RAKUTEN_ON_QUEUE;
        elseif ($source == Vars::TRADEDOUBLER)
            $queue = Vars::TRADEDOUBLER_ON_QUEUE;

        return $queue;
    }

    public static function customError($module, $exception)
    {
//        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        Log::error("MODULE NAME: {$module}");
//        Log::error($exception);
        if($module)
            Log::channel('error')->error("MODULE NAME: {$module}");
        Log::channel('error')->error($exception ?? '');

    }

    public static function customAdmitad($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("admitad_notification")->message("MODULE NAME: {$module}");
//        SlackAlert::to("admitad_notification")->message($exception ? $exception : '');
        if($module)
            Log::channel('admitad')->info("MODULE NAME: {$module}");
        Log::channel('admitad')->info($exception ?? '');
    }

    public static function customAwin($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("awin_notification")->message("MODULE NAME: {$module}");
//        SlackAlert::to("awin_notification")->message($exception ? $exception : '');
        if($module)
            Log::channel('awin')->info("MODULE NAME: {$module}");
        Log::channel('awin')->info($exception ?? '');
    }

    public static function customImpactRadius($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("impact_radius_notification")->message("MODULE NAME: {$module}");
//        SlackAlert::to("impact_radius_notification")->message($exception ? $exception : '');
        if($module)
            Log::channel('impact_radius')->info("MODULE NAME: {$module}");
        Log::channel('impact_radius')->info($exception ?? '');
    }

    public static function customPepperjam($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("pepperjam_notification")->message("MODULE NAME: {$module}");
//        SlackAlert::to("pepperjam_notification")->message($exception ? $exception : '');
        if($module)
            Log::channel('pepperjam')->info("MODULE NAME: {$module}");
        Log::channel('pepperjam')->info($exception ?? '');
    }

    public static function customRakuten($module, $exception)
    {
//        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("rakuten_notification")->message("MODULE NAME: {$module}");
//        SlackAlert::to("rakuten_notification")->message($exception ? $exception : '');
        if($module)
            Log::channel('rakuten')->info("MODULE NAME: {$module}");
        Log::channel('rakuten')->info($exception ? $exception : '');
    }

    public static function customTradedoubler($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("tradedoubler_notification")->message("MODULE NAME: {$module}");
//        SlackAlert::to("tradedoubler_notification")->message($exception ? $exception : '');
        if($module)
            Log::channel('tradedoubler')->info("MODULE NAME: {$module}");
        Log::channel('tradedoubler')->info($exception ?? '');
    }

    public static function customDefault($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("default")->message("MODULE NAME: {$module}");
//        SlackAlert::to("default")->message($exception ? $exception : '');
        if($module)
            Log::channel('default')->info("MODULE NAME: {$module}");
        Log::channel('default')->info($exception ?? '');
    }

    public static function customWarning($module, $data)
    {
        $data = is_array($data) || is_object($data) ? json_encode($data) : $data;
//        if($module)
//            SlackAlert::to("warning")->message("MODULE NAME: {$module}");
//        SlackAlert::to("warning")->message($data ? $data : '');
        if($module)
            Log::channel('warning')->info("MODULE NAME: {$module}");
        Log::channel('warning')->info($data ?? '');
    }

    public static function customLinkGenerate($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("generate_link")->message("MODULE NAME: {$module}");
//        SlackAlert::to("generate_link")->message($exception ? $exception : '');
        if($module)
            Log::channel('generate_link')->info("MODULE NAME: {$module}");
        Log::channel('generate_link')->info($exception ? $exception : '');

    }

    public static function customDeepLinkGenerate($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("generate_depp_link")->message("MODULE NAME: {$module}");
//        SlackAlert::to("generate_depp_link")->message($exception ? $exception : '');
        if($module)
            Log::channel('generate_depp_link')->info("MODULE NAME: {$module}");
        Log::channel('generate_depp_link')->info($exception ? $exception : '');
    }

    public static function trackingClicks($module, $exception)
    {
        $exception = is_array($exception) || is_object($exception) ? json_encode($exception) : $exception;
//        if($module)
//            SlackAlert::to("generate_depp_link")->message("MODULE NAME: {$module}");
//        SlackAlert::to("generate_depp_link")->message($exception ? $exception : '');
        if($module)
            Log::channel('tracking_clicks')->info("MODULE NAME: {$module}");
        Log::channel('tracking_clicks')->info($exception ? $exception : '');
    }

    public static function newNotificationCount()
    {
        return Notification::where('is_new', Vars::NEW_NOTIFICATION)->where('publisher_id', auth()->user()->id)->count();
    }

    public static function getNotifications()
    {
        return Notification::where('publisher_id', auth()->user()->id)->orderBy('created_at', 'DESC')->orderBy('is_new', 'DESC')->take(10)->get();
    }

    public static function getDatabaseJobsAddTime($time = 60)
    {
        $numJobs = DB::table('jobs')->where('queue', Vars::SEND_EMAIL)->count();
        return $numJobs * $time;
    }

    public static function isImageShowable($path)
    {
        $logo = Methods::staticAsset("img/placeholder-profile.png");

        $path = strtok($path, '?');

        if($path && file_exists($path))
        {
            $data = @getimagesize(Methods::staticAsset($path));
            $isImageView = $data ? true : false;
            if($isImageView)
                $logo = self::staticAsset($path);
        }

        return $logo;
    }

    public static function getQueueJobsWithCondition(): array|object|null
    {
        $redis = new Client();
        $jobs = $redis->keys("*");
        $result = array_filter($jobs, function ($job) {
            return Methods::getCurrentsJobsCheck($job);
        });
        $redis->disconnect();
        return $result;
    }

    public static function getCurrentsJobsCheck($job)
    {
        return (stripos($job, Vars::AWIN) !== false || stripos($job, Vars::IMPACT_RADIUS) !== false ||
            stripos($job, Vars::RAKUTEN) !== false || stripos($job, Vars::TRADEDOUBLER) !== false ||
            stripos($job, Vars::ADMITAD) !== false || stripos($job, Vars::FETCH_MEDIA) !== false);
    }

    public static function tryBodyFetchDaily($jobID, $isStatusChange, $type = null)
    {
        if($type == "history")
        {
            History::where("id", $jobID)->update([
                'status' => Vars::JOB_STATUS_COMPLETE,
                'is_processing' => Vars::JOB_ACTIVE
            ]);
        }
        elseif($type == "clicks")
        {
            Clicks::where("id", $jobID)->update([
                'status' => Vars::JOB_STATUS_COMPLETE,
                'is_processing' => Vars::JOB_ACTIVE
            ]);
        }
        else
        {

            FetchDailyData::where("id", $jobID)->update([
                'status' => Vars::JOB_STATUS_COMPLETE,
                'is_processing' => Vars::JOB_ACTIVE
            ]);

            if($isStatusChange)
            {
                FetchDailyData::where('status', Vars::JOB_STATUS_IN_PROCESS)->update([
                    'date' => now()->addSeconds(20)->format(Vars::CUSTOM_DATE_FORMAT_2)
                ]);
            }

        }
    }

    public static function catchBodyFetchDaily($module, $exception, $jobID, $type = null)
    {

        Methods::customError($module, $exception);
        $errorCode = $exception->getCode();
        $errorMessage = $exception->getMessage();
        $retryDateTime = now()->addHours(1)->format(Vars::CUSTOM_DATE_FORMAT_2);

        if($type == "history")
        {
            History::where("id", $jobID)->update([
                "error_code" => $errorCode,
                "error_message" => $errorMessage,
                'date' => $retryDateTime,
                'is_processing' => Vars::JOB_ERROR
            ]);
        }
        elseif($type == "clicks")
        {
            Clicks::where("id", $jobID)->update([
                "error_code" => $errorCode,
                "error_message" => $errorMessage,
                'date' => $retryDateTime,
                'is_processing' => Vars::JOB_ERROR
            ]);
        }
        else
        {
            FetchDailyData::where("id", $jobID)->update([
                "error_code" => $errorCode,
                "error_message" => $errorMessage,
                'date' => $retryDateTime,
                'is_processing' => Vars::JOB_ERROR
            ]);
        }
    }

    public static function getDocUser(Request $request)
    {
        $header = $request->header('token');
        return User::where("api_token", $header)->first();
    }

    public static function checkTokenAlreadyExist($token)
    {
        return User::where("api_token", $token)->count();
    }

    public static function parseAmountToUSD($from, $amount, $transactionDate)
    {
        $date = Carbon::parse($transactionDate)->format("Y-m-d");
        if(env("APP_ENV") == "production")
        {
            return Methods::productionCurrencyConverter($amount, $from, $date);
        }
        else
        {
            return Methods::localCurrencyConverter($amount, $from, $date);
        }
    }

    public static function isEnglish($str)
    {
        if (strlen($str) != strlen(utf8_decode($str))) {
            return false;
        } else {
            return true;
        }
    }

    public static function subMonths($no = 1)
    {
        return now()->startOfMonth()->subMonths($no);
    }

    public static function getAdminAuthorizeCheck()
    {
        return auth()->check() && (in_array(auth()->user()->type, [User::SUPER_ADMIN, User::ADMIN, User::STAFF, User::INTERN]));
    }
}
