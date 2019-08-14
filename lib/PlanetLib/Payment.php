<?php

namespace PlanetLib;

use PlanetLib\Filter\FilterString;
use PlanetLib\Payment\PaymentCriterion;
use PlanetLib\Payment\PaymentCustomer;
use PlanetLib\Filter\FilterRound;

/**
 * Object of this class is used as parameter for \PlanetLib\Planet::generateToken for obtaining token from Planet
 * Parameters identical for project can be loaded automatically from config.ini and overwritten using setters
 * Customer data and order data are transmitted as associative arrays useng setCustomerData and setCriterions
 *
 * Class Payment
 * @package PlanetLib
 */
class Payment {
	
    protected $authenticationUserId;
    protected $authenticationEntityId;
    protected $authenticationPassword;
    protected $paymentType;
    protected $Currency;
    protected $Amount;
    protected $Testmode;
    protected $Planetorderid;
    protected $customermerchantCustomerId;
    protected $billingcountry;

    /**
     * Parameters, identical for whole project can be loaded automatically from config file.
     * They can be overwritten using setters
     */
    public function __construct() {
        if (file_exists("config.ini")) {
            $config = parse_ini_file("config.ini", true);
            $this->fromArray($config['payment']);
        }
    }

    /**
     * @param array $credentials
     * @throws PlanetException
     * @return Payment
     */
    public function setCredentials($credentials) {
        if (!is_array($credentials)) {
            // if no config and no params available, throw exception
            throw new PlanetException("No valid credentials set.");
        }

        $this->_setSequritySender($credentials['authenticationUserId']);
        $this->_setTransactionChannel($credentials['authenticationEntityId']);
        $this->_setUserPassword($credentials['authenticationPassword']);
        $this->_setPaymentType($credentials['paymentType']);
        $this->setCurrency($credentials['Currency']);
        $this->_setAmount($credentials['Amount']);
        $this->setTestMode($credentials['testMode']);
        $this->setPlanetOrderId($credentials['merchantInvoiceId']);
        $this->setCustomerMerchantCustomerId($credentials['customermerchantCustomerId']);
        $this->setBillingCountry($credentials['billingcountry']);

        return $this;
    }

    /**
     * @param array $config
     * @throws PlanetException
     * @return Payment
     */
    public function setPaymentConfiguration($config) {
        if (!is_array($config)) {
            // if no config and no params available, throw exception
            throw new PlanetException("No valid configuration set.");
        }

        $this->setCurrency($config['Currency']);
        $this->_setPaymentType($config['paymentType']);

        return $this;
    }

    /**
     * @param string $currency
     * @return bool|array
     */
    public function setCurrency($currency) {
        $this->Currency = $currency;
        return true;
    }
    /**
     * To set amount
     * 
     * @param int $amount
     * @return boolean
     */
     
    public function setAmount($amount) {
       $this->Amount = FilterRound::round($amount, 2);
        return true;
    }
    /**
     * To set test mode
     * 
     * @param type $text
     * @return boolean
     */
    public function setTestMode($text) {
       $this->Testmode = $text;
        return true;
    }
    /**
     * To set planet order id
     * 
     * @param type $orderid
     * @return boolean
     */
     public function setPlanetOrderId($orderid) {
       $this->Planetorderid = $orderid;
        return true;
    }
    /**
     * To set Customer Merchant id
     * 
     * @param type $orderid
     * @return boolean
     */
     public function setCustomerMerchantCustomerId($setCustMerchantCustomerId) {
       $this->customermerchantCustomerId = $setCustMerchantCustomerId;
        return true;
    }
    /**
     * To set billing country
     * 
     * @param type $billingcountry
     * @return boolean
     */
     public function setBillingCountry($billingcountry) {
       $this->billingcountry = $billingcountry;
        return true;
    }

    /**
     * @param string $amount
     * @return bool|array
     */
    protected function _setAmount($amount) {
        $this->Amount = FilterRound::round($amount, 2);
        return true;
    }
    /**
     *
     * @param array $data
     * @return bool|array
     */
    public function setCustomerData($data) {
        $paymentCustomer = new PaymentCustomer();
        $result = $paymentCustomer->setData($data);
        $this->customerData = $paymentCustomer;
        if (is_array($result)) {
            return $result;
        }
        return true;
    }
    
    /**
     * Data array like this:
     * <code>
     * $data = array(
     *      'customerAddressAdditionalData' => '',
     *      'merchantId'                    => '',
     *      'customerId'                    => '',
     *      'orderShippingCosts'            => 0.00,
     *      'orderCurrency'                 => '',
     *      'orderTransportationCosts'      => 0.00,
     *      'orderAdministrationFee'        => 0.00,
     *      'orderId'                       => '',
     *      'orderExternalInvoiceId'        => '',
     *      'orderExternalReferenceNumber'  => '',
     *      'orderItems'                    => array(
     *          array(
     *              'articleNumber'      => '',
     *              'articleDescription' => '',
     *              'price'              => 0.0,
     *              'quantity'           => 0,
     *              'taxrate'            => 0.0,
     *              'discount'           => 0.0,
     *              'discountPct'        => 0.0,
     *              'ean'                => '',
     *              'grossweight'        => 0.0,
     *              'netweight'          => 0.0
     *          )
     *      )
     * );
     * </code>
     *
     * @param array $data
     * @return bool|array
     */
    public function setCriterions($data) {
        $paymentCriterion = new PaymentCriterion($this);
        $result = $paymentCriterion->setData($data);
        $this->criterionData = $paymentCriterion;
        //$this->_setAmount($this->criterionData->generateCriterionOrderTotalAmount());
        if (is_array($result)) {
            return $result;
        }
        return true;
    }

