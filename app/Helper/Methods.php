<?php
namespace App\Helper;

use App\Models\FetchDailyData;
use App\Models\GenerateExportRequest;
use App\Models\History;
use App\Models\Mix;
use App\Models\Website;
use Illuminate\Support\Facades\Log;

class Methods {
    public static function staticAsset($path) : string {
        return asset($path, env("NTP_SECURE"));
    }

    public static function redirectTo($path) : string {
        $path = str_replace(
            array("http://", "https://"),
            '',
            $path
        );

        $http = env("HTTP_SECURE") ? "https://" : "http://";
        return "{$http}{$path}";
    }

    public static function obfuscateEmail($email) {
        $parts = explode("@", $email);
        $name = $parts[0];
        $domain = $parts[1];
        $obfuscatedName = substr($name, 0, 2) . str_repeat("*", strlen($name) - 2);
        return $obfuscatedName . "@" . $domain;
    }

    public static function numberFormatShort($n, $precision = 1) {
        $thresholds = array(
            1 => '',
            1000 => "K",
            1000000 => "M",
            1000000000 => "B",
            1000000000000 => "T"
        );

        foreach (array_reverse($thresholds, true) as $divisor => $suffix) {
            if ($n >= $divisor) {
                $n_format = number_format($n / $divisor, $precision);
                if ($precision > 0) {
                    $dotzero = "." . str_repeat("0", $precision);
                    $n_format = str_replace($dotzero, '', $n_format);
                }
                return $n_format . $suffix;
            }
        }
        return number_format($n, $precision);
    }

    public static function customError($module, $exception) {
        if ($module) {
            Log::error("MODULE_NAME: {$module}");
        }
        Log::error($exception ?? '');
    }

    public static function getInitials($name) {
        return strtoupper(substr($name, 0, 2));
    }

    public static function getColorFromName($name) {
        // Get the first 6 hex chars of md5 hash to use as color
        return substr(md5($name), 0, 6); // e.g., "f4c242"
    }

    public static function tryBodyFetchDaily($jobID, $isStatusChange, $isHistory = false, $isExported = false) {
        if ($isHistory) {
            History::where("id", $jobID)->update(array(
                "status" => Vars::JOB_STATUS_COMPLETE,
                "is_processing" => Vars::JOB_ACTIVE
            ));
        } elseif ($isExported) {
            GenerateExportRequest::where("id", $jobID)->update(array(
                "status" => Vars::JOB_STATUS_COMPLETE,
                "is_processing" => Vars::JOB_ACTIVE
            ));
        } else {
            FetchDailyData::where("id", $jobID)->update(array(
                "status" => Vars::JOB_STATUS_COMPLETE,
                "is_processing" => Vars::JOB_ACTIVE
            ));

            if ($isStatusChange) {
                FetchDailyData::where("status", Vars::JOB_STATUS_IN_PROCESS)->update(array(
                    "date" => now()->addSeconds(20)->format(Vars::CUSTOM_DATE_FORMAT_2)
                ));
            }
        }
    }

    public static function catchBodyFetchDaily($module, $exception, $jobID, $isHistory = false, $isExported = false) {
        Methods::customError($module, $exception);
        $errorCode = $exception->getCode();
        $errorMessage = $exception->getMessage();
        $retryDateTime = now()->addHours(1)->format(Vars::CUSTOM_DATE_FORMAT_2);

        if ($isHistory) {
            History::where("id", $jobID)->update(array(
                "error_code" => $errorCode,
                "error_message" => $errorMessage,
                "date" => $retryDateTime,
                "is_processing" => Vars::JOB_ERROR
            ));
        } elseif ($isExported) {
            GenerateExportRequest::where("id", $jobID)->update(array(
                "error_code" => $errorCode,
                "error_message" => $errorMessage,
                "date" => $retryDateTime,
                "is_processing" => Vars::JOB_ERROR
            ));
        } else {
            FetchDailyData::where("id", $jobID)->update(array(
                "error_code" => $errorCode,
                "error_message" => $errorMessage,
                "date" => $retryDateTime,
                "is_processing" => Vars::JOB_ERROR
            ));
        }
    }

    public static function isAdminActiveRoute($pattern) {
        return \Illuminate\Support\Str::is($pattern, request()->route()->getName()) || request()->is($pattern)
            ? "active" : '';
    }

    public static function isAdminActiveWithOpenRoute($pattern) {
        return \Illuminate\Support\Str::is($pattern, request()->route()->getName()) || request()->is($pattern)
            ? "active pcoded-triangle" : '';
    }

    public static function getCategories($ids = array(), $onlyName = false, $onlyId = false) {
        $collect = collect(array(
            array("id" => 1, "name" => "Traverel"),
            array("id" => 2, "name" => "Shopping"),
            array("id" => 3, "name" => "Entertainment"),
            array("id" => 4, "name" => "Lifestyle"),
            array("id" => 5, "name" => "Business"),
            array("id" => 6, "name" => "Technology"),
            array("id" => 7, "name" => "Health & Beauty"),
            array("id" => 8, "name" => "DIY & Crafting"),
            array("id" => 9, "name" => "Dating & Romance"),
            array("id" => 10, "name" => "News & Blogging"),
            array("id" => 11, "name" => "Other")
        ));

        return $collect->when(count($ids), function ($q) use($ids) {
            return $q->whereIn("id", $ids);
        })->when($onlyName, function ($q) {
            return $q->pluck("name");
        })->when($onlyId, function ($q) {
            return $q->pluck("id");
        })->toArray();
    }

  public static function getWebsiteType($ids = [], $onlyName = false, $onlyId = false) {
    // Ensure $ids is an array
    $ids = is_array($ids) ? $ids : [];

    $collect = collect([
        ["id" => 1, "name" => "Coupon / Deals"],
        ["id" => 2, "name" => "Content / Blogs / Reviews"],
        ["id" => 3, "name" => "Sub-Networks & Others"]
    ]);

    return $collect->when(!empty($ids), function ($q) use ($ids) {
        return $q->whereIn("id", $ids);
    })->when($onlyName, function ($q) {
        return $q->pluck("name");
    })->when($onlyId, function ($q) {
        return $q->pluck("id");
    })->toArray();
}


    public static function generateWebsiteBarcodeNumber() {
        $number = mt_rand(10000000, 99999999);
        if (self::widExists($number)) {
            return self::generateWebsiteBarcodeNumber();
        }
        return $number;
    }

    public static function widExists($number) {
        return Website::whereWid($number)->exists();
    }
}
