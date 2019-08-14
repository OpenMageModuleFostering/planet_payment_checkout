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
 * @package     PlanetPayment_Upop
 * @copyright   Copyright (c) 2012 Planet Payment Inc. (http://www.planetpayment.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Planet Payment
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_Upop
 * @author     One Pica Codemaster <codemaster@onepica.com>
 */
class Planet_Pay_Model_Currency_Import_Planet extends Mage_Directory_Model_Currency_Import_Abstract {

    protected $_messages = array();
    protected $_rates = array();

    public function __construct() {

        $request = $this->_getRequestModel()
                ->generateCurrencyRateLookUpRequest();
        $responearr = json_decode($request, true);
        Mage::log($responearr, null, 'pprate.log');
        if (substr($responearr["result"]["code"], 0, 3) === "000") {
            $this->_rates = $this->_formatConvertionXml($responearr['conversionRates']);
        } else {
            Mage::throwException("Communication Error! Please try later");
        }
    }

    protected function _convert($currencyFrom, $currencyTo, $retry = 0) {
        if (count($this->_rates)) {
            try {
                if (isset($this->_rates[$currencyTo])) {
                    return $this->_rates[$currencyTo];
                } else {
                    $this->_messages[] = Mage::helper('planet_pay')->__("Unable to retrieve the conversion rate from %s to %s", $currencyFrom, $currencyTo);
                }
            } catch (Exception $e) {
                if ($retry == 0) {
                    $this->_convert($currencyFrom, $currencyTo, 1);
                } else {
                    $this->_messages[] = Mage::helper('planet_pay')->__('Cannot retrieve rate from planet');
                }
            }
        } else {
            $this->_messages[] = Mage::helper('planet_pay')->__("Unable to retrieve the conversion rate from %s to %s", $currencyFrom, $currencyTo);
        }
    }
     public function runExchangeRateQuery() {
        try {
            $service = 'planet_payment_core';
            $this->_getSession()->setCurrencyRateService($service);
            if (!$service) {
                throw new Exception(Mage::helper('adminhtml')->__('Invalid Import Service Specified'));
            }
            try {
                $importModel = Mage::getModel(
                                Mage::getConfig()->getNode('global/currency/import/services/' . $service . '/model')->asArray()
                );
            } catch (Exception $e) {
                Mage::throwException(Mage::helper('adminhtml')->__('Unable to initialize import model'));
            }
            $rates = $importModel->fetchRates();
            $errors = $importModel->getMessages();
            if (sizeof($errors) > 0) {
                foreach ($errors as $error) {
                    Mage::log($error, null, 'exception.log');
                }
                Mage::log(Mage::helper('adminhtml')->__('All possible rates were fetched, please click on "Save" to apply'), null, 'exception.log');
            } else {
                Mage::log(Mage::helper('adminhtml')->__('All rates were fetched, please click on "Save" to apply'), null, 'exception.log');
            }

            Mage::getSingleton('adminhtml/session')->setRates($rates);


            //Prepare Data Layout
            $newRates = Mage::getSingleton('adminhtml/session')->getRates();
            Mage::getSingleton('adminhtml/session')->unsetData('rates');

            $currencyModel = Mage::getModel('directory/currency');
            $currencies = $currencyModel->getConfigAllowCurrencies();
            $defaultCurrencies = $currencyModel->getConfigBaseCurrencies();
            $oldCurrencies = $this->_prepareRates($currencyModel->getCurrencyRates($defaultCurrencies, $currencies));

            foreach ($currencies as $currency) {
                foreach ($oldCurrencies as $key => $value) {
                    if (!array_key_exists($currency, $oldCurrencies[$key])) {
                        $oldCurrencies[$key][$currency] = '';
                    }
                }
            }

            foreach ($oldCurrencies as $key => $value) {
                ksort($oldCurrencies[$key]);
            }

            sort($currencies);

            $_newRates = $this->_prepareRates($newRates);
            $_oldRates = $oldCurrencies;
            $_rates = ( $_newRates ) ? $_newRates : $_oldRates;
            $data = array();
            foreach ($defaultCurrencies as $_currencyCode) {
                if (isset($_rates[$_currencyCode]) && is_array($_rates[$_currencyCode])) {
                    foreach ($_rates[$_currencyCode] as $_rate => $_value) {
                        $data[$_currencyCode][$_rate] = $_value;
                    }
                }
            }


            if (is_array($data)) {
                try {
                    foreach ($data as $currencyCode => $rate) {
                        foreach ($rate as $currencyTo => $value) {
                            $value = abs(Mage::getSingleton('core/locale')->getNumber($value));
                            $data[$currencyCode][$currencyTo] = $value;
                            if ($value == 0) {
                                Mage::log(Mage::helper('adminhtml')->__('Invalid input data for %s => %s rate', $currencyCode, $currencyTo), null, 'exception.log');
                            }
                        }
                    }


                    Mage::getModel('directory/currency')->saveRates($data);
                    //Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('All valid rates have been saved.'));
                } catch (Exception $e) {
                    //Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    /**
     * Prepare rate for currency
     * 
     * @param type $array
     * @return null
     */
    protected function _prepareRates($array) {
        if (!is_array($array)) {
            return $array;
        }

        foreach ($array as $key => $rate) {
            foreach ($rate as $code => $value) {
                $parts = explode('.', $value);
                if (sizeof($parts) == 2) {
                    $parts[1] = str_pad(rtrim($parts[1], 0), 4, '0', STR_PAD_RIGHT);
                    $array[$key][$code] = join('.', $parts);
                } elseif ($value > 0) {
                    $array[$key][$code] = number_format($value, 4);
                } else {
                    $array[$key][$code] = null;
                }
            }
        }
        return $array;
    }

    protected function _formatConvertionXml($response) {
        $currencyRates = array();
        if ($response) {
            $rates = $response['0']['rates'];
            for ($i = 0; $i < count($rates); $i++) {
                foreach ($rates[$i] as $key => $value) {

                    if ($key == 'currency') {

                        $ratecurr = $value;
                    } elseif ($key == 'value') {

                        $ratefetch = $value;
                    }
                    $currencyRates[$ratecurr] = 1 / (float) $ratefetch;
                }
            }
        }
        return $currencyRates;
    }

    protected function _getRequestModel() {
        return Mage::getSingleton('planet_pay/payment');
    }

}
