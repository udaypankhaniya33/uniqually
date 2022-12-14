<?php

namespace App\Http\Controllers\API\User;

use App\AppSetting;
use App\Http\Controllers\API\BaseController;
use App\Jobs\SendOnboardingEmail;
use App\Jobs\SendOrderConfirmation;
use App\Order;
use App\OrderAddon;
use App\Package;
use App\PackageAddon;
use App\PayPalFailedResponse;
use App\PayPalSuccessResponse;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use HttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class OrdersController extends BaseController
{

    private $payPalAuthToken ;
    private $httpClient;
    private $payPalBaseUrl = 'https://api.paypal.com/';
    private $payPalError;
    private $payPalSuccess;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $payPalConfig = Config::get('paypal');
        if($payPalConfig['settings']['mode'] === 'sandbox'){
            $clientId = $payPalConfig['sandbox_client_id'];
            $clientSecret = $payPalConfig['sandbox_secret'];
        }else{
            $clientId = $payPalConfig['live_client_id'];
            $clientSecret = $payPalConfig['live_secret'];
        }
       try{
           $this->httpClient = new \GuzzleHttp\Client();
           $authRequest = $this->httpClient->post( $this->payPalBaseUrl.'v1/oauth2/token',
               [
                   'auth' => [$clientId, $clientSecret],
                   'form_params' => ['grant_type' => 'client_credentials'],
                   'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
               ]);
           $response = $authRequest->getBody()->getContents();
           $response = json_decode($response);
           $this->payPalAuthToken = 'Bearer '.$response->access_token;

       }catch (GuzzleException $exception){
           logger($exception);
       }
    }

    /**
     * Submit order
     * -----------------------------------------------------------------------------------------------------------------
     * @param request()
     * @return \Illuminate\Http\Response
     */
    public function store() {
        $currentUser = Auth::user();
        //$location = Location::get(request()->ip());
        $appSettings = AppSetting::where('key', 'is_paypal_after_submission')->first();
        $isPayPalEnabled = $appSettings->value == 1 ? false : true;
        $isAnnual = request()->has('is_annual') ? request('is_annual') : false;
        $expense = request()->has('expense') ? request('expense') : null;
        $packageAddons = request()->has('package_addons') ? request('package_addons') : null;

        if($isPayPalEnabled){
            if(request()->has('base_package_id', 'paypal_order_id', 'paypal_payer_id', 'paypal_auth_token')){
                if($this->authorizePayment(request('paypal_order_id'), null)){
                    $payPalOrderAuthorizedId = $this->payPalSuccess->purchase_units[0]->payments->authorizations[0]->id;
                    if($this->capturePayment($payPalOrderAuthorizedId)){
                        return $this->order(
                            request('base_package_id'),
                            $isAnnual,
                            $expense,
                            $currentUser,
                            $packageAddons,
                            $isPayPalEnabled);
                    }else{
                        return $this->sendError($this->payPalError->details[0]->issue, [
                            'error' => [
                                'desc' => $this->payPalError->details[0]->description
                            ]
                        ], 422);
                    }
                }else{
                    return $this->sendError($this->payPalError->details[0]->issue, [
                        'error' => [
                            'desc' => $this->payPalError->details[0]->description
                        ]
                    ], 422);
                }
            }else{
                return $this->sendError('Please provide valid data', [
                    'error' => [
                        'base_package_id' => 'Base package is required to initiate order',
                        'paypal_payer_id' => 'PayPal payer id is required',
                        'paypal_auth_token' => 'PayPal authorization token is required',
                        'paypal_order_id' => 'PayPal order id is required'
                    ]
                ], 422);
            }
        }else{
            if(request()->has('base_package_id')){
                return $this->order(
                    request('base_package_id'),
                    $isAnnual,
                    $expense,
                    $currentUser,
                    $packageAddons,
                    $isPayPalEnabled);
            }else{
                return $this->sendError('Please provide valid data', [
                    'error' => [
                        'base_package_id' => 'Base package is required to initiate order'
                    ]
                ], 422);
            }
        }
    }

    private function order($basePackageId, $isAnnual = false, $expense = null, $currentUser, $packageAddons = null,
                           $isPaypalEnabled = false) {
        $discount = 0;
        $orderValue = 0;
        $orderSummery = [];
        $orderValue = 0;
        $quantity = 1;
        $itemEach = 0;
        $selectedPackage = Package::with('packageCategory')->find($basePackageId);
        $itemEach = $selectedPackage->discounted_price;
        if ($isAnnual && $expense !== null && $selectedPackage->packageCategory->title === 'Bookkeeping') {
            $discount = (float)$selectedPackage->discounted_price * 20 / 100;
            $selectedPackage->discounted_price =$isAnnual === true ?
                (float)$selectedPackage->discounted_price - $discount : $selectedPackage->discounted_price;
            $selectedPackage->discounted_price = round($selectedPackage->discounted_price);
            switch ($expense) {
                case 20000:
                    $selectedPackage->discounted_price = $isAnnual === true ?
                        (float)$selectedPackage->discounted_price + 40 :
                        (float)$selectedPackage->discounted_price + 50;
                    break;
                case 30000:
                    $selectedPackage->discounted_price = $isAnnual === true ?
                        (float)$selectedPackage->discounted_price + (40 * 2) :
                        (float)$selectedPackage->discounted_price + (50 * 2);
                    break;
                case 40000:
                    $selectedPackage->discounted_price = $isAnnual === true ?
                        (float)$selectedPackage->discounted_price + (40 * 3) :
                        (float)$selectedPackage->discounted_price + (50 * 3);
                    break;
                case 50000:
                    $selectedPackage->discounted_price = $isAnnual === true ?
                        (float)$selectedPackage->discounted_price + (40 * 4) :
                        (float)$selectedPackage->discounted_price + (50 * 4);
            }
            $itemEach = (float)$selectedPackage->discounted_price;
            if ($isAnnual === true) {
                $selectedPackage->discounted_price = (float)$selectedPackage->discounted_price * 12;
                $currentYear = Carbon::now()->year;
                $selectedPackage->title = $selectedPackage->title . ' ( ' . $currentYear . ' )';
                $quantity = 12;
            }
        }
        $orderValue = $orderValue + (float)$selectedPackage->discounted_price;
        $createdOrder = new Order([
            'custom_ind' => strtoupper(uniqid()),
            'order_created_by' => $currentUser->id,
            'order_creator_location' => null,
            'net_value' => $orderValue,
            'base_package_id' => $selectedPackage->id
        ]);
        $createdOrder->save();
        array_push($orderSummery, [
            'item' => $selectedPackage->title,
            'quantity' => $quantity,
            'costPerItem' => (float)$itemEach,
            'totalCost' => number_format((float)$selectedPackage->discounted_price, 2, '.', '')
        ]);
        if ($packageAddons !== null) {
            $selectedPackageAddonsWithQty = explode(',', $packageAddons);
            foreach ($selectedPackageAddonsWithQty as $index => $addon) {
                $addonAndQuantity = explode(':', $addon);
                $selectedAddonId = $addonAndQuantity[0];
                $selectedAddonQuantity = $addonAndQuantity[1];
                $selectedAddon = PackageAddon::find($selectedAddonId);
                if ($selectedAddon !== null) {
                    $cost = (float)$selectedAddon->discounted_price * $selectedAddonQuantity;
                    $orderValue = $orderValue + $cost;
                    $orderAddonCreated = new OrderAddon([
                        'order_id' => $createdOrder->id,
                        'package_addon_id' => $selectedAddon->id,
                        'quantity' => $selectedAddonQuantity
                    ]);
                    $orderAddonCreated->save();
                    array_push($orderSummery, [
                        'item' => $selectedAddon->title,
                        'quantity' => (int)$selectedAddonQuantity,
                        'costPerItem' => (float)$selectedAddon->discounted_price,
                        'totalCost' => number_format((float)$cost, 2, '.', '')
                    ]);
                }
            }
        }
        Order::where('id', $createdOrder->id)->update([
            'net_value' => $orderValue
        ]);
        dispatch(new SendOrderConfirmation(
            $createdOrder->custom_ind,
            Carbon::now(),
            $orderSummery,
            $currentUser->email,
            $orderValue,
            $currentUser->name
        ));
        dispatch(new SendOnboardingEmail($currentUser,  $selectedPackage->packageCategory->title));
        if($isPaypalEnabled){
            $payPalSuccessRes = new PayPalSuccessResponse([
                'response'  => json_encode($this->payPalSuccess),
                'order_id' =>  $createdOrder->id
            ]);
            $payPalSuccessRes->save();
        }
        return $this->sendResponse([
            'summery' => $orderSummery,
            'total_cost' => number_format((float)$orderValue, 2, '.', ''),
            'order_id' => $createdOrder->custom_ind
        ], 'Order has been submitted successfully!');
    }

    private function authorizePayment($payPalOrderId, $errorCondition) {
        try{
            $authorizeRequest = $this->httpClient->post( $this->payPalBaseUrl.'v2/checkout/orders/'.$payPalOrderId.'/authorize',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => $this->payPalAuthToken,
//                        'PayPal-Mock-Response' => json_encode([
//                            'mock_application_codes' => 'AGREEMENT_ALREADY_CANCELLED'
//                        ])
                    ]
                ]);
            $response = $authorizeRequest->getBody()->getContents();
            $response = json_decode($response);
            $this->payPalSuccess = $response;
            return true;

        }catch (\GuzzleHttp\Exception\ClientException $exception){
            $errorMsg = $exception->getResponse()->getBody()->getContents();
            $this->payPalError = $this->getPayPalError($errorMsg);
            return false;
        }
    }

    private function capturePayment($payPalOrderAuthorizedId) {
        try{
            $captureRequest = $this->httpClient->post( $this->payPalBaseUrl.'v2/payments/authorizations/'.$payPalOrderAuthorizedId.'/capture',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => $this->payPalAuthToken,

                    ]
                ]);
            $response = $captureRequest->getBody()->getContents();
            $response = json_decode($response);
            $this->payPalSuccess = $response;
            return true;

        }catch (\GuzzleHttp\Exception\ClientException $exception){
            $errorMsg = $exception->getResponse()->getBody()->getContents();
            $this->payPalError = $this->getPayPalError($errorMsg);
            return false;
        }
    }

    private function getPayPalError($errorMsg){
        $errorMsg = \GuzzleHttp\json_decode($errorMsg);
        return $errorMsg;
    }

    public function buildRequestBody(){
        return "{}";
    }

}
