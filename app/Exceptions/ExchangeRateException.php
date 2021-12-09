<?php

namespace App\Exceptions;

use App\Constants\ExceptionConstant;
use Throwable;

/**
 * Class AddressException
 *
 * @package App\Exceptions
 */
class ExchangeRateException extends BaseException
{
    private $errorConfig = [
        '20001' => [
            'type'    => ExceptionConstant::FAILURE,
            'message' => '金額不可為負數，請重新確認。',
            'sentry'  => false,
        ]
    ];

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->setErrorConfig($this->errorConfig);
    }
}
