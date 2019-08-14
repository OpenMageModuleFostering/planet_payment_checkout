<?php
/**
 * Class Planet_Pay_Block_Adminhtml_Order
 */
class Planet_Pay_Block_Adminhtml_Order extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    /**
     *
     */
    public function __construct()
    {
        $this->_controller = 'planet_order';
        $this->_blockGroup = 'planet_pay';
        $this->_headerText = Mage::helper('planet_pay')->__('Planet Order Manager');
        parent::__construct();
        $this->_removeButton('add');
    }

}
