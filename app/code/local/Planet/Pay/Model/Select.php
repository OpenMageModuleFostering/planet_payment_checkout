<?php

/**
 * This Is payment model class to process the backend API process
 * 
 * Class Planet_Pay_Model_Select
 */
class Planet_Pay_Model_Select extends Mage_Payment_Model_Method_Abstract {
    /*
     * constant for action order
     */

    const ACTION_ORDER = 'order';

    /**
     * constant for action authorize
     */
    const ACTION_AUTHORIZE = 'authorize';

    /**
     * Contant for action authorize and capture
     */
    const ACTION_AUTHORIZE_CAPTURE = 'authorize_capture';

    /**
     * log file used for common issue
     */
    const LOG_FILE = 'planet_log.txt';

    /**
     * To hold the server credentils needed
     * 
     * @var string 
     */
    protected $_planetPaymentOptions;

    /**
     * Payment method code
     * 
     * @var string
     */
    protected $_code = 'planet_pay';

    /**
     * To hold the form block class
     * 
     * @var string
     */
    protected $_formBlockType = 'planet_pay/form_pay';
    protected $_infoBlockType = 'planet_pay/info_pay';

    /**
     * To check wether gateway or not
     * 
     * @var bool
     */
    protected $_isGateway = true;

    /**
     * To check for authorize support
     * 
     * @var bool
     */
    protected $_canAuthorize = true;

    /**
     * To check for capture support
     * 
     * @var bool
     */
    protected $_canCapture = true;

    /**
     * For partial capture
     * 
     * @var bool
     */
    protected $_canCapturePartial = true;

    /**
     * To check for refund support
     * 
     * @var bool
     */
    protected $_canRefund = true;

    /**
     * To Check for void support
     * 
     * @var bool
     */
    protected $_canVoid = true;

    /**
     * To cehck for cancel invoice support
     * 
     * @var bool
     */
    protected $_canCancelInvoice = false;

    /**
     * Magento assign data for info object
     * 
     * @param mixed $data
     * @return $this
     */
    public function assignData($data) {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        /* This is old code used in previous API */

        //$result  =  Mage::getSingleton('core/session')->getResult();

        $info = $this->getInfoInstance();

        /* This is old code used in previous API */

        /* if(!empty($result) && $result != null){
          $cardarr = $result->toArray();
          $carddetail = $cardarr['card'];
          $info->cc_exp_month = $carddetail['expiryMonth'];
          $info->cc_exp_year = $carddetail['expiryYear'];
          } */

        $info->setPlanetPaymentMethod($data->getPlanetPaymentMethod());
        $onePage = Mage::getSingleton('checkout/type_onepage');
        $onePage->getCheckout()->setPlanetPaymentMethod($data->getPlanetPaymentMethod());
        return $this;
    }

    /**
     * Authorize Method
     * 
     * @param Varien_Object $payment
     * @param type $amount
     * @return \Planet_Pay_Model_Select
     */
    public function authorize(Varien_Object $payment, $amount) {

        $order = $payment->getOrder();
        $orderId = $order->getIncrementId();
        $info = $this->getInfoInstance();
        $status = Mage::getSingleton('core/session')->getResult();
        $token = Mage::getSingleton('core/session')->getToken();
        if (!is_array($status)) {
            $status = $status->toArray();
        }
        // To store the payment card data
        try {
            if (!empty($status['card'])) {
                $payment->setAdditionalInformation($status['threeDSecure']);
                $payment->setCcLast4($status['card']['last4Digits']);
                $payment->setCcOwner($status['card']['holder']);
                $payment->setCcType($status['paymentBrand']);
                $payment->setCcExpMonth($status['card']['expiryMonth']);
                $payment->setCcExpYear($status['card']['expiryYear']);
                $payment->setTransactionId($orderId);
                $payment->setIsTransactionClosed(false);
            } else {
                $resource = Mage::getSingleton('core/resource');
                $readConnection = $resource->getConnection('core_read');
                $table = $resource->getTableName('planet_pay/carddetail');
                $query = $readConnection->select()
                        ->from($table, array('id', 'registeration_id', 'brand_name', 'cc_last_four', 'cc_exp_month', 'cc_exp_year', 'holder')) // select * from tablename or use array('id','title') selected values
                        ->where('registeration_id=?', $token)               // where id =1
                        ->limit('1');
                $results = $readConnection->fetchAll($query);
                $payment->setCcLast4($results[0]['cc_last_four']);
                $payment->setAdditionalInformation($results[0]['id']);
                $payment->setCcOwner($results[0]['holder']);
                $payment->setCcType($results[0]['brand_name']);
                $payment->setCcExpMonth($results[0]['cc_exp_month']);
                $payment->setCcExpYear($results[0]['cc_exp_year']);
            }
            return $this;
        } catch (Exception $ex) {
            Mage::log($ex->getMessage(), null, Planet_Pay_Model_Select::LOG_FILE);
        }
    }

