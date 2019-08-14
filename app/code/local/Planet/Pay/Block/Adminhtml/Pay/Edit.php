<?php

/*
 * *
 * * Magento
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
 * Banner module called when Edit the banner details.
 *
 * @author : Chetu
 */

class Planet_Pay_Block_Adminhtml_Pay_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    /**
     * Method to show the Save, Delete and Take Shot button.
     * return array.
     */
    public function __construct() {
        parent::__construct();
        $id = $this->getRequest()->getParam('id');
        $this->_objectId = 'id';
        $this->_blockGroup = 'planet_pay';
        $this->_controller = 'adminhtml_pay';
        $this->_updateButton('save', 'label', Mage::helper('planet_pay')->__('Save Brand'));
        $this->_updateButton('delete', 'label', Mage::helper('planet_pay')->__('Delete Brand'));
        $this->_addButton('saveandcontinue', array(
            'label' => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick' => 'saveAndContinueEdit()',
            'class' => 'save',
                ), -100);

        $this->_formScripts[] = "
    function toggleEditor() {
        if (tinyMCE.getInstanceById('brand_content') == null) {
            tinyMCE.execCommand('mceAddControl', false, 'brand_content');
        } else {
            tinyMCE.execCommand('mceRemoveControl', false, 'brand_content');
        }
    }

    function saveAndContinueEdit(){
        editForm.submit($('edit_form').action+'back/edit/');
    }
";
    }

    public function getHeaderText() {
        if (Mage::registry('brand_data')) {
            $brandinreg = Mage::registry('brand_data');
            return Mage::helper('planet_pay')->__("Edit Brand '%s'", $this->htmlEscape($brandinreg['brandname']));
        } else {
            return Mage::helper('planet_pay')->__('Add Brand');
        }
    }

}
