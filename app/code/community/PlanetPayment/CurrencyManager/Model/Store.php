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
 * Store model
 *
 * @category    PlanetPayment
 * @package     PlanetPayment_CurrencyManager
 * @author      Diwakar Kumar Singh <diwakars@chetu.com>
 */
class PlanetPayment_CurrencyManager_Model_Store extends Mage_Core_Model_Store {

    /**
     * Round val up to precision
     * 
     * @param decimal $price
     * @return float
     */
    public function roundPrice($price) {
        if (Mage::app()->getStore()->getNeedToRound()) {
            return $price;
        }

        $options = Mage::helper('currencymanager')->getOptions();
        return round($price, isset($options["precision"]) ? $options["precision"] : 2);
    }

}
