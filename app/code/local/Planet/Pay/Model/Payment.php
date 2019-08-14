<?php

use PlanetLib\Resources\Translation\ErrorCodes;

/**
 * This is payment model for final processing of payment
 * 
 * Class Planet_Pay_Model_Payment
 */
class Planet_Pay_Model_Payment extends Mage_Core_Model_Abstract {

    /**
     * @var Mage_Checkout_Model_Session
     */
    protected $_checkoutSession;

    /** @var  array */
    protected $_planetPayOptions;

    /** @var  bool */
    protected $_planetTestMode;

    /**
     * @var Mage_Sales_Model_Quote
     */
    protected $_quote = null;

    /**
     *
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var \PlanetLib\Planet
     */
    protected $planet;

    /**
     * This is contructor to initialize variable
     * 
     */
    protected function _construct() {
        $this->_checkoutSession = Mage::getSingleton('checkout/session');
        $this->_planetPayOptions = Mage::getStoreConfig('payment/planet_pay');
        $this->_planetTestMode = $this->_planetPayOptions['test_mode'];

        $autoloader = new Planet_Pay_Model_Autoloader();
        $autoloader->init();

        $this->planet = new \PlanetLib\Planet();
        if ($this->_planetTestMode) {
            $this->planet->setUrl($this->_planetPayOptions['payment_request_url_test']);
        } else {
            $this->planet->setUrl($this->_planetPayOptions['payment_request_url_live']);
        }

        $onePage = Mage::getSingleton('checkout/type_onepage');
        $this->paymentMethod = $onePage->getCheckout()->getPlanetPaymentMethod();
    }

    /**
     * Quote object getter
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote() {
        if ($this->_quote === null) {
            return $this->_checkoutSession->getQuote();
        }
        return $this->_quote;
    }

    /**
     * To get payment form and interact with library
     * 
     * @return string
     */
    public function getForm() {
        /** @var Planet_Pay_Model_Checkout_Type_Onepage $onePage */
        $onePage = Mage::getSingleton('checkout/type_onepage');
        $planetOrderId = $onePage->getCheckout()->getPlanetOrderId();
        $magentoOrderId = Mage::getSingleton('checkout/type_onepage')->getLastOrderId();
        $magentoOrderId = $magentoOrderId + 1;
        /** @var Planet_Pay_Model_Order $planetOrder */
        $planetOrder = Mage::getModel('planet_pay/order');
        if (!is_null($planetOrderId)) {
            $planetOrder->load($planetOrderId);
        }
        $planetOrder->setPaymentStatus('unfinished');
        $planetOrder->save();

        $onePage->getCheckout()->setPlanetOrderId($magentoOrderId);

        $onePage = Mage::getSingleton('checkout/type_onepage');
        $quote = $onePage->getCheckout()->getQuote();

        $form = $this->generateForm($quote, $planetOrder);
        return $form;
    }

    //Below function used in Old API and part of our testing

    /** 	
     * @return string
     */
    /* public function getpaymentForm()
      {
      $url = Mage::getUrl('planet/payment/check', array('form_key' => Mage::getSingleton('checkout/session')->getFormKey(),'payment'  => 'planet_pay'));
      $html .= '<form action="' . $url . '" class="'.paymentWidgets.'">';
      $html .= '</form>';
      return $html;
      } */

