<?php

/**
 * One Pica
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to codemaster@onepica.com so we can send you a copy immediately.
 * 
 * @category    PlanetPayment
 * @package     PlanetPayment_IpayGateway
 * @copyright   Copyright (c) 2012 Planet Payment Inc. (http://www.planetpayment.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Planet Payment - Ipay main credit card processor class.
 * 
 * @category   PlanetPayment
 * @package    PlanetPayment_IpayGateway
 * @author     One Pica Codemaster <codemaster@onepica.com>
 */
class PlanetPayment_IpayGateway_Model_Ipay extends Mage_Payment_Model_Method_Cc {

    const GATEWAY_URL_PRODUCTION = 'https://prd.txngw.com';
    const GATEWAY_URL_TESTING = 'https://uap.txngw.com';
    const PAYMENT_ACTION_AUTHORIZE = "authorize";
    const PAYMENT_ACTION_AUTHORIZE_CAPTURE = "authorize_capture";
    const PAYMENT_SERVICE_PYC = "pyc";
    const PAYMENT_SERVICE_MCP = "mcp";
    const PAYMENT_SERVICE_NORMAL = "normal";
    const VALIDATE_EXISTING = 1;
    const VALIDATE_NEW = 2;
    const METHOD_CODE = 'ipay';
    const KAPTCHA_URL_PRODUCTION = 'https://ssl.kaptcha.com/logo.htm';
    const KAPTCHA_URL_TESTING = 'https://tst.kaptcha.com/logo.htm';
    //Xmls wil be logged in this file if any error occures while authorising,
    //ie the authorize process is wrapped inside a mysql transaction in magento, 
    //if anything goes wrong,
    //The complete process will be rolled back. Since our database logging
    //is inside this transaction, we will loose the log.
    const LOG_FILE = 'ipay_log.txt';

    /**
     * Hold method code
     * 
     * @var string
     */
    protected $_code = self::METHOD_CODE;

    /**
     * hold the form block type path
     * 
     * @var string
     */
    protected $_formBlockType = 'ipay/payment_form';

    /**
     * Hold the form block for admin path
     * 
     * @var string
     */
    protected $_formBlockTypeAdmin = 'ipay/adminhtml_payment_form';

    /**
     * Hold the info block path
     * 
     * @var string
     */
    protected $_infoBlockType = 'ipay/payment_info';

    /**
     * Hold if gateway is there
     * 
     * @var bool
     */
    protected $_isGateway = true;

    /**
     * Hold boolean value if authorize is enable
     * 
     * @var bool
     */
    protected $_canAuthorize = true;

    /**
     * Hold boolean value if capture is enable
     * 
     * @var bool
     */
    protected $_canCapture = true;

    /**
     * Hold boolean value if partial capture is enable
     * 
     * @var bool
     */
    protected $_canCapturePartial = false;

    /**
     * Hold boolean value if refund is enable
     * 
     * @var bool
     */
    protected $_canRefund = true;

    /**
     * Hold boolean value if partial refund is enable
     * 
     * @var bool
     */
    protected $_canRefundInvoicePartial = true;

    /**
     * Hold boolean value if void is enable
     * 
     * @var bool
     */
    protected $_canVoid = true;

    /**
     * Hold boolean value if can use internal is enable
     * 
     * @var bool
     */
    protected $_canUseInternal = true;

    /**
     * Hold boolean value if guest checkout  is enable
     * 
     * @var bool
     */
    protected $_canUseCheckout = true;

    /**
     * Hold boolean value if multiple shipping is enable
     * 
     * @var bool
     */
    protected $_canUseForMultishipping = true;

    /**
     * Hold boolean value if can save credit card is enable
     * 
     * @var bool
     */
    protected $_canSaveCc = true;

    /**
     * to assign the data
     * 
     * @param Object $data
     * @return Object of class PlanetPayment_IpayGateway_Model_Ipay
     */
    public function assignData($data) {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        if ($data->getIpayProfileId()) {  // use existing profile
            $paymentProfile = Mage::getModel('ipay/profile')
                    ->load($data->getIpayProfileId())
                    ->exportPayment($this->getInfoInstance());
            $this->setValidationMode(self::VALIDATE_EXISTING);
        } else {  // new profile
            parent::assignData($data);
            $this->getInfoInstance()
                    ->setIpayProfileId(null)
                    ->setIsVisible($data->getIsVisible());
            $this->setValidationMode(self::VALIDATE_NEW);
        }
        return $this;
    }

