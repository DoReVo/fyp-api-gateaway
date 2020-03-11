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

        $this->httpClient = new Client(
            [
                'headers' =>
                [
                    // take session_id cookie from request to be used in http call
                    // to invoice-service
                    "Cookie" => 'session_id=' . $request->cookie('session_id'),
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
