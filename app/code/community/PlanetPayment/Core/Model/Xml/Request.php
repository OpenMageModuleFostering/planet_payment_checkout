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
 * @category    PlanetPaymentCoreRequest
 * @package     PlanetPayment_Core
 * @copyright   Copyright (c) 2012 Planet Payment Inc. (http://www.planetpayment.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Planet Payment
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_Core
 * @author     One Pica Codemaster <codemaster@onepica.com>
 */
class PlanetPayment_Core_Model_Xml_Request extends PlanetPayment_Core_Model_Xml_Abstract {

    const LOG_FILE = 'pp_rate.log';
    const GATEWAY_URL_PRODUCTION = 'https://prd.txngw.com';
    const GATEWAY_URL_TESTING = 'http://uap.txngw.com';
     const port = '86';
    const ports = '443';

    /**
     * Returns the response model object
     * 
     * @return object PlanetPayment_Core_Model_Xml_Response 
     */
    protected function _getResponseModel() {
        return Mage::getModel('planet_payment_core/xml_response');
    }

    /**
     * Returns the root node. Two options here, either get a simple xml root node
     * as a varien_simplexml object or get the complete encrypted request wrapped in
     * root node.
     * 
     * @param bool $afterEncrypt
     * @param string $encryptedXml
     * @return Varien_Simplexml_Element 
     */
    protected function _getRootNode($afterEncrypt = false, $encryptedXml = false) {
        $hasEncryption = $this->_hasEncryption();

        $key = $this->_getConfig('key', 'ppcore_general');
        $encryption = $hasEncryption ? '1' : '0';
        if ($afterEncrypt) {
            $rootNodeString = '<REQUEST KEY="' . $key . '" PROTOCOL="1" ENCODING="' . $encryption . '" FMT="1">' . $encryptedXml . '</REQUEST>';
        } else {
            $rootNodeString = '<REQUEST KEY="' . $key . '" PROTOCOL="1" ENCODING="' . $encryption . '" FMT="1"/>';
        }

        return new Varien_Simplexml_Element($rootNodeString);
    }

    /**
     * Transaction request for currency rate look up for mcp
     * 
     * @return PlanetPayment_Core_Model_Xml_Request 
     */
    public function generateCurrencyRateLookUpRequest() {
        try {
            $hasEncryption = $this->_hasEncryption();

            $key = $this->_getConfig('key', 'ppcore_general');
            $encryption = $hasEncryption ? '1' : '0';
            $request = $this->_getRootNode();
            $transaction = $request->addChild('TRANSACTION');
            $fields = $transaction->addChild('FIELDS');
            $fields->addChild('TERMINAL_ID', $this->_getConfig('terminal_id', 'ppcore_general'));
            //$fields->addChild('PIN', $this->_getConfig('pin', 'ppcore_general'));
            $fields->addChild('SERVICE_FORMAT', '0000');
            $fields->addChild('CURRENCY_INDICATOR', '1');
            $fields->addChild('SERVICE', 'CURRENCY');
            $fields->addChild('SERVICE_TYPE', 'RATE');
            $fields->addChild('SERVICE_SUBTYPE', 'QUERY');
            $fields->addChild('QUERY_TYPE', '1');
            // $fields->addChild('FESP_IND', '9'); //added
            //Sending Few Additional data to Gateway
            $this->addAdditionalData($fields, true);
            $this->setTransactionForLog($request);
            $this->setCurrencyRate(true);

            if ($hasEncryption) {
                $this->_encryptRequest($request);
            }

            $this->setTransaction($request);
        } catch (Exception $e) {
            Mage::log($e->getmessage(), null, self::LOG_FILE);
            Mage::throwException($e->getmessage());
        }

        return $this;
    }

    /**
     * Adding Order Id and Incremented Order id and Customer Ip to Request XML
     * 
     * @param obj $fields
     * @param boolean $frontrequest
     */
    public function addAdditionalData($fields, $frontrequest = false) {
        $payment = $this->getPayment();
        if ($payment) {
            $order = $payment->getOrder(); //to create a order object
            //Set User data
            if ($order) {
                $fields->addChild('TICKET', $order->getIncrementId());
                $fields->addChild('USER_DATA_1', $order->getId());
                $fields->addChild('CLIENT_IP', $order->getData('remote_ip'));
                $fields->addChild('FESP_IND', '9');
                //Get Store Details from where order placed...
                $store = $order->getData('store_name');
                $store = str_replace(array("<br>", "\n", "\r"), '-', $store);
                $stores = explode('-', $store);
                $fields->addChild('USER_DATA_3', $stores[0]);
                $fields->addChild('USER_DATA_4', $stores[1]);
                $fields->addChild('USER_DATA_5', $stores[2]);

                $frontrequest = false;
            }
        }

        if ($frontrequest) {
            $fields->addChild('CLIENT_IP', $_SERVER['REMOTE_ADDR']);
            $fields->addChild('FESP_IND', '9');
            $fields->addChild('USER_DATA_3', Mage::app()->getWebsite()->getName());
            $fields->addChild('USER_DATA_4', Mage::app()->getGroup()->getName());
            $fields->addChild('USER_DATA_5', Mage::app()->getStore()->getName());
        }

        $fields->addChild('USER_DATA_6', (string) Mage::getConfig()->getNode()->modules->PlanetPayment_Ppcore->version);
        $fields->addChild('USER_DATA_7', Mage::getVersion());
    }

    /**
     * Sending the request to Planet Payment
     * 
     * @return PlanetPayment_Core_Model_Xml_Request 
     */
    public function send() {
        $transaction = $this->getTransaction();

        if ($transaction) {
            try {
                $isProduction = $this->_isProductionMode();
                if ($isProduction) {
                    $url = self::GATEWAY_URL_PRODUCTION;
                } else {
                    $url = self::GATEWAY_URL_TESTING;
                }
                //Selecting port based on the url
                $port = self::port;
                if (strstr($url, 'https://')) {
                    $port = self::ports;
                }
                //print $transaction->asXML();exit;
                $client = new Zend_Http_Client($url, array('keepalive' => true, 'timeout' => 360));
                $client->getUri()->setPort($port);
                $client->setRawData($transaction->asXML(), 'text/xml');
                $client->setMethod(Zend_Http_Client::POST);
                $response = $client->request()->getBody();

                //Setting response to response model object
                $responseModel = $this->_getResponseModel();
                $responseModel->setCoreRequest($this);
                $responseModel->setCoreResponse($response);
                $this->setResponse($responseModel);
            } catch (Exception $e) {
                Mage::log($e->getmessage(), null, self::LOG_FILE);
                Mage::throwException($e->getmessage());
            }

            return $this;
        } else {
            Mage::log("invalid Transaction", null, self::LOG_FILE);
            Mage::throwException('invalid Transaction');
        }
    }

}
