<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Constants\ExchangeRateConstant;

class ExchangeRateTest extends TestCase
{
    public function getExchangeRateProvider()
    {
        return [
            [
                '/api/exchange_rate',
            ]
        ];
    }

    /**
     * 驗證成功轉換
     * @dataProvider getExchangeRateProvider
     *
     * @param string $url
     */
    public function testSuccess(string $url)
    {
        $inputData = [
            'originRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_TWD,
            'targetRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_JPY,
            'money'      => 20000,
        ];

        $response = $this->json('POST', $url, $inputData);

        $response->assertStatus(200);
        $response->assertJson([
            'code' => 200,
            'status' => true
        ]);
        $response->assertJsonStructure([
            'code',
            'status',
            'message',
            'request_id',
            'response' => [
                'originRate',
                'targetRate',
                'originMoney',
                'exchangeMoney'
            ]
        ]);
    }
    
    /**
     * 驗證匯率代碼錯誤
     * @dataProvider getExchangeRateProvider
     *
     * @param string $url
     */
    public function testValidationRateStringError(string $url)
    {
        $inputData = [
            'originRate' => "ABC",
            'targetRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_JPY,
            'money'      => 20000,
        ];

        $response = $this->json('POST', $url, $inputData);

        $response->assertStatus(400);
        $response->assertJson([
            'code' => 10001,
            'status' => false
        ]);
        $response->assertJsonStructure([
            'code',
            'status',
            'message',
            'detail',
        ]);
    }

    /**
     * 驗證金額錯誤
     * @dataProvider getExchangeRateProvider
     *
     * @param string $url
     */
    public function testValidationMoneyError(string $url)
    {
        $inputData = [
            'originRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_TWD,
            'targetRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_JPY,
            'money'      => "abc",
        ];

        $response = $this->json('POST', $url, $inputData);

        $response->assertStatus(400);
        $response->assertJson([
            'code' => 10001,
            'status' => false
        ]);
        $response->assertJsonStructure([
            'code',
            'status',
            'message',
            'detail',
        ]);
    }
}