    /**
     * Process tokenization resposne
     * 
     * @param type $tokenize
     * @throws Mage_Core_Exception
     */
    public function processPaymentTokenization($tokenize) {
	//echo rand(); die('out');
        $resultniz = $this->tokenizationpayment($tokenize);
        $statusarr = json_decode($resultniz, true);
        file_put_contents("var/log/paymentstatus.txt", print_r($statusarr, true), FILE_APPEND);
        Mage::getSingleton('core/session')->setResult(json_decode($resultniz, true));
        Mage::getSingleton('core/session')->setToken($tokenize);
        $language = Mage::app()->getLocale()->getLocale()->getLanguage();
        /** @var Planet_Pay_Model_Order $planetOrder */
        $planetOrder = Mage::getModel('planet_pay/order');
        $planetCarddetail = Mage::getModel('planet_pay/carddetail');
        /** @var Planet_Pay_Model_Checkout_Type_Onepage $onePage */
        $onePage = Mage::getSingleton('checkout/type_onepage');
        /* $quote = Mage::getModel('sales/quote');
          $payment = $quote->getPayment(); */
        /* $payment = $order->getPayment();
          $resource = Mage::getSingleton('core/resource');
          $readConnection = $resource->getConnection('core_read');
          $table = $resource->getTableName('planet_pay/carddetail');
          //        $query = 'SELECT payment_response FROM ' . $table . ' WHERE order_id = '
          //                . (int) $orderId . ' LIMIT 1';
          //        $results = $readConnection->fetchOne($query);
          $query = $readConnection->select()
          ->from($table, array('id', 'registeration_id', 'brand_name', 'cc_last_four', 'cc_exp_month', 'cc_exp_year', 'holder')) // select * from tablename or use array('id','title') selected values
          ->where('registeration_id=?', $tokenize)               // where id =1
          ->limit('1');
          $results = $readConnection->fetchAll($query); */

        /* $payment->setCcLast4('1234');
          $payment->setCcOwner('shamim');
          $payment->setCcType('visa');
          $payment->setCcExpMonth('02');
          $payment->setCcExpYear('2014'); */

        if (substr($statusarr["result"]["code"], 0, 3) === "000") {


            $onePage->saveOrder();
            $orderId = $onePage->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order');
            $order->load($orderId);
			
			// for orders on hold for review
			if ( $statusarr["result"]["code"] == "000.400.000" ) {
				$order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, 'payment_review', '', false)->save();
			}
            ///
            //$planetOrder->load($result->getOrderId());
            //$planetOrder->setTransactionId($result->getTransactionId());
            $planetOrder->setOrderId($order->getIncrementId());
            $planetOrder->setPaymentStatus('paid');
            $planetOrder->setPaymentResponse($resultniz);
            $planetOrder->save();
        } else {
            $onePage->saveOrder();
            $orderId = $onePage->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order');
            $order->load($orderId);
            //$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, 'decline', '', false)->save();
			$order->setState(Mage_Sales_Model_Order::STATE_CANCELED)->save();
            $planetOrder->setTransactionId($result->getTransactionId());
            $planetOrder->setOrderId($order->getIncrementId());
            $planetOrder->setPaymentStatus('unfinished');
            $planetOrder->setPaymentResponse(json_encode($result->toArray()));
            $planetOrder->save();
            throw new Mage_Core_Exception($result->getMessage());
        }
    }

    public function generateCurrencyRateLookUpRequest() {

        $testurl = '';
        if ($this->_planetTestMode) {
            $testurl = $this->_planetPayOptions['payment_request_url_test'];
        } else {

            $testurl = $this->_planetPayOptions['payment_request_url_live'];
        }

        if ($this->_planetTestMode) {

            $data = array("authentication.userId" => $this->_planetPayOptions['authentication_userId_test'],
                "authentication.password" => $this->_planetPayOptions['authentication_password_test'],
                "authentication.entityId" => $this->_planetPayOptions['authentication_entityId_test']);
        } else {
            $data = array("authentication.userId" => $this->_planetPayOptions['authentication_userId'],
                "authentication.password" => $this->_planetPayOptions['authentication_password'],
                "authentication.entityId" => $this->_planetPayOptions['authentication_entityId']);
        }
        $url = $testurl . "/v1/currencies/conversionRates";
        $fields_string = '';

        foreach ($data as $key => $value) {
            $fields_string[] = $key . '=' . urlencode($value) . '&';
        }
        $urlStringData = $url . '?' . implode('', $fields_string);
        $modfurlStringData = substr_replace($urlStringData, "", -1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it
        curl_setopt($ch, CURLOPT_URL, $modfurlStringData); #set the url and get string together
        $return = curl_exec($ch);
        if ($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
        curl_close($ch);

        return $return;
    }

    /**
     * send payment request with tokenization
     * 
     * @param type $tokenize
     * @return type
     */
    public function tokenizationpayment($tokenize) {

        //one way
        $testurl = '';
        if ($this->_planetTestMode) {
            $testurl = $this->_planetPayOptions['payment_request_url_test'];
        } else {

            $testurl = $this->_planetPayOptions['payment_request_url_live'];
        }
        $quote = Mage::getModel('checkout/session')->getQuote();
        $currency_code = Mage::app()->getStore()->getCurrentCurrencyCode();
        $quoteData = $quote->getData();
        $amount = $quoteData['grand_total'];
        $grandTotal = number_format($amount, 2, '.', '');
        $paymentype = $this->_planetPayOptions['payment_type_test'];

        if ($this->_planetTestMode) {

            $data = "authentication.userId=" . $this->_planetPayOptions['authentication_userId_test'] .
                    "&authentication.password=" . $this->_planetPayOptions['authentication_password_test'] .
                    "&authentication.entityId=" . $this->_planetPayOptions['authentication_entityId_test'] .
                    "&paymentType=$paymentype" .
                    "&currency=$currency_code" .
                    "&amount=$grandTotal";
        } else {
            $data = "authentication.userId=" . $this->_planetPayOptions['authentication_userId'] .
                    "&authentication.password=" . $this->_planetPayOptions['authentication_password'] .
                    "&authentication.entityId=" . $this->_planetPayOptions['authentication_entityId'] .
                    "&paymentType=$paymentype" .
                    "&currency=$currency_code" .
                    "&amount=$grandTotal";
        }

        $params = array('http' => array(
                'method' => 'POST',
                'content' => $data
        ));
        $url = $testurl . "/v1/registrations/" . $tokenize . "/payments";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Length: ' . strlen($data))
        );

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * To process regular Payment
     * 
     * @param $token
     * @throws Mage_Core_Exception
     */
    public function processPaymentResponse($token) {
	
        $result = $this->planet->getResult($token);
        $statusarr = $result->toArray();
        file_put_contents("var/log/paymentstatus.txt", print_r($statusarr, true), FILE_APPEND);
        Mage::getSingleton('core/session')->setResult($statusarr);
        $language = Mage::app()->getLocale()->getLocale()->getLanguage();
        /** @var Planet_Pay_Model_Order $planetOrder */
        $planetOrder = Mage::getModel('planet_pay/order');
        $planetCarddetail = Mage::getModel('planet_pay/carddetail');
        /** @var Planet_Pay_Model_Checkout_Type_Onepage $onePage */
        $onePage = Mage::getSingleton('checkout/type_onepage');

        if ($result->isValid()) {
            $onePage->saveOrder();
            $orderId = $onePage->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order');
            $order->load($orderId);
			
			// for orders on hold for review
			if ( $statusarr["result"]["code"] == "000.400.000" ) {
				$order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, 'payment_review', '', false)->save();
			}
			
            if ($statusarr['registrationId']) {

                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $custid = $customerData->getId();
                $planetCarddetail->setCustId($custid);
                $planetCarddetail->setRegisterationId($statusarr['registrationId']);
                $planetCarddetail->setBrandName($statusarr['paymentBrand']);
                $planetCarddetail->setPaymentType($statusarr['paymentType']);
                $planetCarddetail->setccLastFour($statusarr['card']['last4Digits']);
                $planetCarddetail->setccExpMonth($statusarr['card']['expiryMonth']);
                $planetCarddetail->setCcExpYear($statusarr['card']['expiryYear']);
                $planetCarddetail->setHolder($statusarr['card']['holder']);
            }
            //$planetOrder->load($result->getOrderId());
            $planetOrder->setTransactionId($result->getTransactionId());
            $planetOrder->setOrderId($order->getIncrementId());
            $planetOrder->setPaymentStatus('paid');
            $planetOrder->setPaymentResponse(json_encode($result->toArray()));
            $planetOrder->save();
            $planetCarddetail->save();
        } else {
			$onePage->saveOrder($paymentFailed = true);
			// $onePage->saveOrder();
            //$orderId = $onePage->getCheckout()->getLastOrderId();
			$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            $order = Mage::getModel('sales/order');
            $order->load($orderId);
            $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, 'decline', '', false)->save();
			//$order->setState(Mage_Sales_Model_Order::STATE_CANCELED)->save();
            $planetOrder->setTransactionId($result->getTransactionId());
            $planetOrder->setOrderId($order->getIncrementId());
            $planetOrder->setPaymentStatus('unfinished');
            $planetOrder->setPaymentResponse(json_encode($result->toArray()));
            $planetOrder->save();
			Mage::helper('planet_pay')->sendPaymentFailedEmail($this->getQuote(), 'Payment failed!');
            throw new Mage_Core_Exception($result->getMessage());
        }
    }

    /**
     * To generate payment form by getting token as needed
     * 
     * @param Mage_Sales_Model_Quote $quote
     * @param Planet_Pay_Model_Order $planetOrder
     * @return string
     */
    private function generateForm($quote, $planetOrder) {
        $token = $this->getToken($quote, $planetOrder, $this->paymentMethod);
        Mage::getSingleton('core/session')->setGenerateToken($token);
        //used as part of testing in old code
        /* $url = Mage::getUrl('planet/payment/check', array('form_key' => Mage::getSingleton('checkout/session')->getFormKey(),
          'payment'  => 'planet_pay')); */

        $testMode = Mage::getStoreConfig('payment/planet_pay/test_mode');
        if ($testMode) {
            $javurl = Mage::getStoreConfig('payment/planet_pay/payment_request_url_test');
        } else {
            $javurl = Mage::getStoreConfig('payment/planet_pay/payment_request_url_live');
        }
        $srcurl = $javurl . "/v1/paymentWidgets.js";
        $planetView = new \PlanetLib\View();

        $url = Mage::getUrl('planet/payment/check', array('form_key' => Mage::getSingleton('checkout/session')->getFormKey(),
                    'payment' => 'planet_pay'));
        $planetView->setAction($url);
        $planetView->setSrc($srcurl);
        $planetView->setBrands(array($this->paymentMethod));
        $planetView->setLanguage(Mage::app()->getLocale()->getLocale()->getLanguage());

        //to get the customer info
        //return $planetView->getForm($token);
        return array('token' => $token, 'url' => $url);
    }

    /**
     * To get the token by passing needed credentilas
     * 
     * @param Mage_Sales_Model_Quote $quote
     * @param Planet_Pay_Model_Order $planetOrder
     * @param $paymentMethodCode
     * @return string
     */
    private function getToken($quote, $planetOrder, $paymentMethodCode) {
        $billingAddress = $quote->getBillingAddress();
        $shippingAddress = $quote->getShippingAddress();
        $items = $quote->getAllVisibleItems();
        $customerip = Mage::helper('core/http')->getRemoteAddr();
        $payment = $this->getPayment($quote);

        /** @var Planet_Pay_Model_Method $paymentMethod */
        $paymentMethod = Mage::getModel('planet_pay/method');
        $paymentMethod->loadByBrandcode($paymentMethodCode);

        $customer = array(
            'nameGiven' => $billingAddress->getData('firstname'),
            'nameSurname' => $billingAddress->getData('lastname'),
            'addressStreet' => $billingAddress->getData('street'),
            'addressZip' => $billingAddress->getData('postcode'),
            'addressState' => $billingAddress->getData('region'),
            'addressCity' => $billingAddress->getData('city'),
            'contactIp' => $customerip,
            'contactEmail' => $billingAddress->getData('email'));

        $shippingAddress = $shippingAddress->collectTotals();


        $criterion = array(
            // 'customerDeliveryaddressStreet' => $shippingAddress->getData('street'),
            'customerDeliveryaddressCity' => $shippingAddress->getData('city'),
            'customerDeliveryaddressState' => $shippingAddress->getData('region'),
            //'customerDeliveryaddressState2' => $shippingAddress->getData('region_id'),
            'customerDeliveryaddressCountry' => $shippingAddress->getData('country_id'),
            'customerDeliveryaddressZip' => $shippingAddress->getData('postcode'),
            'customerDeliveryAddressFirstName' => $shippingAddress->getData('firstname'),
            'customerDeliveryAddressLastName' => $shippingAddress->getData('lastname')
        );

        $payment->setCurrency($quote->getQuoteCurrencyCode());
        $payment->setAmount($quote->grand_total);

        $payment->setCustomerData($customer);
        $payment->setCriterions($criterion);

        $planetOrder->setPaymentStatus('unfinished');
        $planetOrder->setTotalOrderSum($payment->getAmount());
        $planetOrder->setPaymentRequest(json_encode($payment->toArray(true, true)));
        $planetOrder->setCreateDate(date('Y-m-d H:i:s', time()));
        $planetOrder->setBrandName($paymentMethod->getBrandcode());
        $planetOrder->save();
        $quotePayment = $this->getQuote()->getPayment();
        $quotePayment->setAdditionalData($paymentMethod->getBrandcode());
        $quotePayment->save();
        $token = $this->planet->generateToken($payment);
        return $token;
    }

    /**
     * To set the payment data
     * 
     * @return \PlanetLib\Payment
     */
    private function getPayment($quote) {
        $payment = new \PlanetLib\Payment();
        $onePage = Mage::getSingleton('checkout/type_onepage');
        $planetOrderId = Mage::getModel("sales/order")->getCollection()->getLastItem()->getIncrementId();
        //$planetOrderId = $onePage->getCheckout()->getPlanetOrderId();
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $billingAddress = $quote->getBillingAddress();
        $countryid = $billingAddress->getData('country_id');
        $custid = $customerData->getId();
        $payment_action = Mage::getStoreConfig('payment/planet_pay/payment_action');
        if ($payment_action != "authorize_capture") {

            $paymenttype = $this->_planetPayOptions['payment_type_test'];
        } else {
            $paymenttype = 'DB';
        }
        if ($this->_planetTestMode) {
            $paymentCredentials = array(
                'authenticationUserId' => $this->_planetPayOptions['authentication_userId_test'],
                'authenticationEntityId' => $this->_planetPayOptions['authentication_entityId_test'],
                //'transactionMode'    => "CONNECTOR_TEST",
                'testMode' => "EXTERNAL",
                'merchantInvoiceId' => $planetOrderId + 1,
                'customermerchantCustomerId' => $custid,
                'billingcountry' => $countryid,
                'authenticationPassword' => $this->_planetPayOptions['authentication_password_test'],
                'paymentType' => $paymenttype
            );
        } else {
            $paymentCredentials = array(
                'authenticationUserId' => $this->_planetPayOptions['authentication_userId'],
                'authenticationEntityId' => $this->_planetPayOptions['authentication_entityId'],
                'transactionMode' => "LIVE",
                //'userLogin'          => $this->_planetPayOptions['user_login'],
                //'testMode' => "EXTERNAL",
                'merchantInvoiceId' => $planetOrderId + 1,
                'customermerchantCustomerId' => $custid,
                'billingcountry' => $countryid,
                'authenticationPassword' => $this->_planetPayOptions['authentication_password'],
                'paymentType' => $paymenttype
            );
        }
        $payment->setCredentials($paymentCredentials);
        return $payment;
    }

}
