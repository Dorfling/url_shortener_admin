<?php

namespace App\Libraries\PaymentGateway;

use App\Libraries\PaymentGateway\Helper\BasePaymentGatewayHelper;
use App\Libraries\PaymentGateway\Helper\PayFastHelper;
use App\Models\Enum\PaymentGatewaysEnum;
use App\Models\PaymentGateway;

class PaymentGatewayLibrary
{
    /**
     * @var BasePaymentGatewayHelper
     */
    public $paymentGatewayHelper = null;

    /**
     * PaymentGatewayLibrary constructor.
     * @param PaymentGateway $paymentGateway
     * @throws \Exception
     */
    public function __construct(PaymentGateway $paymentGateway)
    {
        switch ($paymentGateway->id) {
            case PaymentGatewaysEnum::PAYFAST_ID:
                $this->paymentGatewayHelper = new PayFastHelper();
                break;
            default:
                throw new \Exception('Payment Gateway not supported');
        }
    }
}
