<?php

/**
 * Class Planet_Pay_PaymentController
 */
class Planet_Pay_PaymentController extends Mage_Core_Controller_Front_Action {

    /**
     * @return $this
     * @throws Mage_Core_Exception
     */
    public function checkAction() {
        $registerationid = $_REQUEST['regid'];
        if ($registerationid) {
            try {
                $this->processPaymentTokenization($registerationid);
                Mage::getSingleton('checkout/cart')->truncate()->save();
                Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/onepage/success'));
            } catch (Mage_Core_Exception $e) {
                $message = $e->getMessage();
                Mage::getSingleton('checkout/session')->setErrorMessage('Planet checkout error: ' . $message);
                Mage::getSingleton('checkout/session')->setActiveStep('review');
                Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/onepage'));
            }
        } else {
            $payment = Mage::app()->getRequest()->getParam('payment');
            if ($payment == 'planet_pay') {
				$this->loadLayout(false);
                $token = Mage::app()->getRequest()->getParam('id');
                try {
                    $this->processPaymentResponse($token);
                    Mage::getSingleton('checkout/cart')->truncate()->save();
                    echo '<script>window.top.location = "'.Mage::getUrl('checkout/onepage/success').'";</script>';
                } catch (Mage_Core_Exception $e) {
                    $message = $e->getMessage();
                    echo '<script>alert("Planet checkout error: '.$message.'");
					 window.top.document.querySelector(".new_payon_form").remove();
					 window.top.shippingMethod.save();
					 //window.top.checkout.changeSection("opc-payment");
					 </script>';
                }
            } else {
                throw new Mage_Core_Exception('Error while order processing. Please, contact us.');
            }
        }
    }
	
	/**
	*
	* checkAction backup
	*
	*/
	/*public function checkAction() {
        $registerationid = $_REQUEST['regid'];
        if ($registerationid) {
            try {
                $this->processPaymentTokenization($registerationid);
                Mage::getSingleton('checkout/cart')->truncate()->save();
                Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/onepage/success'));
            } catch (Mage_Core_Exception $e) {
                $message = $e->getMessage();
                Mage::getSingleton('checkout/session')->setErrorMessage('Planet checkout error: ' . $message);
                Mage::getSingleton('checkout/session')->setActiveStep('review');
                Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/onepage'));
            }
        } else {
            $payment = Mage::app()->getRequest()->getParam('payment');
            if ($payment == 'planet_pay') {
                $token = Mage::app()->getRequest()->getParam('id');
                try {
                    $this->processPaymentResponse($token);
                    Mage::getSingleton('checkout/cart')->truncate()->save();
                    Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/onepage/success'));
                } catch (Mage_Core_Exception $e) {
                    $message = $e->getMessage();
                    Mage::getSingleton('checkout/session')->addError('Planet checkout error: ' . $message);
                    Mage::getSingleton('checkout/session')->setActiveStep('billing');
                    Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
                }
            } else {
                throw new Mage_Core_Exception('Error while order processing. Please, contact us.');
            }
        }
    }*/

    /**
     *To handle tokenize request
     *  
     * @param type $tokenize
     * @throws Mage_Core_Exception
     */
    private function processPaymentTokenization($tokenize) {
        if (is_null($tokenize)) {
            throw new Mage_Core_Exception('Registration Id can not be null. Please, contact us.');
        }
        /** @var Planet_Pay_Model_Payment $planetPaymentModel */
        $planetPaymentModel = Mage::getModel('planet_pay/payment');
        $planetPaymentModel->processPaymentTokenization($tokenize);
    }

    /**
     * @param $token
     * @throws Mage_Core_Exception
     */
    private function processPaymentResponse($token) {
        if (is_null($token)) {
            throw new Mage_Core_Exception('Token can not be null. Please, contact us.');
        }
        /** @var Planet_Pay_Model_Payment $planetPaymentModel */
        $planetPaymentModel = Mage::getModel('planet_pay/payment');
        $planetPaymentModel->processPaymentResponse($token);

        }
        
        

}
