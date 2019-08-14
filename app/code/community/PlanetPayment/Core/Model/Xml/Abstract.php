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
class PlanetPayment_Core_Model_Xml_Abstract extends Mage_Core_Model_Abstract {

    /**
     *
     * @var string
     */
    protected $_generalConfigPath = "planet_payment_ppcore/ppcore_general/";

    /**
     *
     * @var string
     */
    protected $_paymentConfigPath = "payment/ppcore/";

    /**
     *
     * @var int
     */
    protected $_testKey = "123451234512345115432154321543214512345112345123";

    const CONFIG_KEY = 'Magento';
    const APP_NAME = 'PP_Core';

    /**
     * returns planet payment general configurations based on the parameters
     * 
     * @param string $path 
     */
    protected function _getConfig($path, $type = 'payment') {
        if ($type == 'payment') {
            $configPath = $this->_paymentConfigPath . $path;
        } else {
            $configPath = $this->_generalConfigPath . $path;
        }

        return Mage::getStoreConfig($configPath, Mage::app()->getStore()->getId());
    }

    /**
     * return encryption key for general configuration 
     * 
     * @return type int
     */
    protected function _generateKey() {
        if ($this->_isProductionMode()) {
            $hex = $this->_getConfig('encryption_key', 'ppcore_general');
        } else {
            $hex = $this->_testKey;
        }

        $rv = '';
        foreach (str_split($hex, 2) as $b) {
            $rv .= chr(hexdec($b));
        }
        return $rv;
    }

    /**
     * To check for the production mode
     * 
     * @return bool
     */
    protected function _isProductionMode() {
        return $this->_getConfig('url', 'ppcore_general') == 'production';
    }

    /**
     * To check for the encryption set or not
     * 
     * @return bool
     */
    protected function _hasEncryption() {
        return (bool) $this->_getConfig('encryption', 'ppcore_general');
    }

    /**
     * Accespts the request xml and encrypt TRANSACTION NODE
     * 
     * @param Varien_Simplexml_Element $request 
     */
    protected function _encryptRequest(Varien_Simplexml_Element &$request) {
        if ($this->_getConfig('encryption_type', 'ppcore_general') == 'triple-des') {
            $transactionToEncrypt = $request->TRANSACTION;
            $transactionToEncryptAsXml = $transactionToEncrypt->asXML();
            $key = $this->_generateKey();
            $encryptedTransactionXml = base64_encode(mcrypt_encrypt(MCRYPT_3DES, $key, $transactionToEncryptAsXml, MCRYPT_MODE_ECB));
            $request = $this->_getRootNode(true, $encryptedTransactionXml);
        }
    }

    /**
     * Accespts the response xml and decrypt TRANSACTION NODE
     * 
     * @param string $response
     * @return string
     */
    protected function _decryptResponse($response) {
        if ($this->_getConfig('encryption_type', 'ppcore_general') == 'triple-des') {
            $transactionToDecrypt = $response[0];
            $key = $this->_generateKey();
            $response = mcrypt_decrypt(MCRYPT_3DES, $key, base64_decode($transactionToDecrypt), MCRYPT_MODE_ECB);
        }

        return $response;
    }

    /**
     * Generates client name to pass with communications
     * 
     * Parts:
     * - MyERP: the ERP that this connector is for (not always applicable)
     * - Majver: version info for the ERP (not always applicable)
     * - MinVer: version info for the ERP (not always applicable)
     * - MyConnector: Name of the OEM's connector AND the name of the OEM (company)  *required*
     * - Majver: OEM's connector version *required*
     * - MinVer: OEM's connector version *required*
     * 
     * @example Magento,1.4,.0.1,Core by Planet Payment,2,0.1
     * @return string
     */
    protected function _getClientName() {
        $mageVersion = Mage::getVersion();
        $mageVerParts = explode('.', $mageVersion, 2);

        $part = array();
        $part[] = self::CONFIG_KEY;
        $part[] = $mageVerParts[0];
        $part[] = $mageVerParts[1];
        $part[] = self::APP_NAME;
        return implode(',', $part);
    }

}
