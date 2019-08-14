<?php
/**
 * This is order log model
 * 
 * Class Planet_Pay_Model_Resource_Orderlog
 */
class Planet_Pay_Model_Resource_Orderlog extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('planet_pay/orderlog', 'id');
    }
}