    /**
     * To cature the invoice
     * 
     * @param Varien_Object $payment
     * @param decimal $amount
     * @return void
     */
    public function capture(Varien_Object $payment, $amount) {
        try {
            if (!$payment->getTransactionId() && $this->getConfigData('payment_action') == self::PAYMENT_ACTION_AUTHORIZE_CAPTURE) {
                return $this->sale($payment, $amount);
            }
            $payment->setAmount($amount);
            $request = $this->_getRequest();
            //Overriding the setAmount method based on currency in which order is placed.
            $order = $payment->getOrder();
            $currencyPrecision = $order->getData('currency_precision');
            if (isset($currencyPrecision)) {
                $amount = round($amount, $currencyPrecision);
                $amountInStoreCurrency = round($payment->getOrder()->getGrandTotal(), $currencyPrecision);
            } else {
                $amount = round($amount, 2);
                $amountInStoreCurrency = round($payment->getOrder()->getGrandTotal(), 2);
            }
            $request->setPayment($payment)
                    ->setAmount($amount)
                    ->setAmountInStoreCurrency($amountInStoreCurrency);
            $request->generateRequestForCapture();
            $request->send();
            $response = $request->getResponse();
            if ($response->isSuccess()) {
                $result = $response->getXmlContent();
                Mage::getSingleton('core/session')->setData('fraud_rules', $result->asXML());
                $payment->setStatus(self::STATUS_APPROVED);
                //$payment->setCcTransId($result->getTransactionId());
                $payment->setLastTransId($result->FIELDS->TRANSACTION_ID);
                if (!$payment->getParentTransactionId() || $result->FIELDS->TRANSACTION_ID != $payment->getParentTransactionId()) {
                    $payment->setTransactionId($result->FIELDS->TRANSACTION_ID);
                }
            } else {
                Mage::log($response->getLogInfo(), null, self::LOG_FILE, trues);
                Mage::throwException(Mage::helper('ipay')->__("Couldn't process your request. Please try again later or contact us"));
            }
        } catch (Exception $e) {
            Mage::throwException(Mage::helper('ipay')->__($e->getMessage()));
        }
    }

    /**
     * Authorize and Capture
     * 
     * @param Varien_Object $payment
     * @param decimal $amount 
     */
    public function sale(Varien_Object $payment, $amount) {
        try {
            $payment->setAmount($amount);
            $request = $this->_getRequest();
            //Overriding the setAmount method based on currency in which order is placed.
            $order = $payment->getOrder();
            if (is_object($order)) {
                $currency = $order->getOrderCurrency(); //$order object
                if (is_object($currency)) {
                    $quoteCurrency = $currency->getCurrencyCode();
                }
            } else {
                $quote = Mage::helper('ipay')->getQuote();
                $quoteCurrency = $quote->getQuoteCurrencyCode();
            }
            $helper = Mage::helper('currencymanager');
            $options = $helper->getOptions(array(), true, $quoteCurrency);
            if (isset($options["precision"])) {
                $amount = round($amount, $options["precision"]);
                $amountInStoreCurrency = round($payment->getOrder()->getGrandTotal(), $options["precision"]);
            } else {
                $amount = round($amount, 2);
                $amountInStoreCurrency = round($payment->getOrder()->getGrandTotal(), 2);
            }
            $request->setPayment($payment)
                    ->setAmount($amount)
                    ->setAmountInStoreCurrency($amountInStoreCurrency);
            $request->generateRequestForSale();
            $request->send();
            $response = $request->getResponse();
            if ($response->isSuccess()) {
                $result = $response->getXmlContent();
                Mage::getSingleton('core/session')->setData('fraud_rules', $result->asXML());
                $exchangeRate = $paymentType == self::PAYMENT_SERVICE_PYC ? (1 / $result->FIELDS->PYC_EXCHANGE_RATE) : Mage::helper('ipay')->getQuote()->getBaseToQuoteRate();
                $payment->setStatus(self::STATUS_APPROVED);
                $payment->setIpayExchangeRate($exchangeRate);
                $payment->setIpayMarkup($result->FIELDS->PYC_MARKUP);
                $payment->setIpayServiceUsed(Mage::getStoreConfig('payment/ipay/service'));
                $payment->setLastTransId($result->FIELDS->TRANSACTION_ID);
                $payment->setPycExhangeRate($result->FIELDS->PYC_EXCHANGE_RATE);
                if (!$payment->getParentTransactionId() || $result->FIELDS->TRANSACTION_ID != $payment->getParentTransactionId()) {
                    $payment->setTransactionId($result->FIELDS->TRANSACTION_ID);
                }
            } else {
                Mage::log($response->getLogInfo(), null, self::LOG_FILE, trues);
                Mage::throwException(Mage::helper('ipay')->__("Couldn't process your request. Please try again later or contact us"));
            }
        } catch (Exception $e) {
            Mage::throwException(Mage::helper('ipay')->__($e->getMessage()));
        }
    }

