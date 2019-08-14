<?php
/*
 **
 ** Magento
 *
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @Namespace : AsiaCheckout
 * @module : Banner
 * @Block :Adminhtml
 *
 * File to show the brand module information in Grid layout.
 *
 *
 * @author: Chetu
 */
class Planet_Pay_Block_Adminhtml_Pay_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('brandGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }
    /**
    * Method to getbanner in collection.
    *return array.
    */
    protected function _prepareCollection() {
        $collection = Mage::getModel('planet_pay/method')->getCollection();    
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
    * Method to show the Brand detail in Grid Format.
    *return object
    */
    protected function _prepareColumns() {
        $this->addColumn('id', array(
            'header' => Mage::helper('planet_pay')->__('Id'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'id',
        ));
        $this->addColumn('brandname', array(
            'header' => Mage::helper('planet_pay')->__('Brand Name'),
            'align' => 'left',
            'index' => 'brandname',
        ));


        $this->addColumn('brandcode', array(
            'header' => Mage::helper('planet_pay')->__('Brand Code'),
            'align' => 'left',
            'index' => 'brandcode',
        ));
		
		$this->addColumn('active', array(
            'header' => Mage::helper('planet_pay')->__('Active Brand'),
            'align' => 'left',
            'index' => 'active',
        ));
        
        $this->addColumn('brandid', array(
            'header' => Mage::helper('planet_pay')->__('Sort Brand'),
            'align' => 'left',
            'index' => 'brandid',
        ));
        
        $this->addColumn('action', array(
            'header' => Mage::helper('planet_pay')->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => Mage::helper('planet_pay')->__('Edit'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )
            ),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}