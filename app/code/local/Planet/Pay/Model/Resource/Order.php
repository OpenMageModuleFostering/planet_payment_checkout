<?php
/**
 * This is order resource model
 * 
 * Class Planet_Pay_Model_Resource_Order
 */
class Planet_Pay_Model_Resource_Order extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('planet_pay/order', 'id');
    }
}