    /**
     * Authorizing payment
     * 
     * @param Varien_Object $payment
     * @param decimal $amount
     * @return object of type PlanetPayment_IpayGateway_Model_Ipay 
     */
    public function authorize(Varien_Object $payment, $amount) {
        if ($amount <= 0) {
            Mage::throwException(Mage::helper('ipay')->__('Invalid amount for authorization.'));
        }

        $payment->setAmount($amount);
        $request = $this->_getRequest();
        $request->setTransactionType(self::PAYMENT_ACTION_AUTHORIZE)
                ->setPayment($payment);
        $paymentType = $this->getPaymentType();
        if ($paymentType == self::PAYMENT_SERVICE_PYC) {
            $quote = Mage::helper('ipay')->getQuote();
            $quoteCurrency = $quote->getQuoteCurrencyCode();
            $helper = Mage::helper('currencymanager');
            $options = $helper->getOptions(array(), true, $quoteCurrency);
            if (isset($options["precision"])) {
                $amount = round($amount, $options["precision"]);
            } else {
                $amount = round($amount, 2);
            }
            $request->setAmount($amount);
            $request->generateRequestForPycAuth();
        } else if ($paymentType == self::PAYMENT_SERVICE_MCP) {
            $quote = Mage::helper('ipay')->getQuote();
            $quoteCurrency = $quote->getQuoteCurrencyCode();
            $helper = Mage::helper('currencymanager');
            $options = $helper->getOptions(array(), true, $quoteCurrency);
            if (isset($options["precision"])) {
                $price = round($quote->getGrandTotal(), $options["precision"]);
            } else {
                $price = round($quote->getGrandTotal(), 2);
            }
            $request->setAmountInStoreCurrency($price);
            //$request->setAmountInStoreCurrency(round(Mage::helper('ipay')->getQuote()->getGrandTotal(), 2));
            $request->generateRequestForMcpAuth();
        } else {
            Mage::throwException(Mage::helper('ipay')->__("Couldn't process your request. Please try agin later."));
        }
        $request->send();
        $response = $request->getResponse();
        if ($response->isSuccess()) {
            $result = $response->getXmlContent();
            Mage::getSingleton('core/session')->setData('fraud_rules', $result->asXML());
            $exchangeRate = $paymentType == self::PAYMENT_SERVICE_PYC ? (1 / $result->FIELDS->PYC_EXCHANGE_RATE) : Mage::helper('ipay')->getQuote()->getBaseToQuoteRate();
            $payment->setCcApproval($result->FIELDS->APPROVAL_CODE)
                    ->setLastTransId($result->FIELDS->TRANSACTION_ID)
                    ->setTransactionId($result->FIELDS->TRANSACTION_ID)
                    ->setIpayExchangeRate($exchangeRate)
                    ->setIpayMarkup($result->FIELDS->PYC_MARKUP)
                    ->setIpayServiceUsed(Mage::getStoreConfig('payment/ipay/service'))
                    ->setIsTransactionClosed(0)
                    ->setCcTransId($result->FIELDS->P3DS_TRANSACTION_ID)
                    ->setPycExhangeRate($result->FIELDS->PYC_EXCHANGE_RATE)
                    ->setStatus(self::STATUS_APPROVED);
        } else {
            //The last added log will be rolled back if error occured. So if any
            //exception occured, the xmls will be logged in var/log/ipay_log.txt
            Mage::log($response->getLogInfo(), null, self::LOG_FILE, trues);
            Mage::throwException(Mage::helper('ipay')->__('Payment authorization error.'));
        }

        return $this;
    }

    /**
     * Void the payment through gateway
     *
     * @param Varien_Object $payment
     * @return PlanetPayment_IpayGateway_Model_Ipay
     */
    public function void(Varien_Object $payment) {
        /* @var $payment Mage_Sales_Model_Order_Payment */
        if ($payment->getParentTransactionId()) {
            $request = $this->_getRequest();
            $request->setPayment($payment);

            $request->generateRequestForVoid()
                    ->send();

            $response = $request->getResponse();

            if ($response->isSuccess()) {
                $payment->setStatus(self::STATUS_SUCCESS);
                return $this;
            } else {
                $payment->setStatus(self::STATUS_ERROR);
                Mage::throwException($this->_wrapGatewayError($result->getResponseReasonText()));
            }
        } else {
            $payment->setStatus(self::STATUS_ERROR);
            Mage::throwException(Mage::helper('ipay')->__('Invalid transaction id'));
        }
    }

