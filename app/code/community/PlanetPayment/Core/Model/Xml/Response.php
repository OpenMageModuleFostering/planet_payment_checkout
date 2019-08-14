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
class PlanetPayment_Core_Model_Xml_Response extends PlanetPayment_Core_Model_Xml_Abstract {

    /**
     * Hold the response xml string
     * 
     * @var object SImpleXML 
     */
    protected $_responseXml = null;

    /**
     * returns the response after decryption
     * 
     * @return object SImpleXML 
     */
    protected function _getResponse() {
        $response = $this->getCoreResponse();
        if ($response) {
            $xmlResponse = simplexml_load_string($response);
            if ($xmlResponse instanceof SimpleXMLElement) {

                if ($this->_hasEncryption()) {
                    $this->_responseXml = simplexml_load_string($this->_decryptResponse($xmlResponse[0]));
                } else {
                    $this->_responseXml = $xmlResponse->RESPONSE;
                }

                //Logging
                //echo print_r($this->_getRequestObject()->getTransactionForLog()->asXML());die;
                $requestXml = $this->_getRequestObject()->getTransactionForLog()->asXML();
                $requestXml = preg_replace("/<ACCOUNT_NUMBER>[0-9]+([0-9]{4})/", '<ACCOUNT_NUMBER>************$1', $requestXml);
                $requestXml = preg_replace("/<CVV>([0-9]+)<\/CVV>/", '<CVV>***</CVV>', $requestXml);

                Mage::log("Print Request : " . $requestXml, null, PlanetPayment_Core_Model_Xml_Request::LOG_FILE, true);
                Mage::log("Print Response : " . $this->_responseXml->asXML(), null, PlanetPayment_Core_Model_Xml_Request::LOG_FILE, true);
            } else {
                Mage::throwException('Invalid response');
            }
        } else {
            Mage::throwException('Invalid response');
        }

        return $this->_responseXml;
    }

    /**
     * Checking whether the request was successful
     * 
     * @return bool
     */
    public function isSuccess() {
        $responseXml = $this->_getResponse();
        if ($responseXml->FIELDS->ARC) {
            if ($responseXml->FIELDS->ARC == '00' && $responseXml->FIELDS->MRC == '00') {
                return true;
            } else {
                $errorHelper = Mage::helper('planet_payment_core/error');
                $arcMsg = $errorHelper->getErrorMessage((string) $responseXml->FIELDS->ARC, 'arc');
                $mrcMsg = $errorHelper->getErrorMessage((string) $responseXml->FIELDS->MRC, 'mrc');
                $this->setMessage("<i> (" . $arcMsg . ", " . $mrcMsg . ")</i><br/>RESPONSE TEXT:" . $responseXml->FIELDS->RESPONSE_TEXT);
            }
        }

        return false;
    }

    /**
     * Returns the Xml content of response
     * 
     * @return object SImpleXML  
     */
    public function getXmlContent() {
        if (!$this->_responseXml) {
            $this->_getResponse();
        }

        return $this->_responseXml;
    }

    /**
     * Returning the request object of this response
     * 
     * @return object
     */
    protected function _getRequestObject() {
        return $this->getCoreRequest();
    }

}
