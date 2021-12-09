<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Exceptions\UserException;
use App\Services\ExchangeRateService;
use App\Constants\ExchangeRateConstant;
use App\Http\Resources\PostExchangeRateResource;

class ExchangeRateController extends Controller
{
    private $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * [POST] - 轉換匯率
     */
    public function postExchangeRate(Request $request)
    {
        $inputData    = $request->all();
        $validateRule = [
            'originRate'  => ['required', 'string', Rule::in(ExchangeRateConstant::EXCHANGE_RATE_TYPE_ARY)],
            'targetRate'  => ['required', 'string', Rule::in(ExchangeRateConstant::EXCHANGE_RATE_TYPE_ARY)],
            'money'       => ['required', 'numeric']
        ];
        $this->validateByRule($inputData, $validateRule);

        $exchangeMoney = $this->exchangeRateService->calculateRate($inputData);
        
        $response = [
            'exchangeMoney' => $exchangeMoney,
            'inputData'     => $inputData
        ];

        return new PostExchangeRateResource($response);
    }
}