    /**
     * Refund the amount with transaction id
     *
     * @param Varien_Object $payment
     * @param decimal $amount
     * @return PlanetPayment_IpayGateway_Model_Ipay
     */
    public function refund(Varien_Object $payment, $amount) {
        /* @var $payment Mage_Sales_Model_Order_Payment */
        if ($payment->getRefundTransactionId() && $amount > 0) {
            if ($amount == $payment->getBaseAmountPaid()) {
                //avoid a rounding error
                $amountConverted = $payment->getAmountPaid();
            } else {
                //calculate the amount based on the payment exchange rate
                $amountConverted = $amount * $payment->getIpayExchangeRate();
            }
            $request = $this->_getRequest();
            //Overriding the setAmount method based on currency in which order is placed.
            $order = $payment->getOrder();
            $currencyPrecision = $order->getData('currency_precision');
            if (isset($currencyPrecision)) {
                $amount = round($amount, $currencyPrecision);
                $amountConverted = round($amountConverted, $currencyPrecision);
            } else {
                $amount = round($amount, 2);
                $amountConverted = round($amountConverted, 2);
            }
            $request->setPayment($payment)
                    ->setAmount($amount)
                    ->setAmountInStoreCurrency($amountConverted);
            $request->generateRequestForRefund()
                    ->send();
            $response = $request->getResponse();
            if ($response->isSuccess()) {
                $payment->setStatus(self::STATUS_SUCCESS);
                return $this;
            } else {
                $payment->setStatus(self::STATUS_ERROR);
                Mage::throwException(Mage::helper('ipay')->__('Error in refunding the payment. Message:' . $response->getMessage()));
            }
        }
        Mage::throwException(Mage::helper('ipay')->__('Error in refunding the payment'));
    }

    /**
     * Identifying the payment type PYC or MCP
     * 
     * @return string 
     */
    public function getPaymentType() {
        $typeConfig = $this->getConfigData("service");
        $nativeCurrency = $this->getConfigData("currency");
        $quoteCurrency = Mage::helper('ipay')->getQuote()->getQuoteCurrencyCode();
        $acceptedCurrencies = explode(",", $this->getConfigData("accepted_currencies"));
        if ($typeConfig == self::PAYMENT_SERVICE_PYC) {
            if ($quoteCurrency == $nativeCurrency) {
                return self::PAYMENT_SERVICE_PYC;
            }
        } else if (in_array($quoteCurrency, $acceptedCurrencies)) {
            return self::PAYMENT_SERVICE_MCP;
        }

        return self::PAYMENT_SERVICE_NORMAL;
    }

    /**
     * Retrieve block type for method form generation
     *
     * @return string
     */
    public function getFormBlockType() {
        if (Mage::app()->getStore()->isAdmin()) {
            return $this->_formBlockTypeAdmin;
        } else {
            return $this->_formBlockType;
        }
    }

    /**
     * returns the Xml request object
     * @return  object PlanetPayment_IpayGateway_Model_Xml_Request
     */
    protected function _getRequest() {
        return Mage::getmodel('ipay/xml_request');
    }

