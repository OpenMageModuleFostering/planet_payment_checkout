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
 * IpayGateway Block Profile List
 *
 * @category   PlanetPayment
 * @package    PlanetPayment_IpayGateway
 * @author     One Pica Codemaster <codemaster@onepica.com>
 */
class Planet_Pay_Block_Profile_List extends Mage_Core_Block_Template {

    /**
     * Retrieve customer model
     *
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer() {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

    /**
     * Returns an array of payment profiles.
     *
     * @return array
     */
    public function getPaymentProfiles() {
        if (!$this->hasData('payment_profiles')) {
            $profilesArray = array();
            $profiles = Mage::getModel('planet_pay/carddetail')->getCollection()
                    ->addCustomerFilter($this->getCustomer());
            foreach ($profiles as $profile) {
                // don't display on frontend
                $profilesArray[] = $profile;
            }
        }
        $this->setPaymentProfiles($profilesArray);
        return $this->getData('payment_profiles');
    }

    /**
     * return the add url
     * 
     * @return string
     */
    public function getAddUrl() {
        return $this->getUrl('*/*/new');
    }

}
