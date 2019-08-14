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
 * Banner module display form with input fields and validation.
 *
 * @author : Chetu
 */
class Planet_Pay_Block_Adminhtml_Pay_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $Brandarrng = Mage::registry('brand_data');
      $fieldset = $form->addFieldset('brand_form', array('legend'=>Mage::helper('planet_pay')->__('Brand information')));
      $fieldset->addField('brandname', 'text', array(
          'label'     => Mage::helper('planet_pay')->__('brandname'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'brandname',
          'value'     => $Brandarrng['brandname']
      ));
      
      $fieldset->addField('brandcode', 'text', array(
          'label'     => Mage::helper('planet_pay')->__('brandcode'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'brandcode',
          'value'      => $Brandarrng['brandcode']
      ));

      $fieldset->addField('active', 'text', array(
          'label'     => Mage::helper('planet_pay')->__('active'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'active',
          'value'      => $Brandarrng['active']
      ));

      $fieldset->addField('brandid', 'text', array(
          'label'     => Mage::helper('planet_pay')->__('brandid'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'brandid',
          'value'      => $Brandarrng['brandid']
      ));
      
      return parent::_prepareForm();
  }
}