<?php
/**
 * Class Planet_Pay_Block_Checkout_Onepage_Review
 */
class Planet_Pay_Block_Checkout_Onepage_Review extends Mage_Checkout_Block_Onepage_Review
{

    private $errorMessage;

    /**
     * @return bool
     */
    public function loadReview()
    {
        if (Mage::getSingleton('checkout/session')->getReviewStep() === true) {
            Mage::getSingleton('checkout/session')->setReviewStep(false);
            return true;
        }
        return false;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        if (is_null($this->errorMessage)) {
            $this->errorMessage = Mage::getSingleton('checkout/session')->getErrorMessage();
            Mage::getSingleton('checkout/session')->setErrorMessage(null);
        }
        return $this->errorMessage;
    }
}
