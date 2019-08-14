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
 * @Controller :CategoryController
 *
 * File to show the Banner Form to add new banner, save edit and delete the banners.
 * File to add the wyswing editor and perform mass delete.
 *
 * @author: Chetu
 */

class Planet_Pay_Adminhtml_BrandController extends Mage_Adminhtml_Controller_action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('planet_pay/items')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    /**
     * Method to Edit the banner in banner module.
     * return object
     * 
     */
    public function editAction() {
        $brandId = $this->getRequest()->getParam('id');
        $brandModel = Mage::getModel('planet_pay/method')->load($brandId);
        if ($brandModel->getId() || $brandId == 0) {
            Mage::register('brand_data', $brandModel);
            $this->loadLayout();
            $this->_setActiveMenu('brand/set_time');
            $this->_addBreadcrumb('brand Manager', 'brand Manager');
            $this->_addBreadcrumb('Brand Description', 'Brand Description');
            $this->getLayout()->getBlock('head')
                    ->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()
                            ->createBlock('planet_pay/adminhtml_pay_edit'))
                    ->_addLeft($this->getLayout()
                            ->createBlock('planet_pay/adminhtml_pay_edit_tabs')
            );
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')
                    ->addError('Brand does not exist');
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        $this->_redirect('*/*/edit');
    }

    /**
     * Method to Save the banner in banner module.
     * return object.
     * 
     */
    public function saveAction() {

        if ($this->getRequest()->getPost()) {
            try {
                $postData = $this->getRequest()->getPost();
                $brandModel = Mage::getModel('planet_pay/method');
                if ($this->getRequest()->getParam('id') <= 0)
                    $brandModel->setCreatedTime(
                            Mage::getSingleton('core/date')
                                    ->gmtDate()
                    );
                $brandModel
                        ->addData($postData)
                        ->setUpdateTime(
                                Mage::getSingleton('core/date')
                                ->gmtDate())
                        ->setId($this->getRequest()->getParam('id'))
                        ->save();
                Mage::getSingleton('adminhtml/session')
                        ->addSuccess('successfully saved');
                Mage::getSingleton('adminhtml/session')
                        ->settestData(false);
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')
                        ->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')
                        ->settestData($this->getRequest()
                                ->getPost()
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()
                            ->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
        //  $this->renderLayout();
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $brandModel = Mage::getModel('planet_pay/method');
                $brandModel->setId($this->getRequest()
                                ->getParam('id'))
                        ->delete();
                Mage::getSingleton('adminhtml/session')
                        ->addSuccess('successfully deleted');
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')
                        ->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

}
