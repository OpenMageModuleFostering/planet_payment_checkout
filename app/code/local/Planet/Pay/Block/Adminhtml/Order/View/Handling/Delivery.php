<?php
/**
 * Class Planet_Pay_Block_Adminhtml_Order_View_Handling_Delivery
 */
class Planet_Pay_Block_Adminhtml_Order_View_Handling_Delivery extends Mage_Adminhtml_Block_Template
{
    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $onclick = "submitAndReloadArea($('order_delivery_block').parentNode, '".$this->getSubmitUrl()."')";
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('planet_pay')->__('Send Delivery Note'),
                'class'   => 'save',
                'onclick' => $onclick
            ));
        $this->setChild('submit_button', $button);
        return parent::_prepareLayout();
    }

    /**
     * @return Planet_Pay_Model_Resource_Orderlog_Collection
     */
    public function getOrderDeliveryLogs()
    {
        /** @var Planet_Pay_Model_Resource_Orderlog_Collection $orderDeliveryLogs */
        $orderDeliveryLogs = Mage::getResourceModel('planet_pay/orderlog_collection');
        $orderDeliveryLogs->setOrderFilter($this->getPlanetOrder());
        $orderDeliveryLogs->setMethodFilter('delivery_note');
        foreach ($orderDeliveryLogs as $orderDeliveryLog) {
            if ($orderDeliveryLog->getSuccess() == true) {
                Mage::register('deliverySent', true);
            }
        }
        return $orderDeliveryLogs;
    }

    /**
     * Retrieve order model
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('sales_order');
    }

    /**
     * @return Planet_Pay_Model_Order
     */
    public function getPlanetOrder()
    {
        return Mage::registry('planet_order');
    }

    /**
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->getUrl('*/*/sendDeliveryNote', array('order_id'=>$this->getOrder()->getId()));
    }

}
