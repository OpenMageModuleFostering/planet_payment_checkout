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
 * Currency model
 *
 * @category    PlanetPayment
 * @package     PlanetPayment_CurrencyManager
 * @author      Diwakar Kumar Singh <diwakars@chetu.com>
 */
class PlanetPayment_CurrencyManager_Model_Currency extends Mage_Directory_Model_Currency {

    /**
     * Apply currency format to number with specific rounding precision 
     * 
     * @param double $price
     * @param array $options
     * @param bool $includeContainer
     * @param bool $addBrackets
     * @return String
     */
    public function format($price, $options = array(), $includeContainer = true, $addBrackets = false) {
        $helper = Mage::helper('currencymanager');
        return $this->formatPrecision(
                        $price, isset($options["precision"]) ? $options["precision"] : 2, $helper->clearOptionValues($options), $includeContainer, $addBrackets
        );
    }

    /**
     * Returns the formatted price
     * 
     * @param double $price
     * @param array $options
     * @return String
     */
    public function formatTxt($price, $options = array()) {
        $helper = Mage::helper('currencymanager');
        $answer = parent::formatTxt($price, $helper->clearOptionValues($options));

        if ($helper->isActive()) {
            $moduleName = Mage::app()->getRequest()->getModuleName();

            $optionsAdvanced = $helper->getOptions($options, false, $this->getCurrencyCode());
            $options = $helper->getOptions($options, true, $this->getCurrencyCode());
            if (isset($options["precision"])) {
                $price = round($price, $options["precision"]);
            }
            $answer = parent::formatTxt($price, $options);
            if (count($options) > 0) {
                if (($moduleName == 'admin')) {
                    $answer = parent::formatTxt($price, $helper->clearOptionValues($options));
                }
                $minDecimalCount = $optionsAdvanced['precision'];
                if ($minDecimalCount <= $options['precision']) {
                    $options['precision'] = $minDecimalCount;
                }
                $answer = $this->formatWithPrecision($options, $optionsAdvanced, $price, $answer);
            }
        }
        return $answer;
    }

    /**
     * Returns the formatted price
     * 
     * @param int $options
     * @param array $optionsAdvanced
     * @param double $price
     * @param string $answer
     * @return String
     */
    protected function formatWithPrecision($options, $optionsAdvanced, &$price, $answer) {
        $helper = Mage::helper('currencymanager');
        if (isset($optionsAdvanced['precision'])) {
            $price = round($price, $optionsAdvanced['precision']);
            if ($optionsAdvanced['precision'] < 0) {
                $options['precision'] = 0;
            }
            if (!isset($price)) {
                $price = 0;
            }
            return parent::formatTxt($price, $helper->clearOptionValues($options));
        }
        return $answer;
    }

    /**
     * Convert price to currency format
     * 
     * @param double $price
     * @param int $toCurrency
     * @return String
     */
    public function convert($price, $toCurrency = null) {
        $result = parent::convert($price, $toCurrency);
        $data = new Varien_Object(array(
            "price" => $price,
            "toCurrency" => $toCurrency,
            "result" => $result
        ));
        Mage::dispatchEvent("currency_convert_after", array("conversion" => $data));
        return $data->getData("result");
    }

}
