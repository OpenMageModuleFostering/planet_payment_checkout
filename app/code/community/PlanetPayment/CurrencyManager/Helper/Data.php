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
 * CurrencyManager helper data
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_CurrencyManager
 * @author     Diwakar Kumar Singh <diwakars@chetu.com>
 */
class PlanetPayment_CurrencyManager_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Store all currency setting options.
     *
     * @var array
     */
    protected $_options = array();

    /**
     * Hold boolean value for in order 
     * 
     * @var bool
     */
    protected $_inOrder = false;

    /**
     * Get options
     * 
     * @param array $options
     * @param boolean $old
     * @param string $currency
     * @return decimal
     */
    public function getOptions($options = array(), $old = false, $currency = "default") {
        $storeId = Mage::app()->getStore()->getStoreId();
        if (!isset($this->_options[$storeId][$currency])) {
            $this->setOptions($currency);
        }
        $newOptions = $this->_options[$storeId][$currency];
        if (count($options) > 0) {
            return $newOptions + $options;
        } else {
            return $newOptions;
        }
    }

    /**
     * Clear options
     * 
     * @param array $options
     * @return array
     */
    public function clearOptionValues($options) {
        $oldOptions = array("position", "format", "display", "precision", "script", "name", "currency", "symbol");
        foreach (array_keys($options) as $optionKey) {
            if (!in_array($optionKey, $oldOptions)) {
                unset($options[$optionKey]);
            }
        }
        return $options;
    }

    /**
     * Check if currency manager is enabled.
     * 
     * @return boolean
     */
    public function isActive() {
        $config = Mage::getStoreConfig('currencymanager/general');
        $storeId = Mage::app()->getStore()->getStoreId();
        return ($this->isInOrder() || (($config['enabled']) && ($storeId > 0)) || (($config['enabledadm']) && ($storeId == 0)) );
    }

    /**
     * Set Currency Manager Options
     * 
     * @param string $currency
     * @return PlanetPayment_CurrencyManager_Helper_Data
     */
    public function setOptions($currency = "default") {
        $config = Mage::getStoreConfig('currencymanager/general');
        $options = array();
        $storeId = Mage::app()->getStore()->getStoreId();
        if ($this->isActive()) {
            if ($this->_inOrder) {
                //get precision from order
                //set $options
                $order_id = Mage::app()->getRequest()->getParam('order_id');
                $invoiceid = Mage::app()->getRequest()->getParam('invoice_id');
                $creditmemoid = Mage::app()->getRequest()->getParam('creditmemo_id');
                if (isset($invoiceid)) {
                    $invoice = Mage::getModel("sales/order_invoice")->load($invoiceid);
                    $order = $invoice->getOrder();
                    $order_id = $order->getId();
                } else if (isset($creditmemoid)) {
                    $creditmemo = Mage::getModel('sales/order_creditmemo')->load($creditmemoid);
                    $order = $creditmemo->getOrder();
                    $order_id = $order->getId();
                }
                if (isset($order_id)) {
                    $order = Mage::getModel('sales/order')->load($order_id);
                    if ($order->getId()) {
                        $currencyPrecision = $order->getData('currency_precision');
                        $options = array('precision' => $currencyPrecision);
                    }
                }
            } else {
                $this->getGeneralSettings($config, $options);
                if (isset($config['countrywise'])) {
                    $this->collectCurrencySettings($config, $currency, $options);
                }
            }
        }
        $this->_options[$storeId][$currency] = $options;
        if (!isset($this->_options[$storeId]["default"])) {
            $this->_options[$storeId]["default"] = $options;
        }
        return $this;
    }

    /**
     * Get general settings
     * 
     * @param array $config
     * @param array $options
     */
    protected function getGeneralSettings($config, &$options) {
        if (isset($config['precision'])) {
            $options['precision'] = min(30, max(-1, (int) $config['precision']));
        }
    }

    /**
     * Collect currency settings
     * 
     * @param array $config
     * @param array $currency
     * @param array $options
     */
    protected function collectCurrencySettings($config, $currency, &$options) {
        $countrywise_data = unserialize($config['countrywise']);
        if (count($countrywise_data['precision']) > 0) {
            $countrywise = array();
            foreach ($countrywise_data['precision'] as $key => $val) {
                $countrywise['currency'][] = $key;
                $countrywise['precision'][] = $val;
            }
        }
        if (count($countrywise['currency']) > 0) {
            $optionsData = array();
            $precision = $this->getCurrencySettings($currency, $countrywise, 'precision', true);
            if ($precision !== false) {
                $optionsData['precision'] = min(30, max(-1, $precision));
                $options['precision'] = $optionsData['precision'];
            }
        }
    }

    /**
     * Check if is in order
     * 
     * @return boolean
     */
    public function isInOrder() {
        //check sales, invoice areas
        $module = Mage::app()->getRequest()->getModuleName();
        $controller = Mage::app()->getRequest()->getControllerName();
        if (in_array($module, array('sales')) || (($module == 'admin') && (strpos($controller, 'sales_order') !== false))) {
            $this->_inOrder = true;
            return true;
        } else {
            $this->_inOrder = false;
            return false;
        }
    }

    /**
     * Get Currency Settings
     * 
     * @param string $currency
     * @param array $countrywise
     * @param array $option
     * @param boolean $int
     * @return boolean
     */
    protected function getCurrencySettings($currency, $countrywise, $option, $int = false) {
        $data = array_combine($countrywise['currency'], $countrywise[$option]);
        if (array_key_exists($currency, $data)) {
            $value = $data[$currency];
            if ($value === "") {
                return false;
            }
            return ($int) ? (int) $value : $value;
        }
        return false;
    }

}
