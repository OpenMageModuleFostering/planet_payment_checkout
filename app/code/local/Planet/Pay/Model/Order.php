<?php
/**
 * order resource model
 * 
 * Class Planet_Pay_Model_Order
 */
class Planet_Pay_Model_Order extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('planet_pay/order');
    }

    /**
     * To load the magento order number
     * 
     * @param Mage_Sales_Model_Order $order
     */
    public function loadByMageOrder($order)
    {
        $this->load($order->getIncrementId(), 'order_id');
    }


}