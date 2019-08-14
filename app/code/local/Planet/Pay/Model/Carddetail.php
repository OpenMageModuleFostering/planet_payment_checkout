<?php
/**
 * This is card detail model for various card data
 * 
 * Class Planet_Pay_Model_Order
 */
class Planet_Pay_Model_Carddetail extends Mage_Core_Model_Abstract
{

    protected function _construct()
    {
        $this->_init('planet_pay/carddetail');
    }

    /**
     * To saved the card data
     * 
     * @param Mage_Sales_Model_Order $order
     */
    public function saveCardDetail($result)
    {
        $getDetail = $result->toArray();
        $saveCardDetail = Mage::getModel('planet_pay/carddetail');
        $id = $getDetail['id'];
        $paymentType = $getDetail['paymentType'];
        $paymentBrand = $getDetail['paymentBrand'];
        $last4Digits = $getDetail['card']['last4Digits'];
        $holder = $getDetail['card']['holder'];
        $expiryMonth = $getDetail['card']['expiryMonth'];
        $expiryYear = $getDetail['card']['expiryYear'];
        try{
            $saveCardDetail->setResponseid($id);
            $saveCardDetail->setPaymenttype($paymentType);
            $saveCardDetail->setPaymentbrand($paymentBrand);
            $saveCardDetail->setLastdigits($last4Digits);
            $saveCardDetail->setExpirymonth($expiryMonth);
            $saveCardDetail->setExpiryyear($expiryYear);
            $saveCardDetail->setHolder($holder);
            $insertId = $saveCardDetail->save();
            
        } catch (Exception $ex) {
            
             Mage::log($ex->getMessage(), null, Planet_Pay_Model_Select::LOG_FILE);
        }
            
    }


}