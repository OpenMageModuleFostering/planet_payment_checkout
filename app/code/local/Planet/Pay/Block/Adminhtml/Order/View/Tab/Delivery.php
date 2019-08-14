<?php
/**
 * Class Planet_Pay_Block_Adminhtml_Order_View_Tab_Delivery
 */
class Planet_Pay_Block_Adminhtml_Order_View_Tab_Delivery extends Mage_Adminhtml_Block_Sales_Order_Abstract
                                                         implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getSource()
    {
        return $this->getOrder();
    }

    /**
     * Retrieve order totals block settings
     *
     * @return array
     */
    public function getOrderTotalData()
    {
        return array(
            'can_display_total_due'      => true,
            'can_display_total_paid'     => true,
            'can_display_total_refunded' => true,
        );
    }

    public function getOrderInfoData()
    {
        return array(
            'no_use_order_link' => true,
        );
    }


    public function getViewUrl($orderId)
    {
        return $this->getUrl('*/*/*', array('order_id'=>$orderId));
    }

    /**
     * ######################## TAB settings #################################
     */

    /**
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('planet_pay')->__('Delivery Request');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('planet_pay')->__('Order Delivery Request Info');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