    /**
     * Capture Method
     * 
     * @param Varien_Object $payment
     * @param type $amount
     * @return \Planet_Pay_Model_Select
     */
    public function capture(Varien_Object $payment, $amount) {

        if (!$payment->getTransactionId() && $this->getConfigData('payment_action') == self::ACTION_AUTHORIZE_CAPTURE) {
            $status = Mage::getSingleton('core/session')->getResult();
            $code = $status['result']['code'];
            $description = $status['result']['description'];
			$amount = $payment->amount_ordered;
            return $this->sale($payment, $amount, $code, $description);
        }
        $order = $payment->getOrder();
		$amount = $payment->amount_ordered;
        $result = $this->callApi($payment, $amount, 'CP');
        if (substr($result["status"], 0, 3) !== "000") {
            $errorMsg = $result["status"] . '/' . $result["description"];
        } else {
            Mage::log($result, null, $this->getCode() . 'capture.log');
            //process result here to check status etc as per payment gateway.
            // if invalid status throw exception
            // $code = $result['result']['code'];
            if (substr($result["status"], 0, 3) === "000") {
                $payment->setTransactionId($result['merchantTransactionId']);
                /* $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array('key1' => 'value1', 'key2' => 'value2')); */
            } else {
                Mage::throwException($errorMsg);
            }

            // Add the comment and save the order
        }
        if ($errorMsg) {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    public function sale(Varien_Object $payment, $amount, $code, $description) {
        $order = $payment->getOrder();
        $authresp = Mage::getSingleton('core/session')->getResult();
        if (!is_array($authresp)) {
            $authresp = $authresp->toArray();
        }
        // $result = $this->callApi($payment, $amount, 'DB');
        if (substr($code, 0, 3) !== "000") {
            $errorMsg = $code . '/' . $description;
        } else {
            Mage::log($result, null, $this->getCode() . 'debit.log');
            //process result here to check status etc as per payment gateway.
            // if invalid status throw exception
            // $code = $result['result']['code'];
            if (substr($code, 0, 3) === "000") {
                $payment->setCcLast4($authresp['card']['last4Digits']);
                $payment->setCcOwner($authresp['card']['holder']);
                $payment->setCcType($authresp['paymentBrand']);
                $payment->setCcExpMonth($authresp['card']['expiryMonth']);
                $payment->setCcExpYear($authresp['card']['expiryYear']);
                $payment->setTransactionId(rand(0, 1000));
                /* $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array('key1' => 'value1', 'key2' => 'value2')); */
            } else {
                Mage::throwException($errorMsg);
            }

            // Add the comment and save the order
        }
        if ($errorMsg) {
            $payment->setCcLast4($authresp['card']['last4Digits']);
            $payment->setCcOwner($authresp['card']['holder']);
            $payment->setCcType($authresp['paymentBrand']);
            $payment->setCcExpMonth($authresp['card']['expiryMonth']);
            $payment->setCcExpYear($authresp['card']['expiryYear']);
            $payment->setTransactionId(rand(0, 1000));
        }

        return $this;
    }

    /**
     * 
     * @param Varien_Object $payment
     * @param type $amount
     * @return \Planet_Pay_Model_Select
     */
    public function refund(Varien_Object $payment, $amount) {

        $order = $payment->getOrder();
		$amount = $payment->amount_ordered;
        $result = $this->callApi($payment, $amount, 'RF');
        if (substr($result["status"], 0, 3) !== "000") {
            $errorMsg = $result["status"] . '/' . $result["description"];
            Mage::throwException($errorMsg);
        } else {
            Mage::log($result, null, $this->getCode() . 'refund.log');
            //process result here to check status etc as per payment gateway.
            // if invalid status throw exception
            // $code = $result['result']['code'];
            if (substr($result["status"], 0, 3) === "000") {
                $payment->setTransactionId($result['merchantTransactionId']);
                /* $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array('key1' => 'value1', 'key2' => 'value2')); */
            } else {
                Mage::throwException($errorMsg);
            }

            // Add the comment and save the order
        }
        return $this;
    }

    /**
     * Void Payment
     * 
     * @param Varien_Object $payment
     * @return \Planet_Pay_Model_Select
     */
    public function void(Varien_Object $payment) {
        $order = $payment->getOrder();
		$amount = $payment->amount_ordered;
        $result = $this->callApi($payment, $amount, 'RV');
        if (substr($result["status"], 0, 3) !== "000") {
            $errorMsg = $result["status"] . '/' . $result["description"];
            Mage::throwException($errorMsg);
        } else {
            Mage::log($result, null, $this->getCode() . 'void.log');
            //process result here to check status etc as per payment gateway.
            // if invalid status throw exception
            // $code = $result['result']['code'];
            if (substr($result["status"], 0, 3) === "000") {
                $payment->setTransactionId($result['merchantTransactionId']);
                $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, 'canceled', '', false)->save();
                /* $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array('key1' => 'value1', 'key2' => 'value2')); */
            } else {
                Mage::throwException($errorMsg);
            }

            // Add the comment and save the order
        }
        return $this;
    }

    /**
     * Cancel payment
     * 
     * @param Varien_Object $payment
     * @return \Planet_Pay_Model_Select
     */
    public function cancel(Varien_Object $payment) {
		$amount = $payment->amount_ordered;
        $result = $this->callApi($payment, $amount, 'RV');
        if (substr($result["status"], 0, 3) !== "000") {
            $errorMsg = $result["status"] . '/' . $result["description"];
            Mage::throwException($errorMsg);
        } else {
            Mage::log($result, null, $this->getCode() . 'cancel.log');
            //process result here to check status etc as per payment gateway.
            // if invalid status throw exception
            // $code = $result['result']['code'];
            if (substr($result["status"], 0, 3) === "000") {
                $payment->setTransactionId($result['merchantTransactionId']);
                /* $payment->setTransactionAdditionalInfo(Mage_Sales_Model_Order_Payment_Transaction::RAW_DETAILS, array('key1' => 'value1', 'key2' => 'value2')); */
            } else {
                Mage::throwException($errorMsg);
            }

            // Add the comment and save the order
        }
        return $this;
    }

    /**
     * This method call the gateway backend api
     * 
     * @param Varien_Object $payment
     * @param type $amount
     * @param type $type
     * @return type
     */
    private function callApi(Varien_Object $payment, $amount, $filename) {

        $this->_planetPaymentOptions = Mage::getStoreConfig('payment/planet_pay');
        $order = $payment->getOrder();
        $types = Mage::getSingleton('payment/config')->getCcTypes();
        $cctypes = $payment->getCcType();
        $billingaddress = $order->getBillingAddress();
        $totals = number_format($amount, 2, '.', '');
        $orderId = $order->getIncrementId();
        if ($filename != 'DB') {
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $table = $resource->getTableName('planet_pay/order');
//        $query = 'SELECT payment_response FROM ' . $table . ' WHERE order_id = '
//                . (int) $orderId . ' LIMIT 1';
//        $results = $readConnection->fetchOne($query);
            $query = $readConnection->select()
                    ->from($table, array('payment_response')) // select * from tablename or use array('id','title') selected values
                    ->where('order_id=?', $orderId)               // where id =1
                    ->limit('1');
            $results = $readConnection->fetchOne($query);
            $result_arr = json_decode($results, true);
            $token_payment = $result_arr['id'];
        } else {
            $authresp = Mage::getSingleton('core/session')->getResult($result);
            $authresp = $authresp->toArray();
            $token_payment = $authresp['id'];
        }
        //This code used in previous API

        /* $Ordermodel = Mage::getModel('planet_pay/order')->load($orderId);
          $paymentresposne = $orderdetail->getPaymentResponse();
          $pay_res_arr =  json_decode($paymentresposne->toArray()); */

        $currencyDesc = $order->getOrderCurrencyCode();

        $testMode = Mage::getStoreConfig('payment/planet_pay/test_mode');
        if ($testMode) {
            $url = Mage::getStoreConfig('payment/planet_pay/payment_request_url_test');
            $url.="/v1/payments/" . $token_payment;
        } else {
            $url = Mage::getStoreConfig('payment/planet_pay/payment_request_url_live');
            $url.="/v1/payments/" . $token_payment;
        }
        if ($filename != 'RV') {

            if ($testMode) {
                $fields = array(
                    'referencedPaymentId' => $token_payment,
                    'authentication.userId' => $this->_planetPaymentOptions['authentication_userId_test'],
                    'authentication.password' => $this->_planetPaymentOptions['authentication_password_test'],
                    'authentication.entityId' => $this->_planetPaymentOptions['authentication_entityId_test'],
                    'merchantInvoiceId' => $orderId,
                    'merchantTransactionId' => rand(0, 1000),
                    'currency' => $currencyDesc,
                    'amount' => $totals,
                    'paymentType' => $filename,
                    'testMode' => "EXTERNAL"
                );
            } else {
                $fields = array(
                    'referencedPaymentId' => $token_payment,
                    'authentication.userId' => $this->_planetPaymentOptions['authentication_userId'],
                    'authentication.password' => $this->_planetPaymentOptions['authentication_password'],
                    'authentication.entityId' => $this->_planetPaymentOptions['authentication_entityId'],
                    'merchantInvoiceId' => $orderId,
                    'merchantTransactionId' => rand(0, 1000),
                    'currency' => $currencyDesc,
                    'amount' => $totals,
                    'paymentType' => $filename
                );
            }
        } else {

            if ($testMode) {
                $fields = array(
                    'referencedPaymentId' => $token_payment,
                    'authentication.userId' => $this->_planetPaymentOptions['authentication_userId_test'],
                    'authentication.password' => $this->_planetPaymentOptions['authentication_password_test'],
                    'authentication.entityId' => $this->_planetPaymentOptions['authentication_entityId_test'],
                    'merchantInvoiceId' => $orderId,
                    'merchantTransactionId' => rand(0, 1000),
                    'paymentType' => $filename,
                    'testMode' => "EXTERNAL"
                );
            } else {
                $fields = array(
                    'referencedPaymentId' => $token_payment,
                    'authentication.userId' => $this->_planetPaymentOptions['authentication_userId'],
                    'authentication.password' => $this->_planetPaymentOptions['authentication_password'],
                    'authentication.entityId' => $this->_planetPaymentOptions['authentication_entityId'],
                    'merchantInvoiceId' => $orderId,
                    'merchantTransactionId' => rand(0, 1000),
                    'paymentType' => $filename
                );
            }
        }
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        file_put_contents("var/log/backofficerequestlog.txt", $fields_string, FILE_APPEND);
        $fields_string = substr($fields_string, 0, -1);


        //open connection

        $ch = curl_init($url);

        //set the url, number of POST vars, POST data

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0); // DO NOT RETURN HTTP HEADERS
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // RETURN THE CONTENTS OF THE CALL
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // Timeout on connect (2 minutes)
        //execute post
        $result = curl_exec($ch);
        curl_close($ch);
        $resultJson = json_decode($result, true);
        //return $resultJson;
        file_put_contents("var/log/backofficeresponse.txt", print_r($resultJson, true), FILE_APPEND);
        $coderes = $resultJson['result']['code'];
        $transid = $resultJson['merchantTransactionId'];
        $description = $resultJson['result']['description'];
        return array('status' => $coderes, 'merchantTransactionId' => $transid, 'description' => $description);
    }

    /**
     * @return $this
     */
    public function validate() {
        parent::validate();
        return $this;
    }

    /**
     * To get the order placed redirect url
     * 
     * @return bool
     */
    public function getOrderPlaceRedirectUrl() {
        return false;
    }

}
