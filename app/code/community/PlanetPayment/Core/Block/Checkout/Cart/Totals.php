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
 * Cart Totals 
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_Core
 * @author     mohd shamim<mohds@chetu.com>
 */
class PlanetPayment_Core_Block_Checkout_Cart_Totals extends Mage_Checkout_Block_Cart_Totals {

    /**
     * Check if we have display grand total in base currency
     *
     * @return bool
     */
    public function needDisplayBaseGrandtotal() {
        $quote = $this->getQuote();
        $payment_method_code = $quote->getPayment()->getMethodInstance()->getCode();
        if ($payment_method_code == PlanetPayment_IpayGateway_Model_Ipay::METHOD_CODE) {
            if ($quote->getBaseCurrencyCode() != $quote->getQuoteCurrencyCode() ||
                    ($this->_isPyc() && $quote->getQuoteCurrencyCode() != $quote->getPayment()->getIpayCurrencyCode())) {
                return true;
            }
        } elseif ($payment_method_code == PlanetPayment_Upop_Model_Upop::METHOD_CODE) {
            if ($quote->getBaseCurrencyCode() != $quote->getQuoteCurrencyCode() ||
                    ($this->_isPyc() && $quote->getQuoteCurrencyCode() != $quote->getPayment()->getUpopCurrencyCode())) {
                return true;
            }
        } else {
            if ($quote->getBaseCurrencyCode() != $quote->getQuoteCurrencyCode()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get formated in base currency base grand total value
     *
     * @return string
     */
    public function displayBaseGrandtotal() {
        $firstTotal = reset($this->_totals);
        if ($firstTotal) {
            $quote = $this->getQuote();
            $payment_method_code = $quote->getPayment()->getMethodInstance()->getCode();
            if ($payment_method_code == PlanetPayment_IpayGateway_Model_Ipay::METHOD_CODE) {
                $exchangeRate = $quote->getIpayExchangeRate();
            } elseif ($payment_method_code == PlanetPayment_Upop_Model_Upop::METHOD_CODE) {
                $exchangeRate = $quote->getUpopExchangeRate();
            } else {
                $exchangeRate = '';
            }

            if ($this->_isPyc() && $exchangeRate) {
                $total = $firstTotal->getAddress()->getBaseGrandTotal();
                if ($payment_method_code == PlanetPayment_IpayGateway_Model_Ipay::METHOD_CODE) {
                    $currency_code = $quote->getPayment()->getIpayCurrencyCode();
                } elseif ($payment_method_code == PlanetPayment_Upop_Model_Upop::METHOD_CODE) {
                    $currency_code = $quote->getPayment()->getUpopCurrencyCode();
                }
                $currency = Mage::app()->getLocale()->currency($currency_code)->getSymbol() . " " . number_format($total * $exchangeRate, 2);
            } elseif ($this->_isMcp()) {
                $total = $firstTotal->getAddress()->getGrandTotal();
                $currency = Mage::app()->getStore()->getCurrentCurrency()->format($total, array(), true);
            } else {
                $total = $firstTotal->getAddress()->getBaseGrandTotal();
                $currency = Mage::app()->getStore()->getBaseCurrency()->format($total, array(), true);
            }
            return $currency;
        }
        return '-';
    }

    /**
     * Check the payment service configure in backend
     * 
     * @return int
     */
    protected function _isPyc() {
        $quote = $this->getQuote();
        $payment_method_code = $quote->getPayment()->getMethodInstance()->getCode();
        if ($payment_method_code == PlanetPayment_IpayGateway_Model_Ipay::METHOD_CODE) {
            return Mage::getmodel('ipay/ipay')->getConfigData("service") == PlanetPayment_IpayGateway_Model_Ipay::PAYMENT_SERVICE_PYC;
        } elseif ($payment_method_code == PlanetPayment_Upop_Model_Upop::METHOD_CODE) {
            return Mage::getmodel('upop/upop')->getConfigData("service") == PlanetPayment_Upop_Model_Upop::PAYMENT_SERVICE_PYC;
        }
    }

    /**
     * Check the payment service configure in backend
     * 
     * @return int
     */
    protected function _isMcp() {
        $quote = $this->getQuote();
        $payment_method_code = $quote->getPayment()->getMethodInstance()->getCode();
        if ($payment_method_code == PlanetPayment_IpayGateway_Model_Ipay::METHOD_CODE) {
            return Mage::getmodel('ipay/ipay')->getConfigData("service") == PlanetPayment_IpayGateway_Model_Ipay::PAYMENT_SERVICE_MCP;
        } elseif ($payment_method_code == PlanetPayment_Upop_Model_Upop::METHOD_CODE) {
            return Mage::getmodel('upop/upop')->getConfigData("service") == PlanetPayment_Upop_Model_Upop::PAYMENT_SERVICE_MCP;
        }
    }

}
