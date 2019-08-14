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
 * CurrencyManager helper tax
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_CurrencyManager
 * @author     Diwakar Kumar Singh <diwakars@chetu.com>
 */
class PlanetPayment_CurrencyManager_Helper_Tax extends Mage_Tax_Helper_Data {

    /**
     * Get product price with all tax settings processing 
     * 
     * @param Mage_Catalog_Model_Product $product
     * @param decimal $price
     * @param mixed $includingTax
     * @param mixed $shippingAddress
     * @param mixed $billingAddress
     * @param mixed $ctc
     * @param mixed $store
     * @param mixed $priceIncludesTax
     * @param boolean $roundPrice
     * @return string
     */
    public function getPrice($product, $price, $includingTax = null, $shippingAddress = null, $billingAddress = null, $ctc = null, $store = null, $priceIncludesTax = null, $roundPrice = true) {
        if (!$price) {
            return '';
        }
        
        $store = Mage::app()->getStore($store);
        if (!$this->needPriceConversion($store)) {
            return $price;
        }
        Mage::app()->getStore($store)->setNeedToRound(true);
        if (version_compare(Mage::getVersion(), '1.8.1', '>')) {
            //Helper rewrite
            $result = parent::getPrice($product, $price, $includingTax, $shippingAddress, $billingAddress, $ctc, $store, $priceIncludesTax, $roundPrice);
        } else {
            //Helper rewrite
            $result = parent::getPrice($product, $price, $includingTax, $shippingAddress, $billingAddress, $ctc, $store, $priceIncludesTax);
        }
        Mage::app()->getStore($store)->setNeedToRound(false);
        return $result;
    }

}
