<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Mockery\MockInterface;
use App\Services\ExchangeRateService;
use App\Constants\ExchangeRateConstant;
use App\Exceptions\ExchangeRateException;

class ExchangeRateServiceTest extends TestCase
{
    private $exchangeRateServiceMock;
    private $exchangeRateService;

    public function setUp() : void
    {
        parent::setUp();
        $this->exchangeRateServiceMock = \Mockery::mock(ExchangeRateService::class);
        $this->exchangeRateService     = app(exchangeRateService::class);
    }

    /**
     * 驗證成功轉換
     */
    public function testCalculateRate()
    {
        $inputData = [
            'originRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_TWD,
            'targetRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_JPY,
            'money'      => 16500.2,
        ];

        $mockRetrun = 60539.23;
        
        // act
        $this->exchangeRateServiceMock
            ->shouldReceive('calculateRate')
            ->once()
            ->with($inputData)
            ->andReturn($mockRetrun);
            
        $mockResult = $this->exchangeRateServiceMock->calculateRate($inputData);
        $result     = $this->exchangeRateService->calculateRate($inputData);
        $this->assertSame($mockResult, $result);
    }

    /**
     * 驗證相同匯率不需轉換
     */
    public function testCalculateRateSameRate()
    {
        $inputData = [
            'originRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_TWD,
            'targetRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_TWD,
            'money'      => 150000,
        ];

        $mockRetrun = 150000;
        
        // act
        $this->exchangeRateServiceMock
            ->shouldReceive('calculateRate')
            ->once()
            ->with($inputData)
            ->andReturn($mockRetrun);
            
        $mockResult = $this->exchangeRateServiceMock->calculateRate($inputData);
        $result     = $this->exchangeRateService->calculateRate($inputData);
        $this->assertEquals($mockResult, $result);
    }

    /**
     * 金額為0不需要轉換
     */
    public function testCalculateRateMoneyZero()
    {
        $inputData = [
            'originRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_TWD,
            'targetRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_TWD,
            'money'      => 0,
        ];

        $mockRetrun = 0;
        
        // act
        $this->exchangeRateServiceMock
            ->shouldReceive('calculateRate')
            ->once()
            ->with($inputData)
            ->andReturn($mockRetrun);
            
        $mockResult = $this->exchangeRateServiceMock->calculateRate($inputData);
        $result     = $this->exchangeRateService->calculateRate($inputData);
        $this->assertEquals($mockResult, $result);
    }

    /**
     * 驗證金額不能為負數
     */
    public function testCalculateRateMoneyNegativeFail()
    {
        $inputData = [
            'originRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_TWD,
            'targetRate' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_JPY,
            'money'      => -300,
        ];
        
        $this->expectException(ExchangeRateException::class);
        // act
        $this->exchangeRateService->calculateRate($inputData);
    }

    /**
     * 驗證匯率轉換
     */
    public function testGetExchangeRateValue()
    {
        $inputData = [
            'origin' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_USD,
            'target' => ExchangeRateConstant::EXCHANGE_RATE_TYPE_JPY,
        ];

        $class = new ExchangeRateService(app(ExchangeRateException::class));
        $function = function () use ($inputData) {
            return $this->getExchangeRateValue($inputData['origin'], $inputData['target']);
        };
        $test = $function->bindTo($class, $class);
        $this->assertEquals(111.801, $test());
    }
}
