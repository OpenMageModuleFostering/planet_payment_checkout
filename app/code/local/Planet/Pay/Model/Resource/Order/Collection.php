<?php
/**
 * Class Planet_Pay_Model_Resource_Order_Collection
 */
class Planet_Pay_Model_Resource_Order_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('planet_pay/order');
    }

}