<?php

/**
 * One Pica
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to codemaster@onepica.com so we can send you a copy immediately.
 * 
 * @category    PlanetPayment
 * @package     PlanetPayment_Core
 * @copyright   Copyright (c) 2012 Planet Payment Inc. (http://www.planetpayment.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Planet Payment
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_Core
 * @author     One Pica Codemaster <codemaster@onepica.com>
 */
class PlanetPayment_Core_Model_System_Config_Payment_Currency extends Mage_Adminhtml_Model_System_Config_Source_Currency {

    /**
     * Allowed Currencies and Add New currecny code to the array inorder to show that in the select box
     * 
     * @var array
     */
    private $__allowedCurrencies = array(
        'USD',
        'CAD',
        'HKD'
    );

    /**
     * To allow multiple currency to be set 
     * 
     * @param type $isMultiSelect
     * @return array
     */
    public function toOptionArray($isMultiSelect) {
        $options = parent::toOptionArray($isMultiSelect);
        $allowedOptions = array();
        foreach ($options as $option) {
            if (isset($option['value'])) {
                if (in_array($option['value'], $this->__allowedCurrencies)) {
                    array_push($allowedOptions, $option);
                }
            }
        }
        return $allowedOptions;
    }

}
