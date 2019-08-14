<?php 
class planet_Pay_Block_Info_Pay extends Mage_Payment_Block_Info
{
	/**
     * Init ops payment information block
     *
     */
	/*protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('pay/info/pay.phtml');
    }*/
	protected function _prepareSpecificInformation($transport = null)
	{
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}
		$info = $this->getInfo();
                $profilearr = $info->getAdditionalInformation();
				if(is_array($profilearr) && !empty($profilearr)){				
					
					
				}
                $profileid = key($profilearr);
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$transport->addData(array(
			Mage::helper('payment')->__('Credit Card No Last 4') => $info->getCcLast4(),
			//Mage::helper('payment')->__('Payon Profile Id') => $profileid,
			Mage::helper('payment')->__('Card Type') => $info->getCcType(),
			Mage::helper('payment')->__('Exp Date') => $info->getCcExpMonth() . ' / '.$info->getCcExpYear(),
			Mage::helper('payment')->__('Card Owner') => $info->getCcOwner(),
		));
		return $transport;
	}
}