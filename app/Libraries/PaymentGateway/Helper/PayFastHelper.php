<?php

namespace App\Libraries\PaymentGateway\Helper;

use Carbon\Carbon;

/**
 * Class PayFastHelper
 * @package App\Libraries\PaymentGateway\Helper
 * Documentation https://developers.payfast.co.za/documentation/
 */
class PayFastHelper implements BasePaymentGatewayHelper
{
    public const URL_VALIDATION_LIVE = 'https://www.payfast.co.za/eng/query/validate';
    public const URL_VALIDATION_DEV = 'https://sandbox.payfast.co.za/eng/query/validate';
    public const URL_PROCESS_LIVE = 'https://www.payfast.co.za/eng/process';
    public const URL_PROCESS_DEV = 'https://sandbox.payfast.co.za/eng/process';

    public const PARAMETER_MERCHANT_ID = 'merchant_id';
    public const PARAMETER_MERCHANT_KEY = 'merchant_key';
    public const PARAMETER_RETURN_URL = 'return_url';
    public const PARAMETER_CANCEL_URL = 'cancel_url';
    public const PARAMETER_NOTIFY_URL = 'notify_url';
    public const PARAMETER_NAME_FIRST = 'name_first';
    public const PARAMETER_NAME_LAST = 'name_last';
    public const PARAMETER_EMAIL_ADDRESS = 'email_address';
    public const PARAMETER_CELL_NUMBER = 'cell_number';
    public const PARAMETER_M_PAYMENT_ID = 'm_payment_id';
    public const PARAMETER_AMOUNT = 'amount';
    public const PARAMETER_ITEM_NAME = 'item_name';
    public const PARAMETER_ITEM_DESCRIPTION = 'item_description';
    public const PARAMETER_CUSTOM_INT1 = 'custom_int1';
    public const PARAMETER_CUSTOM_INT2 = 'custom_int2';
    public const PARAMETER_CUSTOM_INT3 = 'custom_int3';
    public const PARAMETER_CUSTOM_INT4 = 'custom_int4';
    public const PARAMETER_CUSTOM_INT5 = 'custom_int5';
    public const PARAMETER_CUSTOM_STR1 = 'custom_str1';
    public const PARAMETER_CUSTOM_STR2 = 'custom_str2';
    public const PARAMETER_CUSTOM_STR3 = 'custom_str3';
    public const PARAMETER_CUSTOM_STR4 = 'custom_str4';
    public const PARAMETER_CUSTOM_STR5 = 'custom_str5';
    public const PARAMETER_EMAIL_CONFIRMATION = 'email_confirmation';
    public const PARAMETER_CONFIRMATION_ADDRESS = 'confirmation_address';
    public const PARAMETER_CURRENCY = 'currency';
    public const PARAMETER_PAYMENT_METHOD = 'payment_method';
    public const PARAMETER_SUBSCRIPTION_TYPE = 'subscription_type';
    public const PARAMETER_BILLING_DATE = 'billing_date';
    public const PARAMETER_RECURRING_AMOUNT = 'recurring_amount';
    public const PARAMETER_FREQUENCY = 'frequency';
    public const PARAMETER_CYCLES = 'cycles';

    public const SUBSCRIPTION_FREQUENCY_MONTHLY = 3;
    public const SUBSCRIPTION_FREQUENCY_QUARTERLY = 4;
    public const SUBSCRIPTION_FREQUENCY_BIANNUAL = 5;
    public const SUBSCRIPTION_FREQUENCY_ANNUAL = 6;


