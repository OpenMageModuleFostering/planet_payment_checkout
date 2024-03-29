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
class PlanetPayment_Core_Model_System_Config_General_Encryption {

    /**
     * To provide the option to dropdown
     * 
     * @return string
     */
    public function toOptionArray() {
        $options = array();
        $options[] = array(
            'value' => "triple-des",
            'label' => "Triple-DES"
        );
        return $options;
    }

}
