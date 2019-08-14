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
 * @package    PlanetPayment_Core
 * @copyright  Copyright (c) 2012 Planet Payment Inc. (http://www.planetpayment.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Order Totals 
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_Core
 * @author     mohd shamim<mohds@chetu.com>
 */
class PlanetPayment_Core_Block_Sales_Order_Totals extends Mage_Sales_Block_Order_Totals {

    /**
     * Initialize order totals array
     *
     * @return Mage_Sales_Block_Order_Totals
     */
    protected function _initTotals() {
        $source = $this->getSource();

        $this->_totals = array();
        $this->_totals['subtotal'] = new Varien_Object(array(
            'code' => 'subtotal',
            'value' => $source->getSubtotal(),
            'label' => $this->__('Subtotal')
        ));


        /**
         * Add shipping
         */
        if (!$source->getIsVirtual() && ((float) $source->getShippingAmount() || $source->getShippingDescription())) {
            $this->_totals['shipping'] = new Varien_Object(array(
                'code' => 'shipping',
                'field' => 'shipping_amount',
                'value' => $this->getSource()->getShippingAmount(),
                'label' => $this->__('Shipping & Handling')
            ));
        }

        /**
         * Add discount
         */
        if (((float) $this->getSource()->getDiscountAmount()) != 0) {
            if ($this->getSource()->getDiscountDescription()) {
                $discountLabel = $this->__('Discount (%s)', $source->getDiscountDescription());
            } else {
                $discountLabel = $this->__('Discount');
            }
            $this->_totals['discount'] = new Varien_Object(array(
                'code' => 'discount',
                'field' => 'discount_amount',
                'value' => $source->getDiscountAmount(),
                'label' => $discountLabel
            ));
        }

        $this->_totals['grand_total'] = new Varien_Object(array(
            'code' => 'grand_total',
            'field' => 'grand_total',
            'strong' => true,
            'value' => $source->getGrandTotal(),
            'label' => $this->__('Grand Total')
        ));

        /**
         * Base grandtotal
         */
        if ($this->needDisplayBaseGrandtotal()) {
            $quoteId = $this->getOrder()->getQuoteId();
            $quote = Mage::getModel('sales/quote')->load($quoteId);
            if ($quote->getId()) {
                $payment_method_code = $quote->getPayment()->getMethodInstance()->getCode();
                $exchangeRate = '';
                if ($this->_isPyc() && $exchangeRate) {
                    $total = $source->getBaseGrandTotal();
                } else {
                    $total = $source->getBaseGrandTotal();
                    $currency = Mage::app()->getStore()->getBaseCurrency()->format($total, array(), true);
                }
				if ($this->getOrder()->isCurrencyDifferent()) {
					$this->_totals['base_grandtotal'] = new Varien_Object(array(
						'code' => 'base_grandtotal',
						'value' => $this->getOrder()->formatBasePrice($source->getBaseGrandTotal()),
						'label' => $this->__('Grand Total to be Charged'),
						'is_formated' => true,
					));
				}
            }
        }
        return $this;
    }

    /**
     * Check if we have display grand total in base currency
     *
     * @return bool
     */
    public function needDisplayBaseGrandtotal() {
        $order = $this->getOrder();
        $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
		if ($order->getBaseCurrencyCode() != $order->getQuoteCurrencyCode()) {
            return true;
        }

        return false;
    }

    /**
     * Check if payment service set as pyc
     * 
     * @return bool
     */
    protected function _isPyc() {
        $order = $this->getOrder();
        $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
    }

    /**
     * Check if payment service set as mcp
     * 
     * @return bool
     */
    protected function _isMcp() {
        $order = $this->getOrder();
        $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
    }

}
