<?php

namespace App\Services;

use App\Traits\ApiControllerTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CurrencyService
{
    use ApiControllerTrait;


    /**
     * @var float
     */
    protected $commission = 0.01;

    /**
     * @param RequestService $requestService
     */
    public function __construct(
        protected RequestService $requestService,
    )
    {
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function rates(Request $request): JsonResponse
    {
        $data = $this->requestService->sendRequest(config('blockchain.api'));

        if ($data['status'] === 'error') {
            return $this->response(
                data: $data,
                statusCode: $data['code']
            );
        }

        $data = $data['data'];

        $rates = [];
        if ($request->has('currency')) {
            $requestedCurrency = strtoupper($request->input('currency'));
            if (isset($data[$requestedCurrency])) {
                $rates[$requestedCurrency] = round($data[$requestedCurrency]['15m'] * 0.98, 2);
            }
        } else {
            foreach ($data as $currency => $rate) {
                $rates[$currency] = round($rate['15m'] * (1 - $this->commission), 2);
            }

            asort($rates);
        }

        return $this->response(
            data: [
                'status' => 'success',
                'code' => 200,
                'data' => $rates
            ],
        );
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public function convert(array $data): JsonResponse
    {
        $currencyFrom = $data['currency_from'];
        $currencyTo = $data['currency_to'];
        $value = $data['value'];

        if (!in_array('BTC', [$currencyFrom, $currencyTo])) {
            return $this->response(
                data: [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'One of the currencies should be BTC'
                ],
                statusCode: 400
            );
        }

        $data = $this->requestService->sendRequest(config('blockchain.api'));

        if ($data['status'] === 'error') {
            return $this->response(
                data: $data,
                statusCode: $data['code']
            );
        }

        $data = $data['data'];

        if (
            ($currencyFrom === 'BTC' && !isset($data[$currencyTo])) ||
            ($currencyTo === 'BTC' && !isset($data[$currencyFrom]))
        ) {
            return $this->response(
                data: [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Invalid currency'
                ],
                statusCode: 400
            );
        }


        if ($currencyFrom === 'BTC') {
            $rate = $value * $data[$currencyTo]['15m'];
        } else {
            $rate = $value / $data[$currencyFrom]['15m'];
        }

        $precision = $currencyFrom === 'BTC' ? 2 : 10;
        $rate = round($rate, $precision);
        $convertedValue = round($rate * (1 - $this->commission), $precision);

        return $this->response(
            data: [
                'status' => 'success',
                'code' => 200,
                'data' => [
                    'currency_from' => $currencyFrom,
                    'currency_to' => $currencyTo,
                    'value' => $value,
                    'converted_value' => $convertedValue,
                    'rate' => $rate,
                ]
            ],
        );
    }
}
