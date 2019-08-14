<?php
/**
 * Thos is pay model for cartdetail
 * 
 * Class Planet_Pay_Model_Resource_Order
 */
class Planet_Pay_Model_Resource_Carddetail extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('planet_pay/carddetail', 'id');
    }
}