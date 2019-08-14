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
 * */

/**
 * Observer model
 *
 * @category    PlanetPayment
 * @package     PlanetPayment_CurrencyManager
 * @author      Diwakar Kumar Singh <diwakars@chetu.com>
 */
class PlanetPayment_CurrencyManager_Model_Observer {

    /**
     * Add value to currency_precision field when an order is placed.
     * 
     * @param Varien_Event_Observer $observer
     * return void
     */
    public function salesOrderPlaceAfter(Varien_Event_Observer $observer) {
        $orderwithipay = false;
        $order = $observer->getEvent()->getOrder();
        $order_currency_code = $order->getData('order_currency_code');
        $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
        $options = Mage::helper('currencymanager')->getOptions(array(), true, $order_currency_code);
        $order->setData('currency_precision', ((($options["precision"] != '' || $options["precision"] == '0') /* && $orderwithipay */) ? $options["precision"] : 2));
        $order->save();
    }

}
