<?php
/**
 * Class Planet_Pay_Block_Checkout_Onepage
 */
class Planet_Pay_Block_Checkout_Onepage extends Mage_Checkout_Block_Onepage
{
    protected $activeStep;
    protected $checkoutFilled;

    /**
     *
     */
    public function __construct()
    {
        parent::_construct();
        if (is_null($this->activeStep)) {
            $sessionActiveStep = $this->getCheckout()->getActiveStep();
            if (is_string($sessionActiveStep)) {
                $this->getCheckout()->setActiveStep(null);
                $this->activeStep = $sessionActiveStep;
                if ($sessionActiveStep == 'review') {
                    $this->getCheckout()->setReviewStep(true);
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getSteps()
    {
        $steps = parent::getSteps();
        if (!is_null($this->activeStep) && isset($steps[$this->activeStep])) {
            $steps[$this->activeStep]['allow'] = true;
            $steps[$this->activeStep]['complete'] = true;
        }
        return $steps;
    }



    /**
     * @return string
     */
    public function getActiveStep()
    {
        if (!is_null($this->activeStep)) {
            $this->fillCheckout();
            return $this->activeStep;
        }
        else {
            return parent::getActiveStep();
        }
    }

    private function fillCheckout()
    {
        if (is_null($this->checkoutFilled)) {
            $checkout = Mage::getSingleton('checkout/type_onepage');
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $checkout->saveBilling($quote->getBillingAddress()->toArray(),false);
            $checkout->saveShipping($quote->getShippingAddress()->toArray(),false);
            $checkout->saveShippingMethod($quote->getShippingAddress()->getShippingMethod());
            $brandCode = $quote->getPayment()->getAdditionalData();
            $checkout->getCheckout()->setPlanetPaymentMethod($brandCode);
            $this->checkoutFilled = true;
        }
    }
}
