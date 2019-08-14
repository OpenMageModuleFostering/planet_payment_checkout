<?php
/**
 * Class Planet_Pay_Block_Adminhtml_Order_View_Items_Renderer_Default
 */
class Planet_Pay_Block_Adminhtml_Order_View_Items_Renderer_Default extends Mage_Adminhtml_Block_Sales_Order_View_Items_Renderer_Default
{

    /**
     * @return Varien_Object
     */
    public function getSubmitButton()
    {
        $onclick = "submitAndReloadArea($('order-credit').parentNode, '".$this->getSubmitUrl()."')";
        $active = $this->isButtonActive();
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('planet_pay')->__('Send Credit Note'),
                'class'   => 'save',
                'onclick' => $onclick,
                'disabled'=> !$active
            ));
        return $button;
    }

    /**
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->getUrl('*/*/sendProductCreditNote', array('order_id'=>$this->getOrder()->getId(),
                                                                'item_id' =>$this->getItem()->getId()
        ));
    }

    /**
     * @return mixed
     */
    private function isButtonActive()
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
       if (Mage::registry('deliverySent') === true) {
           return ((int)$this->getItem()->getQtyOrdered() - (int)$this->getItem()->getQtyRefunded() > 0);
       }
       return false;
    }

    /**
     * @return Planet_Pay_Model_Order
     */
    public function getPlanetOrder()
    {
        return Mage::registry('planet_order');
    }

} 