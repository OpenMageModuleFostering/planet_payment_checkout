<?php
/**
 * Class Planet_Pay_Block_Adminhtml_Order_View_Tab_Credit
 */
class Planet_Pay_Block_Adminhtml_Order_View_Tab_Credit extends Mage_Adminhtml_Block_Sales_Order_Abstract
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
        return Mage::helper('planet_pay')->__('Credit Note');
    }

    /**
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('planet_pay')->__('Order Credit Note Info');
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

    /**
     * @return string
     */
    public function getItemsHtml()
    {
        return $this->getChildHtml('order_credit_items');
    }

    /**
     * @return mixed
     */
    public function isDeliveryNoteSent()
    {
        if (is_null(Mage::registry('deliverySent'))) {
            /** @var Planet_Pay_Model_Resource_Orderlog_Collection $orderRequestLogs */
            $orderRequestLogs = Mage::getResourceModel('planet_pay/orderlog_collection');
            $orderRequestLogs->setOrderFilter($this->getPlanetOrder());
            $orderRequestLogs->setMethodFilter('delivery_note');
            $orderRequestLogs->setSuccessFilter();
            if ($orderRequestLogs->count()) {
                Mage::register('deliverySent', true);
            }
        }
        return Mage::registry('deliverySent');
    }

    /**
     * @return Planet_Pay_Model_Order
     */
    public function getPlanetOrder()
    {
        return Mage::registry('planet_order');
    }
}
