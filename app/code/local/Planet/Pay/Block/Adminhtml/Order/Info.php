<?php
/**
 * Class Planet_Pay_Block_Adminhtml_Order_Info
 */
class Planet_Pay_Block_Adminhtml_Order_Info extends Mage_Adminhtml_Block_Template
{
    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('sales_order');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    public function getPlanetOrder()
    {
        $planetOrder =  Mage::getModel('planet_pay/order');
        $planetOrder->loadByMageOrder($this->getOrder());
        return $planetOrder;
    }

}
