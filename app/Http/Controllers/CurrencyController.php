<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyConvertRequest;
use App\Services\CurrencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * @param CurrencyService $currencyService
     */
    public function __construct(
        protected CurrencyService $currencyService
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function rates(Request $request): JsonResponse
    {
        return $this->currencyService->rates($request);
    }

    /**
     * @param CurrencyConvertRequest $request
     * @return JsonResponse
     */
    public function convert(CurrencyConvertRequest $request): JsonResponse
    {
        return $this->currencyService->convert($request->validated());
    }
}
