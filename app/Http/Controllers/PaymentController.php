<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $httpClient;

    public function __construct(Request $request)
    {
        $token = $request->bearerToken();
        $this->httpClient = new Client(
            [
                'headers' =>
                [
                    // JWT token from this request
                    'Authorization' => 'Bearer ' . $token,
                ],

            ]
        );
    }

    public function makePayment()
    {
        try {
            $url = getenv('PAYMENT_SERVICE_API');
            $payment = $this->httpClient->get($url);
            return response($payment->getBody(), 200)->header('Content-Type', 'application/json');
        } catch (\Throwable $th) {
            return response()->json(array('error' => $th->getMessage()), 400);
        }

    }

}
