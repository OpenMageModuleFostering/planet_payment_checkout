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
 * @package     Planet_Pay
 * @copyright   Copyright (c) 2012 Planet Payment Inc. (http://www.planetpayment.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Planet Payment - Profile Controller
 *
 * @category   PlanetPayment
 * @package    Planet_Pay
 * @author     One Pica Codemaster <codemaster@onepica.com>
 */
class Planet_Pay_ProfileController extends Mage_Core_Controller_Front_Action {

    /**
     * Call the predispatch of parent and set the flag for the same
     */
    public function preDispatch() {
        parent::preDispatch();
        if (!$this->_getSession()->authenticate($this) || !Mage::helper('planet_pay')->isEnabled()) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }

    /**
     * Store CC profile list action.
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->renderLayout();
    }

    /**
     * Edit a payment profile.
     */
    public function editAction() {
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('planet_pay/profile');
        }
        $this->renderLayout();
    }

    /**
     * Add a profile. 
     */
    public function newAction() {
        $this->_forward('edit');
    }

    /**
     * Retrieve customer session object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession() {
        return Mage::getSingleton('customer/session');
    }

}