    /**
     * Returns checkout session
     * 
     * @return Object 
     */
    protected function _getSession() {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Validate payment method information object
     *
     * @param   Mage_Payment_Model_Info $info
     * @return  Mage_Payment_Model_Abstract
     */
    public function validate() {
        /*
         * calling parent validate function
         */
        if ($this->getValidationMode() == self::VALIDATE_NEW) {

            $info = $this->getInfoInstance();
            $errorMsg = false;
            $availableTypes = explode(',', $this->getConfigData('cctypes'));

            $ccNumber = $info->getCcNumber();

            // remove credit card number delimiters such as "-" and space
            $ccNumber = preg_replace('/[\-\s]+/', '', $ccNumber);
            $info->setCcNumber($ccNumber);

            $ccType = '';

            if (in_array($info->getCcType(), $availableTypes)) {
                if ($this->validateCcNum($ccNumber)
                        // Other credit card type number validation
                        || ($this->OtherCcType($info->getCcType()) && $this->validateCcNumOther($ccNumber))) {

                    $ccType = 'OT';
                    $ccTypeRegExpList = array(
                        //Solo, Switch or Maestro. International safe
                        //'SS'  => '/^((6759[0-9]{12})|(6334|6767[0-9]{12})|(6334|6767[0-9]{14,15})|(5018|5020|5038|6304|6759|6761|6763[0-9]{12,19})|(49[013][1356][0-9]{12})|(633[34][0-9]{12})|(633110[0-9]{10})|(564182[0-9]{10}))([0-9]{2,3})?$/', // Maestro / Solo
                        'SO' => '/(^(6334)[5-9](\d{11}$|\d{13,14}$))|(^(6767)(\d{12}$|\d{14,15}$))/', // Solo only
                        'SM' => '/(^(5[0678])\d{11,18}$)|(^(6[^05])\d{11,18}$)|(^(601)[^1]\d{9,16}$)|(^(6011)\d{9,11}$)|(^(6011)\d{13,16}$)|(^(65)\d{11,13}$)|(^(65)\d{15,18}$)|(^(49030)[2-9](\d{10}$|\d{12,13}$))|(^(49033)[5-9](\d{10}$|\d{12,13}$))|(^(49110)[1-2](\d{10}$|\d{12,13}$))|(^(49117)[4-9](\d{10}$|\d{12,13}$))|(^(49118)[0-2](\d{10}$|\d{12,13}$))|(^(4936)(\d{12}$|\d{14,15}$))/',
                        'VI' => '/^4[0-9]{12}([0-9]{3})?$/', // Visa
                        'MC' => '/^5[1-5][0-9]{14}$/', // Master Card
                        'AE' => '/^3[47][0-9]{13}$/', // American Express
                        'DI' => '/^6011[0-9]{12}$/', // Discovery
                        'JCB' => '/^(3[0-9]{15}|(2131|1800)[0-9]{11})$/', // JCB
                        'DIN' => '/^(300[0-9]{11}|305[0-9]{11}|36[0-9]{12}|38[0-9]{12})$/', //DINERS
                    );

                    foreach ($ccTypeRegExpList as $ccTypeMatch => $ccTypeRegExp) {
                        if (preg_match($ccTypeRegExp, $ccNumber)) {
                            $ccType = $ccTypeMatch;
                            break;
                        }
                    }

                    if (!$this->OtherCcType($info->getCcType()) && $ccType != $info->getCcType()) {
                        $errorCode = 'ccsave_cc_type,ccsave_cc_number';
                        $errorMsg = $this->_getHelper()->__('Credit card number mismatch with credit card type.');
                    }
                } else {
                    $errorCode = 'ccsave_cc_number';
                    $errorMsg = $this->_getHelper()->__('Invalid Credit Card Number');
                }
            } else {
                $errorCode = 'ccsave_cc_type';
                $errorMsg = $this->_getHelper()->__('Credit card type is not allowed for this payment method.');
            }

            //validate credit card verification number
            if ($errorMsg === false && $this->hasVerification()) {
                $verifcationRegEx = $this->getVerificationRegEx();
                $regExp = isset($verifcationRegEx[$info->getCcType()]) ? $verifcationRegEx[$info->getCcType()] : '';
                if (!$info->getCcCid() || !$regExp || !preg_match($regExp, $info->getCcCid())) {
                    $errorMsg = $this->_getHelper()->__('Please enter a valid credit card verification number.');
                }
            }

            if ($ccType != 'SS' && !$this->_validateExpDate($info->getCcExpYear(), $info->getCcExpMonth())) {
                $errorCode = 'ccsave_expiration,ccsave_expiration_yr';
                $errorMsg = $this->_getHelper()->__('Incorrect credit card expiration date.');
            }

            if ($errorMsg) {
                Mage::throwException($errorMsg);
            }

            //This must be after all validation conditions
            if ($this->getIsCentinelValidationEnabled()) {
                $this->getCentinelValidator()->validate($this->getCentinelValidationData());
            }
        } else {
            Mage_Payment_Model_Method_Abstract::validate();
        }

        return $this;
    }

    /**
     * to return an array of credit cards to be verify
     * 
     * @return array
     */
    public function getVerificationRegEx() {
        $verificationExpList = array(
            'VI' => '/^[0-9]{3}$/', // Visa
            'MC' => '/^[0-9]{3}$/', // Master Card
            'AE' => '/^[0-9]{4}$/', // American Express
            'DI' => '/^[0-9]{3}$/', // Discovery
            'SS' => '/^[0-9]{3,4}$/',
            'SM' => '/^[0-9]{3,4}$/', // Switch or Maestro
            'SO' => '/^[0-9]{3,4}$/', // Solo
            'OT' => '/^[0-9]{3,4}$/',
            'JCB' => '/^[0-9]{4}$/', //JCB
            'DIN' => '/^[0-9]{3}$/', //DINERS
        );
        return $verificationExpList;
    }

}
