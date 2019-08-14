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
 * @Block : Adminhtml.
 * File to show the Left side tab in the Banner module.
 *
 * @author : Chetu
 */
class Planet_Pay_Block_Adminhtml_Pay_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('brand_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('planet_pay')->__('Brand Information'));
  }

  protected function _beforeToHtml()
  {  
    $this->getChildHtml('store_switcher');
    $this->addTab('form_section', array(
          'label'     => Mage::helper('planet_pay')->__('Brand Information'),
          'title'     => Mage::helper('planet_pay')->__('Brand Information'),
          'content'   => $this->getLayout()->createBlock('planet_pay/adminhtml_pay_edit_tab_form')->toHtml(),
        ));
    return parent::_beforeToHtml();
  }
}