    public function getCriterions() {
        return $this->criterionData;
    }

    /**
     * @param string $sender
     * @return bool|array
     */
    protected function _setSequritySender($sender) {
        $this->authenticationUserId = $sender;
        return true;
    }

    /**
     * @param string $channel
     * @return bool|array
     */
    protected function _setTransactionChannel($channel) {
        $this->authenticationEntityId = $channel;
        return true;
    }


    /**
     * @param string $pwd
     * @return bool|array
     */
    protected function _setUserPassword($pwd) {
        $this->authenticationPassword = $pwd;
        return true;
    }

    /**
     * @param string $type
     * @return bool|array
     */
    protected function _setPaymentType($type) {
        $this->paymentType = $type;
        return true;
    }

    /**
     * Returns all params as array
     *
     * @param bool $converToString
     * @return array
     */
    public function getRequestParams($converToString = false) {
	
		if(!empty($this->Testmode)){
        $data = array('authentication.userId' => $this->authenticationUserId,
            'authentication.entityId' => $this->authenticationEntityId ,
            'authentication.password' => $this->authenticationPassword ,
            'paymentType' => $this->paymentType,
            'amount' => $this->Amount,
            'currency' => $this->Currency,
            'testMode' => $this->Testmode,
            'merchantInvoiceId' => $this->Planetorderid,
            'customer.merchantCustomerId' => $this->customermerchantCustomerId,
            'billing.country' => $this->billingcountry);
			}
			else{
			$data = array('authentication.userId' => $this->authenticationUserId,
            'authentication.entityId' => $this->authenticationEntityId ,
            'authentication.password' => $this->authenticationPassword ,
            'paymentType' => $this->paymentType,
            'amount' => $this->Amount,
            'currency' => $this->Currency,
            'merchantInvoiceId' => $this->Planetorderid,
            'customer.merchantCustomerId' => $this->customermerchantCustomerId,
            'billing.country' => $this->billingcountry);
			
			}
        if ($converToString) {
            $data = FilterString::convertValuesToString($data);
        }
        if (!is_null($this->customerData)) {
            $data = array_merge($data, $this->customerData->toArray(false, $converToString));
        }
        if (!is_null($this->criterionData)) {
            $data = array_merge($data, $this->criterionData->toArray(false, $converToString));
        }
        return $data;
    }

    /**
     * @param bool $internalNames
     * @param bool $converToString
     * @return array
     */
    public function toArray($internalNames = false, $converToString = false) {
        if ($internalNames) {
		
			if(!empty($this->Testmode)){
					$data = array('authentication.userId' => $this->authenticationUserId,
						'authentication.entityId' => $this->authenticationEntityId,
						'authentication.password' => $this->authenticationPassword,
						'paymentType' => $this->paymentType,
						'amount' => $this->Amount,
						'currency' => $this->Currency,
						'testMode' => $this->Testmode,
						'merchantInvoiceId' => $this->Planetorderid,
						'customer.merchantCustomerId' => $this->customermerchantCustomerId,
						'billing.country' => $this->billingcountry);
				}
			else {
					$data = array('authentication.userId' => $this->authenticationUserId,
						'authentication.entityId' => $this->authenticationEntityId,
						'authentication.password' => $this->authenticationPassword,
						'paymentType' => $this->paymentType,
						'amount' => $this->Amount,
						'currency' => $this->Currency,
						'merchantInvoiceId' => $this->Planetorderid,
						'customer.merchantCustomerId' => $this->customermerchantCustomerId,
						'billing.country' => $this->billingcountry);
				}
            if ($converToString) {
                $data = FilterString::convertValuesToString($data);
            }
            if (!is_null($this->customerData)) {
                $data['customerData'] = $this->customerData->toArray(true, $converToString);
            }
            if (!is_null($this->criterionData)) {
                $data['criterionData'] = $this->criterionData->toArray(true, $converToString);
            }
            return $data;
        } else {
            return $this->getRequestParams();
        }
    }

    /**
     * @return mixed
     */
    public function getAmount() {
        return $this->Amount;
    }

    /**
     * Used for filling all parameters from one array
     *
     * @param $params
     */
    public function fromArray($params) {
        $this->setCredentials($params);
        $this->setPaymentConfiguration($params);
    }

    /**
     * Returns params as string for GET request. Used internally from /PlanetLib/Planet
     *
     * @return string
     */
    public function getRequestData() {
        $data = '';
        $errors = array();
        foreach ($this->getRequestParams() as $paramName => $paramValue) {
            if (is_null($paramValue)) {
                $errors[$paramName] = "Parameter $paramName is not set";
            }
            $data .= $paramName . '=' . urlencode($paramValue) . '&';
        }
        return rtrim($data, "&");
    }

    /**
     * @param $merchantId
     * @param $orderId
     * @return string
     */
    private function getIdentificationTransactionId($merchantId, $orderId) {
        return $merchantId . $orderId;
    }

}
