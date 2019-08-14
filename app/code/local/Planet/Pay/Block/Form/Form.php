<?php
/**
 * Class Planet_Pay_Block_Form_Form
 */
class Planet_Pay_Block_Form_Form extends Mage_Payment_Block_Form
{

    /** @var  bool */
    protected $showIcons;

    protected function _construct()
    {
        $paymentCode = Mage::getSingleton('checkout/session')->getQuote()->getPayment()->getMethodInstance()->getCode();
        if ($paymentCode == 'planet_pay') {
            $this->setTemplate('planet_pay/form/form.phtml');
            $this->showIcons =  Mage::getStoreConfig('payment/planet_pay/show_icons_on_summary');
        }
        parent::_construct();
    }

    /**
     * @return string
     */
    public function getForm()
    {
        /** @var Planet_Pay_Model_Payment $planetPaymentModel */
        $planetPaymentModel = Mage::getModel('planet_pay/payment');
        return $planetPaymentModel->getForm();
    }
    
    /**
     * @return bool
     */
    public function isTokenization()
    {
        $customerData = Mage::getSingleton('customer/session')->getCustomer();                
        $custid =  $customerData->getId();
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $table = $resource->getTableName('planet_pay/carddetail');
        $query = 'SELECT registeration_id,brand_name, cc_last_four,cc_exp_month,cc_exp_year  FROM ' . $table . ' WHERE cust_id = '
                . (int) $custid;
        $resuid = $readConnection->fetchAll($query);
        return $resuid;
    }

    /**
     * @return bool
     */
    public function showIcons()
    {
        return $this->showIcons;
    }

    /**
     * @return null|string
     */
    public function getImagePath()
    {
        $onePage = Mage::getSingleton('checkout/type_onepage');
        $brandCode = $onePage->getCheckout()->getPlanetPaymentMethod();
        $path = Mage::getBaseDir('media').'/planet/img/'.$brandCode.'.png';
        if (file_exists($path)) {
            $url =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'/planet/img/'.$brandCode.'.png';
            return $url;
        }
        return null;
    }
}