    /**
     * According to https://developers.payfast.co.za/documentation/#checkout-page Section:The order of the checkout variables
     * The order the variables are in this array, are the order in which they'll need to be encoded for a security check
     * @var array
     */
    private $parameters = [
        self::PARAMETER_MERCHANT_ID => null,
        self::PARAMETER_MERCHANT_KEY => null,
        self::PARAMETER_RETURN_URL => null,
        self::PARAMETER_CANCEL_URL => null,
        self::PARAMETER_NOTIFY_URL => null,
        self::PARAMETER_NAME_FIRST => null,
        self::PARAMETER_NAME_LAST => null,
        self::PARAMETER_EMAIL_ADDRESS => null,
        self::PARAMETER_CELL_NUMBER => null,
        self::PARAMETER_M_PAYMENT_ID => null,
        self::PARAMETER_AMOUNT => null,
        self::PARAMETER_ITEM_NAME => null,
        self::PARAMETER_ITEM_DESCRIPTION => null,
        self::PARAMETER_CUSTOM_INT1 => null,
        self::PARAMETER_CUSTOM_INT2 => null,
        self::PARAMETER_CUSTOM_INT3 => null,
        self::PARAMETER_CUSTOM_INT4 => null,
        self::PARAMETER_CUSTOM_INT5 => null,
        self::PARAMETER_CUSTOM_STR1 => null,
        self::PARAMETER_CUSTOM_STR2 => null,
        self::PARAMETER_CUSTOM_STR3 => null,
        self::PARAMETER_CUSTOM_STR4 => null,
        self::PARAMETER_CUSTOM_STR5 => null,
        self::PARAMETER_EMAIL_CONFIRMATION => null,
        self::PARAMETER_CONFIRMATION_ADDRESS => null,
        self::PARAMETER_CURRENCY => null,
        self::PARAMETER_PAYMENT_METHOD => null,
        self::PARAMETER_SUBSCRIPTION_TYPE => null,
        self::PARAMETER_BILLING_DATE => null,
        self::PARAMETER_RECURRING_AMOUNT => null,
        self::PARAMETER_FREQUENCY => null,
        self::PARAMETER_CYCLES => null,
    ];

    /**
     * These fields will need to be set
     * @var string[]
     */
    private static $requiredFields = [
        self::PARAMETER_MERCHANT_ID,
        self::PARAMETER_MERCHANT_KEY,
        self::PARAMETER_AMOUNT,
        self::PARAMETER_ITEM_NAME,
    ];

