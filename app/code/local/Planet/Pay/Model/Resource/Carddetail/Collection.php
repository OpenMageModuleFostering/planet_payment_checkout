<?php
/**
 * Class Planet_Pay_Model_Resource_Order_Collection
 */
class Planet_Pay_Model_Resource_Carddetail_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('planet_pay/carddetail');
    }
    
    /**
     * To add the customer id to filter the current customer
     * 
     * @param Mage_Customer_Model_Customer $customer
     * @return \Planet_Pay_Model_Resource_Carddetail_Collection
     */
    public function addCustomerFilter(Mage_Customer_Model_Customer $customer) {
        $this->addFieldToFilter('cust_id', $customer->getId());
        return $this;
    }

}