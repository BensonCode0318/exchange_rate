<?php

namespace App\Constants;

/**
 * Class ExceptionConstant
 *
 * @package App\Constants
 */
class ExchangeRateConstant
{
    const EXCHANGE_RATE_TYPE_TWD = 'TWD';
    const EXCHANGE_RATE_TYPE_JPY = 'JPY';
    const EXCHANGE_RATE_TYPE_USD = 'USD';

    const EXCHANGE_RATE_TYPE_ARY = [
        self::EXCHANGE_RATE_TYPE_TWD,
        self::EXCHANGE_RATE_TYPE_JPY,
        self::EXCHANGE_RATE_TYPE_USD,
    ];
}
