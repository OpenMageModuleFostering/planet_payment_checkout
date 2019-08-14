<?php
/**
 * order log model for interacting order log table
 * 
 * Class Planet_Pay_Model_Orderlog
 */
class Planet_Pay_Model_Orderlog extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('planet_pay/orderlog');
    }

}