<?php

namespace App\Services;

use App\Exceptions\ExchangeRateException;

class ExchangeRateService
{
    private $exception;

    public function __construct(ExchangeRateException $exception)
    {
        $this->exception = $exception;
    }

    /**
     * 計算匯率
     * @param array $inputData [
     *                  @param string originRate,
     *                  @param string targetRate,
     *                  @param numeric money
     *               ]
     *
     * @return numeric
     */
    public function calculateRate(array $inputData)
    {
        // 來源與結果匯率相同或者金額為0 不需計算
        if ($inputData['originRate'] === $inputData['targetRate'] || $inputData['money'] == 0) {
            return $inputData['money'];
        }

        // 金額不可為負數，請重新確認。
        $inputData['money'] < 0 && $this->exception->error(20001);

        // 取得匯率
        $rate = $this->getExchangeRateValue($inputData['originRate'], $inputData['targetRate']);
        return round($inputData['money'] * $rate, 2);
    }

    /**
     * 取得對應的匯率
     * @param string $target
     * @param string $origin
     *
     * @return mixed
     */
    private function getExchangeRateValue(string $origin, string $target)
    {
        $json = '
        {
            "currencies": {
                "TWD": {
                    "TWD": 1,
                    "JPY": 3.669,
                    "USD": 0.03281
                },
                "JPY": {
                    "TWD": 0.26956,
                    "JPY": 1,
                    "USD": 0.00885
                },
                "USD": {
                    "TWD": 30.444,
                    "JPY": 111.801,
                    "USD": 1
                }
            }
        }
        ';
        $rate = json_decode($json, true);

        return $rate['currencies'][$origin][$target];
    }
}
