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
 * @package     PlanetPayment_IpayGateway
 * @copyright   Copyright (c) 2012 Planet Payment Inc. (http://www.planetpayment.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Planet Payment - Provide Option for Payment Action
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_IpayGateway
 * @author     One Pica Codemaster <codemaster@onepica.com>
 */
class Planet_Pay_Model_System_Config_Payment_PaymentAction {

    /**
     * To provide option for authorize and authorize_capture
     * 
     * @return array
     */
    public function toOptionArray() {
        return array(
            array(
                'value' => Planet_Pay_Model_Select::ACTION_AUTHORIZE,
                'label' => Mage::helper('planet_pay')->__('Authorize Only')
            ),
            array(
                'value' => Planet_Pay_Model_Select::ACTION_AUTHORIZE_CAPTURE,
                'label' => Mage::helper('planet_pay')->__('Authorize and Capture')
            )
        );
    }

}
