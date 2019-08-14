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
 * Product Price Format
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_CurrencyManager
 * @author     Diwakar kumar Singh<diwakars@chetu.com>
 */
class PlanetPayment_CurrencyManager_Block_Adminhtml_Price extends Mage_Adminhtml_Block_Catalog_Product_Helper_Form_Price {

    /**
     * Get Escaped Value
     * 
     * @param mixed $index
     * @return string
     */
    public function getEscapedValue($index = null) {
        $options = Mage::helper('currencymanager')->getOptions(array());
        $value = $this->getValue();
        if (!is_numeric($value)) {
            return null;
        }
        if (isset($options['precision'])) {
            return number_format($value, $options['precision'], null, '');
        }
        return parent::getEscapedValue($index);
    }

}
