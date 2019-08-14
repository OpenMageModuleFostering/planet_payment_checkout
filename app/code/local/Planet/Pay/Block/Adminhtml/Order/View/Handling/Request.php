<?php
/**
 * Class Planet_Pay_Block_Adminhtml_Order_View_Handling_Request
 */
class Planet_Pay_Block_Adminhtml_Order_View_Handling_Request extends Mage_Adminhtml_Block_Template
{
    /**
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $onclick = "submitAndReloadArea($('order_request_block').parentNode, '".$this->getSubmitUrl()."')";
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('sales')->__('Send Order Request'),
                'class'   => 'save',
                'onclick' => $onclick
            ));
        $this->setChild('submit_button', $button);
        return parent::_prepareLayout();
    }

    /**
     * @return Planet_Pay_Model_Resource_Orderlog_Collection
     */
    public function getOrderRequestLogs()
    {
        /** @var Planet_Pay_Model_Resource_Orderlog_Collection $orderRequestLogs */
        $orderRequestLogs = Mage::getResourceModel('planet_pay/orderlog_collection');
        $orderRequestLogs->setOrderFilter($this->getPlanetOrder());
        $orderRequestLogs->setMethodFilter('order_request');
        return $orderRequestLogs;
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
        return $this->getUrl('*/*/sendOrderRequest', array('order_id'=>$this->getOrder()->getId()));
    }

}
