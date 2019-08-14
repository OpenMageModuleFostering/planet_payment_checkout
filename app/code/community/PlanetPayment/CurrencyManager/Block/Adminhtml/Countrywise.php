<?php

/**
 * Planet Payment
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_CurrencyManager
 * @copyright  Copyright (c) 2012 Planet Payment Inc. (http://www.planetpayment.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Countrywise CurrencyManager
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_CurrencyManager
 * @author     Diwakar kumar Singh<diwakars@chetu.com>
 */
class PlanetPayment_CurrencyManager_Block_Adminhtml_Countrywise extends Mage_Adminhtml_Block_System_Config_Form_Field {

    /**
     * Get block's html output
     * 
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) {
        $this->setElement($element);
        $this->setTemplate('currencymanager/countrywise.phtml');
        return $this->toHtml();
    }

    /**
     * Get Allowed Currencies
     * 
     * @return mixed
     */
    protected function getAllowedCurrencies() {
        if (!$this->hasData('allowed_currencies')) {
            $currencies = Mage::app()->getLocale()->getOptionCurrencies();
            $allowedCurrencyCodes = Mage::getSingleton('directory/currency')->getConfigAllowCurrencies();

            $formattedCurrencies = array();
            foreach ($currencies as $currency) {
                $formattedCurrencies[$currency['value']]['label'] = $currency['label'];
            }

            $allowedCurrencies = array();
            foreach ($allowedCurrencyCodes as $currencyCode) {
                $allowedCurrencies[$currencyCode] = $formattedCurrencies[$currencyCode]['label'];
            }

            $this->setData('allowed_currencies', $allowedCurrencies);
        }
        return $this->getData('allowed_currencies');
    }

    /**
     * Get Currency Manager Setting  Value
     * 
     * @param string $key
     * @return string
     */
    protected function _getValue($key) {
        $value = $this->getElement()->getData('value/' . $key);
        if (is_null($value)) {
            $key = explode("/", $key);
            $key = array_shift($key);
            $value = Mage::app()->getConfig()->getNode('default/currencymanager/general/' . $key);
            return (string) $value;
        }
        return $value;
    }

}
