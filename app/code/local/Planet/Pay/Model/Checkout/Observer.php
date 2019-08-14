<?php
/**
 * Class Planet_Pay_Model_Checkout_Observer
 */
class Planet_Pay_Model_Checkout_Observer
{
   /**
     * @param $orderId
     */
    public function checkoutSuccessAction($orderId)
    {
        $onePage = Mage::getSingleton('checkout/type_onepage');
        /*$orderid = $orderId->order_ids[0];
        $order = Mage::getModel('sales/order')->load($orderid);
        $Incrementid = $order->getIncrementId();*/
        $onePage->getCheckout()->setPlanetLastOrderId(null);
        Mage::getSingleton('checkout/cart')->truncate()->save();
    }
} 