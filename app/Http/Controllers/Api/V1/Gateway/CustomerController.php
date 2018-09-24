<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use Illuminate\Http\Request;
use App\Http\Requests\Api\Gateway\SaveCustomer;
use App\Http\Controllers\Controller;
use App\Models\GatewayCustomer;
use Braintree\Gateway;

class CustomerController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('jwt.auth');
    }

    /**
     * Get customer data
     * 
     * @return mixed
     */
    public function index()
    {
        return $this->api->user()->gatewayCustomers;
    }

    /**
     * Create Customer data
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function create(SaveCustomer $request, Gateway $gateway)
    {
        $data = $request->only([
            'first_name', 'last_name', 'address', 'city',
            'state', 'country', 'postal_code', 'nonce',
            'type', 'last_four', 'expiration_month', 'expiration_year'
        ]);

        $result = $gateway->customer()->create([
            'firstName' => $data['first_name'],
            'lastName' => $data['last_name'],
            'paymentMethodNonce' => $data['nonce'],
            'creditCard' => [
                    'billingAddress' => [
                    'streetAddress' => $data['address'],
                    'locality' => $data['city'],
                    'region' => $data['state'],
                    'countryName' => $data['country'],
                    'postalCode' => $data['postal_code']
                ]
            ]
        ]);

        if ($result->success) {
            $customer = $this->api->user()->gatewayCustomers()->create([
                'customer_id' => $result->customer->id,
                'token' => $result->customer->paymentMethods[0]->token,
                'type' => $data['type'],
                'last_four' => $data['last_four'],
                'expiration_month' => $data['expiration_month'],
                'expiration_year' => $data['expiration_year']
            ]);

            return $customer;
        }

        return response()->json([
            'message' => 'An error occurred while saving customer data'
        ], 422);
    }

    /**
     * Get customer
     * 
     * @param  Request         $request Request object
     * @param  GatewayCustomer $model   Model instance
     * @return GatewayCustomer
     */
    public function get(Request $request, GatewayCustomer $model) {
        if ($this->api->user()->id != $model->user_id) {
            abort(401);
        }

        return $model;
    }

    /**
     * Get customer
     * 
     * @param  Request         $request Request object
     * @param  GatewayCustomer $model   Model instance
     * @return GatewayCustomer
     */
    public function getDetails(Request $request, GatewayCustomer $model, Gateway $gateway) {
        if ($this->api->user()->id != $model->user_id) {
            abort(401);
        }

        $details = $gateway->customer()->find($model->customer_id);

        if (!$details) {
            return response(null, 500);
        }

        return [
            'first_name' => $details->firstName,
            'last_name' => $details->lastName,
            'address' => $details->addresses[0]->streetAddress,
            'city' => $details->addresses[0]->locality,
            'state' => $details->addresses[0]->region,
            'country' => $details->addresses[0]->countryName,
            'postal_code' => $details->addresses[0]->postalCode,
        ];
    }

    /**
     * Delete customer
     * 
     * @param  Request         $request Request object
     * @param  GatewayCustomer $model   Model instance
     * @return mixed
     */
    public function delete(Request $request, GatewayCustomer $model)
    {
        if ($this->api->user()->id != $model->user_id) {
            abort(401);
        }

        $model->delete();

        return response(null, 202);
    }
}
