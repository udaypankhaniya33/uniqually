<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Jobs\SendOnboardingEmail;
use App\Jobs\SendOrderConfirmation;
use App\Order;
use App\OrderAddon;
use App\Package;
use App\PackageAddon;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use PayPalCheckoutSdk\Payments\AuthorizationsCaptureRequest;
use Stevebauman\Location\Facades\Location;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\PayPalEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Orders\OrdersAuthorizeRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class OrdersController extends BaseController
{

    private $payPalClient;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $payPalConfig = Config::get('paypal');
       if($payPalConfig['settings']['mode'] === 'sandbox'){
           $this->payPalClient = new PayPalHttpClient( new SandboxEnvironment(
               $payPalConfig['sandbox_client_id'],
               $payPalConfig['sandbox_secret']
           ));
       }else{
           // todo::set live configurations
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
        $orderSummery = [];
        $orderValue = 0;
        $quantity = 1;
        $itemEach = 0;
        if(request()->has('base_package_id', 'paypal_order_id', 'paypal_payer_id', 'paypal_auth_token')) {
            $authorizeOrderRequest = new OrdersAuthorizeRequest(request('paypal_order_id'));
            $authorizeOrderRequest->headers["prefer"] = "return=representation";
            $authorizeOrderRequest->headers["PayPal-Partner-Attribution-Id"] = request('paypal_auth_token');
            try {
                $orderAuthorizationDetails = $this->payPalClient->execute($authorizeOrderRequest);
                if ($orderAuthorizationDetails->statusCode === 201) {
                    $authorizationId = $orderAuthorizationDetails->result->purchase_units[0]->payments->authorizations[0]->id;
                    $orderCaptureRequest = new AuthorizationsCaptureRequest($authorizationId);
                    $orderCaptureRequest->body = $this->buildRequestBody();
                    try {
                        $orderCaptureRequestDetails = $this->payPalClient->execute($orderCaptureRequest);
                        if ($orderCaptureRequestDetails->statusCode === 201) {
                            $selectedPackage = Package::with('packageCategory')->find(request('base_package_id'));
                            $itemEach = $selectedPackage->discounted_price;
                            if (request()->has(['is_annual', 'expense']) && $selectedPackage->packageCategory->title === 'Bookkeeping') {
                                $discount = (float)$selectedPackage->discounted_price * 20 / 100;
                                $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                                    (float)$selectedPackage->discounted_price - $discount : $selectedPackage->discounted_price;
                                $selectedPackage->discounted_price = round($selectedPackage->discounted_price);
                                switch (request('expense')) {
                                    case 20000:
                                        $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                                            (float)$selectedPackage->discounted_price + 40 :
                                            (float)$selectedPackage->discounted_price + 50;
                                        break;
                                    case 30000:
                                        $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                                            (float)$selectedPackage->discounted_price + (40 * 2) :
                                            (float)$selectedPackage->discounted_price + (50 * 2);
                                        break;
                                    case 40000:
                                        $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                                            (float)$selectedPackage->discounted_price + (40 * 3) :
                                            (float)$selectedPackage->discounted_price + (50 * 3);
                                        break;
                                    case 50000:
                                        $selectedPackage->discounted_price = request('is_annual') === 'true' ?
                                            (float)$selectedPackage->discounted_price + (40 * 4) :
                                            (float)$selectedPackage->discounted_price + (50 * 4);
                                }
                                $itemEach = (float)$selectedPackage->discounted_price;
                                if (request('is_annual') === 'true') {
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
                            if (request()->has('package_addons') && request('package_addons') !== null) {
                                $selectedPackageAddonsWithQty = explode(',', request('package_addons'));
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
                                $orderValue
                            ));
                            dispatch(new SendOnboardingEmail($currentUser));
                            return $this->sendResponse([
                                'summery' => $orderSummery,
                                'total_cost' => number_format((float)$orderValue, 2, '.', ''),
                                'order_id' => $createdOrder->custom_ind
                            ], 'Order has been submitted successfully!');

                        }
                    } catch (Exception $exception) {
                        return $this->sendError('Please provide valid data', [
                            'error' => [
                                'paypal_order_id' => $exception
                            ]
                        ], 422);
                    }
                }
            }catch (Exception $exception) {
                return $this->sendError('Please provide valid data', [
                    'error' => [
                        'paypal_order_id' => 'An error occurred while authorization'
                    ]
                ], 422);
            }

        }else {
            return $this->sendError('Please provide valid data', [
                'error' => [
                    'base_package_id' => 'Base package is required to initiate order',
                    'paypal_payer_id' => 'PayPal payer id is required',
                    'paypal_auth_token' => 'PayPal authorization token is required',
                    'paypal_order_id' => 'PayPal order id is required'
                ]
            ], 422);
        }

    }

    public function buildRequestBody(){
        return "{}";
    }

}
