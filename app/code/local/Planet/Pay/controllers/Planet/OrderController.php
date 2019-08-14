<?php
/**
 * Include the mage order controller
 */
require_once Mage::getModuleDir('controllers', 'Mage_Adminhtml') . DS . 'Sales' . DS . 'OrderController.php';

/**
 * This class used for managing planet order in backend 
 * 
 * Class Planet_Pay_Planet_OrderController
 */
class Planet_Pay_Planet_OrderController extends Mage_Adminhtml_Sales_OrderController {

    /**
     * This to initilize the order sale action
     * 
     * @return $this|Mage_Adminhtml_Sales_OrderController
     */
    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('sales/planet_orders')
                ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
                ->_addBreadcrumb($this->__('Planet Orders'), $this->__('Planet Orders'));

        return $this;
    }

    /**
     * 
     * View order detale
     */
    public function viewAction() {
        $this->_title($this->__('Sales'))->_title($this->__('Orders'));

        $order = $this->_initOrder();
        $planetOrder = $this->getPlanetOrder($order);

        if ($order) {
            $isActionsNotPermitted = false;
            $ver = new Mage;
            $version = $ver->getVersion();
            if ($version >= '1.8.1.0') {
                $isActionsNotPermitted = $order->getActionFlag(
                        Mage_Sales_Model_Order::ACTION_FLAG_PRODUCTS_PERMISSION_DENIED
                );
            }
            if ($isActionsNotPermitted) {
                $this->_getSession()->addError($this->__('You don\'t have permissions to manage this order because of one or more products are not permitted for your website.'));
            }

            $this->_initAction();

            $this->_title(sprintf("#%s", $order->getRealOrderId()));

            $this->renderLayout();
        }
    }

    /**
     * send order request action
     */
    public function sendOrderRequestAction() {
        $order = $this->_initOrder();
        $planetOrder = $this->getPlanetOrder($order);

        if ($planetOrder) {
            try {
                $response = false;
                /** @var Planet_Pay_Model_Handling $orderRequest */
                $orderRequest = Mage::getModel('planet_pay/handling');
                $orderRequest->sendOrderRequest($planetOrder);
                $this->loadLayout('empty');
                $this->renderLayout();
            } catch (Mage_Core_Exception $e) {
                $response = array(
                    'error' => true,
                    'message' => $e->getMessage(),
                );
            } catch (Exception $e) {
                $response = array(
                    'error' => true,
                    'message' => $this->__('Cannot send Order Request: ' . $e->getMessage())
                );
            }
            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
                $this->getResponse()->setBody($response);
            }
        }
    }

    /**
     * send order delivery note action
     */
    public function sendDeliveryNoteAction() {
        $order = $this->_initOrder();
        $planetOrder = $this->getPlanetOrder($order);

        if ($planetOrder) {
            try {
                $response = false;
                $data = $this->getRequest()->getPost('delivery');
                /** @var Planet_Pay_Model_Handling $orderRequest */
                $orderRequest = Mage::getModel('planet_pay/handling');
                $orderRequest->sendDeliveryNote($planetOrder, $data);
                $this->loadLayout('empty');
                $this->renderLayout();
            } catch (Mage_Core_Exception $e) {
                $response = array(
                    'error' => true,
                    'message' => $e->getMessage(),
                );
            } catch (Exception $e) {
                $response = array(
                    'error' => true,
                    'message' => $this->__('Cannot send Delivery Note')
                );
            }
            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
                $this->getResponse()->setBody($response);
            }
        }
    }

    /**
     * send credit note action
     */
    public function sendCreditNoteAction() {
        $order = $this->_initOrder();
        $planetOrder = $this->getPlanetOrder($order);

        if ($planetOrder) {
            try {
                $response = false;
                /** @var Planet_Pay_Model_Handling $orderRequest */
                $orderRequest = Mage::getModel('planet_pay/handling');
                $orderRequest->sendCreditNote($planetOrder, array());
                $this->loadLayout('empty');
                $this->renderLayout();
            } catch (Mage_Core_Exception $e) {
                $response = array(
                    'error' => true,
                    'message' => $e->getMessage(),
                );
            } catch (Exception $e) {
                $response = array(
                    'error' => true,
                    'message' => $this->__('Cannot send Credit Note')
                );
            }
            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
                $this->getResponse()->setBody($response);
            }
        }
    }

    /**
     * send product credit note action
     */
    public function sendProductCreditNoteAction() {
        $order = $this->_initOrder();
        $planetOrder = $this->getPlanetOrder($order);
        $itemId = $this->getRequest()->getParam('item_id');
        $requestParams = $this->getRequest()->getParam('credit_request');

        if ($planetOrder && isset($requestParams['items'][$itemId])) {
            try {
                $response = false;
                /** @var Planet_Pay_Model_Handling $orderRequest */
                $orderRequest = Mage::getModel('planet_pay/handling');
                $orderRequest->sendProductCreditNote($planetOrder, $order, array(
                    'id' => $itemId,
                    'params' => $requestParams['items'][$itemId]
                ));
                $this->loadLayout('empty');
                $this->renderLayout();
            } catch (Mage_Core_Exception $e) {
                $response = array(
                    'error' => true,
                    'message' => $e->getMessage(),
                );
            } catch (Exception $e) {
                $response = array(
                    'error' => true,
                    'message' => $this->__('Cannot send Credit Note')
                );
            }
            if (is_array($response)) {
                $response = Mage::helper('core')->jsonEncode($response);
                $this->getResponse()->setBody($response);
            }
        }
    }

    /**
     * To get planet order
     * 
     * @param $order
     * @return bool|Planet_Pay_Model_Order
     */
    public function getPlanetOrder($order = null) {
        /** @var Planet_Pay_Model_Order $planetOrder */
        $planetOrder = Mage::getModel('planet_pay/order');
        $planetOrder->loadByMageOrder($order);
        if ($planetOrder->getId()) {
            Mage::register('planet_order', $planetOrder);
            return $planetOrder;
        } else {
            return false;
        }
    }

}