    public function __construct()
    {
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateGetString(): string
    {
        $getString = '';
        foreach ($this->getParameters() as $parameter => $value) {
            //Check if the required parameters have been set correctly before building the get string
            if (in_array($parameter, $this->getRequiredFields()) && $value === null) {
                throw new \Exception($parameter . ' is a required parameter, but was not set');
            }
            //If a parameter was not set, skip it
            if ($value === null) {
                continue;
            }
            //If the get string is not empty, we need to append a & to split the key value pairs
            if ($getString !== '') {
                $getString .= '&';
            }
            $getString .= $parameter . '=' . $value;
        }
        return $getString;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getSignature(): string
    {
        $getString = $this->generateGetString();
        return md5($getString);
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getRequiredFields(): array
    {
        return self::$requiredFields;
    }

    /**
     * Required field
     * @return int
     */
    public function getMerchantId(): int
    {
        return $this->parameters[self::PARAMETER_MERCHANT_ID];
    }

    /**
     * Required field
     * @param int $merchantId
     */
    public function setMerchantId(int $merchantId)
    {
        $this->parameters[self::PARAMETER_MERCHANT_KEY] = $merchantId;
    }

    /**
     * Required field
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->parameters[self::PARAMETER_MERCHANT_KEY];
    }

    /**
     * Required field
     * @param string $merchantKey
     */
    public function setMerchantKey(string $merchantKey)
    {
        $this->parameters[self::PARAMETER_MERCHANT_KEY] = $merchantKey;
    }

    /**
     * @return string|null
     */
    public function getReturnUrl(): ?string
    {
        return $this->parameters[self::PARAMETER_RETURN_URL];
    }

    /**
     * @param string|null $returnUrl
     */
    public function setReturnUrl(string $returnUrl = null)
    {
        $this->parameters[self::PARAMETER_RETURN_URL] = $returnUrl;
    }

    /**
     * @return string|null
     */
    public function getCancelUrl(): ?string
    {
        return $this->parameters[self::PARAMETER_CANCEL_URL];
    }

    /**
     * @param string|null $cancelUrl
     */
    public function setCancelUrl(string $cancelUrl = null)
    {
        $this->parameters[self::PARAMETER_CANCEL_URL] = $cancelUrl;
    }

    /**
     * @return string|null
     */
    public function getNotifyUrl(): ?string
    {
        return $this->parameters[self::PARAMETER_NOTIFY_URL];
    }

    /**
     * @param string|null $notifyUrl
     */
    public function setNotifyUrl(string $notifyUrl = null)
    {
        $this->parameters[self::PARAMETER_NOTIFY_URL] = $notifyUrl;
    }

    /**
     * @return string|null
     */
    public function getNameFirst(): ?string
    {
        return $this->parameters[self::PARAMETER_NAME_FIRST];
    }

    /**
     * @param string|null $nameFirst
     */
    public function setNameFirst(string $nameFirst = null)
    {
        $this->parameters[self::PARAMETER_NAME_FIRST] = $nameFirst;
    }

    /**
     * @return string|null
     */
    public function getNameLast(): ?string
    {
        return $this->parameters[self::PARAMETER_NAME_LAST];
    }

    /**
     * @param string|null $nameLast
     */
    public function setNameLast(string $nameLast = null)
    {
        $this->parameters[self::PARAMETER_NAME_LAST] = $nameLast;
    }

    /**
     * @return string|null
     */
    public function getEmailAddress(): ?string
    {
        return $this->parameters[self::PARAMETER_EMAIL_ADDRESS];
    }

    /**
     * @param string|null $emailAddress
     */
    public function setEmailAddress(string $emailAddress = null)
    {
        $this->parameters[self::PARAMETER_EMAIL_ADDRESS] = $emailAddress;
    }

    /**
     * @return string|null
     */
    public function getCellNumber(): ?string
    {
        return $this->parameters[self::PARAMETER_CELL_NUMBER];
    }

    /**
     * @param string|null $cellNumber
     */
    public function setCellNumber(string $cellNumber = null)
    {
        $this->parameters[self::PARAMETER_CELL_NUMBER] = $cellNumber;
    }

    /**
     * @return string|null
     */
    public function getMPaymentId(): ?string
    {
        return $this->parameters[self::PARAMETER_M_PAYMENT_ID];
    }

    /**
     * @param string|null $mPaymentId
     */
    public function setMPaymentId(string $mPaymentId = null)
    {
        $this->parameters[self::PARAMETER_M_PAYMENT_ID] = $mPaymentId;
    }

    /**
     * Required field
     * @return string
     */
    public function getAmount(): string
    {
        return $this->parameters[self::PARAMETER_AMOUNT];
    }

    /**
     * Required field
     * @param string $amount
     */
    public function setAmount(string $amount)
    {
        $this->parameters[self::PARAMETER_AMOUNT] = $amount;
    }

    /**
     * Required field
     * @return string
     */
    public function getItemName(): string
    {
        return $this->parameters[self::PARAMETER_ITEM_NAME];
    }

    /**
     * Required field
     * @param string $itemName
     */
    public function setItemName(string $itemName)
    {
        $this->parameters[self::PARAMETER_ITEM_NAME] = $itemName;
    }

    /**
     * @return string|null
     */
    public function getItemDescription(): ?string
    {
        return $this->parameters[self::PARAMETER_ITEM_DESCRIPTION];
    }

    /**
     * @param string|null $itemDescription
     */
    public function setItemDescription(string $itemDescription = null)
    {
        $this->parameters[self::PARAMETER_ITEM_DESCRIPTION] = $itemDescription;
    }

    /**
     * @return string|null
     */
    public function getCustomInt1(): ?string
    {

        return $this->parameters[self::PARAMETER_CUSTOM_INT1];
    }

    /**
     * public function setCustomInt1(string $customInt1 = null)
     * {
     * $this->parameters[self::PARAMETER_CUSTOM_INT1] = $customInt1;
     * }
     *
     * /**
     * @return string|null
     */
    public function getCustomInt2(): ?string
    {
        $this->parameters[self::PARAMETER_CUSTOM_INT2];
    }

    /**
     * @param string|null $customInt2
     */
    public function setCustomInt2(string $customInt2 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_INT2] = $customInt2;
    }

    /**
     * @return string|null
     */
    public function getCustomInt3(): ?string
    {
        $this->parameters[self::PARAMETER_CUSTOM_INT3];
    }

    /**
     * @param string|null $customInt3
     */
    public function setCustomInt3(string $customInt3 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_INT3] = $customInt3;
    }

    /**
     * @return string|null
     */
    public function getCustomInt4(): ?string
    {
        $this->parameters[self::PARAMETER_CUSTOM_INT4];
    }

    /**
     * @param string|null $customInt4
     */
    public function setCustomInt4(string $customInt4 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_INT4] = $customInt4;
    }

    /**
     * @return string|null
     */
    public function getCustomInt5(): ?string
    {
        return $this->parameters[self::PARAMETER_CUSTOM_INT5];
    }

