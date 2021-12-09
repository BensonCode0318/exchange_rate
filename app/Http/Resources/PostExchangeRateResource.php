<?php


namespace App\Http\Resources;

class PostExchangeRateResource extends BaseJsonResource
{
    public function toArray($request)
    {
        return [
            'originRate'    => $this['inputData']['originRate'],
            'targetRate'    => $this['inputData']['targetRate'],
            'originMoney'   => $this['inputData']['money'],
            'exchangeMoney' => number_format($this['exchangeMoney'], 2, '.', ','),
        ];
    }
}
