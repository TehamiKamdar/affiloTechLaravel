<?php
namespace App\Http\Controllers\Publisher;

use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Billing;
use App\Models\Website;
use App\Models\Mediakit;
use Illuminate\Http\Request;
use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Publisher\WebsiteRequest;
use App\Http\Requests\Publisher\UpdateWebsiteRequest;
use App\Services\Publisher\Setting\PaymentNBillingService;
use App\Services\Publisher\Setting\BasicInformationService;
use App\Services\Publisher\Setting\LoginInformationService;
use App\Services\Publisher\Setting\CompanyInformationService;
use App\Services\Publisher\Setting\WebsiteInformationService;

class SettingController extends BaseController
{
    // Basic Information
    public function getBasicInformation(Request $request, BasicInformationService $service)
    {
        return $service->init($request);
    }

    public function storeBasicInformation(Request $request, BasicInformationService $service)
    {
        return $service->store($request);
    }

    public function actionMediaKitsDelete(Mediakit $mediakit, BasicInformationService $service)
    {
        return $service->deleteMediaKit($mediakit);
    }

    // Company Information
    public function getCompanyInformation(Request $request, CompanyInformationService $service)
    {
        return $service->init($request);
    }

    public function storeCompanyInformation(Request $request, CompanyInformationService $service)
    {
        return $service->store($request);
    }

    // Website Information
    public function getWebsites(Request $request, WebsiteInformationService $service)
    {
        return $service->init($request);
    }

    public function getWebsiteById(Website $website, WebsiteInformationService $service)
    {
        return $service->getWebsiteById($website);
    }

    public function updateWebsite(UpdateWebsiteRequest $request, WebsiteInformationService $service)
    {
        return $service->updateWebsite($request);
    }

    public function verifyWebsite(Request $request, WebsiteInformationService $service)
    {
        return $service->verifyWebsite($request);
    }

    public function setWebsite(Request $request, Website $website, WebsiteInformationService $service)
    {
        return $service->setWebsite($request, $website);
    }

    // Payment & Billing
    public function getPaymentNBilling(Request $request, PaymentNBillingService $service)
    {
        return $service->init($request);
    }
    // LocationController.php
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->get(['id', 'name']);
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = City::where('state_id', $stateId)->get(['id', 'name']);
        return response()->json($cities);
    }

    public function storePayment(Request $request, PaymentNBillingService $service)
    {
        $user = auth()->user();

           $payment = PaymentSetting::where('user_id', $user->id)->first();
           if($payment){
            $payment->update([
                "payment_frequency"=>$request->payment_frequency,
                "payment_method"=>$request->payment_method,
                "user_id"=>$user->id,
                "website_id" => $user->active_website_id,
      "payment_threshold" => $request->payment_threshold,
      "bank_location" => $request->bank_location,
      "account_holder_name" =>$request->account_holder_name,
      "bank_account_number" => $request->bank_account_number,
      "bank_code" => $request->bank_code,
      "account_type" => $request->account_type,
      "paypal_country" => $request->paypal_country,
      "paypal_holder_name" => $request->paypal_holder_name,
      "paypal_email" => $request->paypal_email,
      "payoneer_holder_name" => $request->payoneer_holder_name,
      "payoneer_email" => $request->payoneer_email,
                ]);
        }else{
             $payment= PaymentSetting::create([
                "payment_frequency"=>$request->payment_frequency,
                "payment_method"=>$request->payment_method,
                "user_id"=>$user->id,
                "website_id" => $user->active_website_id,
      "payment_threshold" => $request->payment_threshold,
      "bank_location" => $request->bank_location,
      "account_holder_name" =>$request->account_holder_name,
      "bank_account_number" => $request->bank_account_number,
      "bank_code" => $request->bank_code,
      "account_type" => $request->account_type,
      "paypal_country" => $request->paypal_country,
      "paypal_holder_name" => $request->paypal_holder_name,
      "paypal_email" => $request->paypal_email,
      "payoneer_holder_name" => $request->payoneer_holder_name,
      "payoneer_email" => $request->payoneer_email,
                ]);
        }

         return redirect()->back()->with(['payment'=>$payment]);
    }

     public function storePaymentNBilling(Request $request, PaymentNBillingService $service)
    {

        $user = auth()->user();

         $billing = Billing::where('user_id',$user->id)->first();

        if($billing){
            $billing->update([
                "name"=>$request->fname,
            "phone"=>$request->billing_phone,
            "address"=>$request->billing_address,
            "country"=>$request->country,
            "state"=>$request->state,
            "city"=>$request->city,
            "company_registration_no"=>$request->company_registration,
            "tax_vat_no"=>$request->tax_number
                ]);
        }else{
             $billing = Billing::create([
            "user_id" => $user->id,
            "name"=>$request->fname,
            "phone"=>$request->billing_phone,
            "address"=>$request->billing_address,
            "country"=>$request->country,
            "state"=>$request->state,
            "city"=>$request->city,
            "company_registration_no"=>$request->company_registration,
            "tax_vat_no"=>$request->tax_number
            ]);
        }




            return redirect()->back()->with(['billing'=>$billing]);
    }

    // Login Information
    public function getLoginInformation(Request $request, LoginInformationService $service)
    {
        return $service->init($request);
    }

    public function changeEmailUpdate(Request $request, LoginInformationService $service)
    {
        return $service->changeUpdateEmail($request);
    }

    public function verifyEmail($url, LoginInformationService $service)
    {
        return $service->verifyEmail($url);
    }

    public function changePasswordUpdate(Request $request, LoginInformationService $service)
    {
        return $service->changePassword($request);
    }

    // Invoices
    public function getInvoices(Request $request, LoginInformationService $service)
    {
        $title = "Invoices";

        seo()
            ->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Profile',
            $title
        ];

        return $this->returnComingSoonView($title, $headings);
    }
}