    /**
     * @param string|null $customInt5
     */
    public function setCustomInt5(string $customInt5 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_INT5] = $customInt5;
    }

    /**
     * @return string|null
     */
    public function getCustomStr1(): ?string
    {
        $this->parameters[self::PARAMETER_CUSTOM_STR1];
    }

    /**
     * @param string|null $customStr1
     */
    public function setCustomStr1(string $customStr1 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_STR1] = $customStr1;
    }

    /**
     * @return string|null
     */
    public function getCustomStr2(): ?string
    {
        return $this->parameters[self::PARAMETER_CUSTOM_STR2];
    }

    /**
     * @param string|null $customStr2
     */
    public function setCustomStr2(string $customStr2 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_STR2] = $customStr2;
    }

    /**
     * @return string|null
     */
    public function getCustomStr3(): ?string
    {
        return $this->parameters[self::PARAMETER_CUSTOM_STR3];
    }

    /**
     * @param string|null $customStr3
     */
    public function setCustomStr3(string $customStr3 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_STR3] = $customStr3;
    }

    /**
     * @return string|null
     */
    public function getCustomStr4(): ?string
    {
        return $this->parameters[self::PARAMETER_CUSTOM_STR4];
    }

    /**
     * @param string|null $customStr4
     */
    public function setCustomStr4(string $customStr4 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_STR4] = $customStr4;
    }

    /**
     * @return string|null
     */
    public function getCustomStr5(): ?string
    {
        return $this->parameters[self::PARAMETER_CUSTOM_STR5];
    }

    /**
     * @param string|null $customStr5
     */
    public function setCustomStr5(string $customStr5 = null)
    {
        $this->parameters[self::PARAMETER_CUSTOM_STR5] = $customStr5;
    }

    /**
     * @return string|null
     */
    public function getEmailConfirmation(): ?string
    {
        return $this->parameters[self::PARAMETER_EMAIL_ADDRESS];
    }

    /**
     * @param string|null $emailConfirmation
     */
    public function setEmailConfirmation(string $emailConfirmation = null)
    {
        $this->parameters[self::PARAMETER_EMAIL_ADDRESS] = $emailConfirmation;
    }

    /**
     * @return string|null
     */
    public function getConfirmationAddress(): ?string
    {
        return $this->parameters[self::PARAMETER_CONFIRMATION_ADDRESS];
    }

    /**
     * @param string|null $confirmationAddress
     */
    public function setConfirmationAddress(string $confirmationAddress = null)
    {
        $this->parameters[self::PARAMETER_CONFIRMATION_ADDRESS] = $confirmationAddress;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->parameters[self::PARAMETER_CURRENCY];
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(string $currency = null)
    {
        $this->parameters[self::PARAMETER_CURRENCY] = $currency;
    }

    /**
     * @return string|null
     */
    public function getPaymentMethod(): ?string
    {
        return $this->parameters[self::PARAMETER_PAYMENT_METHOD];
    }

    /**
     * @param string|null $paymentMethod
     */
    public function setPaymentMethod(string $paymentMethod = null)
    {
        $this->parameters[self::PARAMETER_PAYMENT_METHOD] = $paymentMethod;
    }

    /**
     * @return string|null
     */
    public function getSubscriptionType(): ?string
    {
        return $this->parameters[self::PARAMETER_SUBSCRIPTION_TYPE];
    }

    /**
     * @param string|null $subscriptionType
     */
    public function setSubscriptionType(string $subscriptionType = null)
    {
        $this->parameters[self::PARAMETER_SUBSCRIPTION_TYPE] = $subscriptionType;
    }

    /**
     * @return Carbon|null
     */
    public function getBillingDate(): ?Carbon
    {
        return $this->parameters[self::PARAMETER_BILLING_DATE];
    }

    /**
     * @param Carbon|null $billingDate
     */
    public function setBillingDate(Carbon $billingDate = null)
    {
        $this->parameters[self::PARAMETER_BILLING_DATE] = $billingDate;
    }

    /**
     * @return string|null
     */
    public function getBillingDateString(): ?string
    {
        $billingDate = $this->getBillingDate();
        if ($billingDate === null) {
            return null;
        }
        return $billingDate->format('Y-m-d');
    }

    /**
     * @return string|null
     */
    public function getRecurringAmount(): ?string
    {
        return $this->parameters[self::PARAMETER_RECURRING_AMOUNT];
    }

    /**
     * @param string|null $recurringAmount
     */
    public function setRecurringAmount(string $recurringAmount = null)
    {
        $this->parameters[self::PARAMETER_RECURRING_AMOUNT] = $recurringAmount;
    }

    /**
     * @return string|null
     */
    public function getFrequency(): ?string
    {
        return $this->parameters[self::PARAMETER_FREQUENCY];
    }

    /**
     * @param string|null $frequency
     */
    public function setFrequency(string $frequency = null)
    {
        $this->parameters[self::PARAMETER_FREQUENCY] = $frequency;
    }

    /**
     * @return string|null
     */
    public function getCycles(): ?string
    {
        return $this->parameters[self::PARAMETER_CYCLES];
    }

    /**
     * @param string|null $cycles
     */
    public function setCycles(string $cycles = null)
    {
        $this->parameters[self::PARAMETER_CYCLES] = $cycles;
    }
}
