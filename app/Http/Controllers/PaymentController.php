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
    private $data;

    public function __construct(Request $request)
    {

        $this->data = $request;
        $token = $request->bearerToken();
        $this->httpClient = new Client(
            [
                'base_uri' => getenv('PAYMENT_SERVICE_API'),
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
            $payment = $this->httpClient->post(
                'payment',
                [
                    'json' =>
                    [
                        'invoice_id' => $this->data->invoice_id,
                        'amount' => $this->data->amount,
                    ],
                ]
            );
            return response($payment->getBody(), 200);
        } catch (\Throwable $th) {
            return response()->json(array('error' => $th->getMessage()), 400);
        }

    }

}
