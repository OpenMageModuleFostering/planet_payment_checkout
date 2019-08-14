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
 * Locale model
 *
 * @category    PlanetPayment
 * @package     PlanetPayment_CurrencyManager
 * @author      Diwakar Kumar Singh <diwakars@chetu.com>
 */
class PlanetPayment_CurrencyManager_Model_Locale extends Mage_Core_Model_Locale {

    /**
     * Create Zend_Currency object for current locale
     * 
     * @param string $ccode
     * @return string
     */
    public function currency($ccode) {
        $admcurrency = parent::currency($ccode);
        $options = Mage::helper('currencymanager')->getOptions(array(), true, $ccode);
        $admcurrency->setFormat($options, $ccode);

        return $admcurrency;
    }

    /**
     * returns array with price formatting info for js function formatCurrency in js/varien/js.js
     * 
     * @return array
     */
    public function getJsPriceFormat() {
        // For JavaScript prices
        $parentFormat = parent::getJsPriceFormat();
        $options = Mage::helper('currencymanager')->getOptions(array());
        if (isset($options["precision"])) {
            $parentFormat["requiredPrecision"] = $options["precision"];
            $parentFormat["precision"] = $options["precision"];
        }

        return $parentFormat;
    }

}
