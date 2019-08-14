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
 * File to show the Add Banner button and Take Screenshot button for brand urls.
 *
 *
 * @author: Chetu
 */
class Planet_Pay_Block_Adminhtml_Pay extends Mage_Adminhtml_Block_Widget_Grid_Container {
    /**
    * Method to show the add banner, take screenshot button in the header section.
    *return object
    */
    public function __construct() {
        $this->_controller = 'adminhtml_pay';
        $this->_blockGroup = 'planet_pay';
        $this->_headerText = Mage::helper('planet_pay')->__('Brand Manager');
        $this->_addButtonLabel = Mage::helper('planet_pay')->__('Add Brand');
        parent::__construct();
    }

}