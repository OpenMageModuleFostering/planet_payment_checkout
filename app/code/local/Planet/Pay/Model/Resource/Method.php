<?php
/**
 * This is planet pay model class
 * 
 * Class Planet_Pay_Model_Resource_Method
 */
class Planet_Pay_Model_Resource_Method extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('planet_pay/method', 'id');
    }
}