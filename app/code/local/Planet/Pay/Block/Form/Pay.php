<?php
use PlanetLib\Resources\Translation\BrandNames;

/**
 * Class Planet_Pay_Block_Form_Pay
 */
class Planet_Pay_Block_Form_Pay extends Mage_Payment_Block_Form
{
    /**
     * @var Planet_Pay_Model_Resource_Method_Collection
     */
    protected $_methodsCollection = null;

    /** @var  BrandNames */
    protected $brandNames;

    /** @var  bool */
    protected $showIcons;

	protected function _construct()
    {
       $this->setTemplate('planet_pay/form/pay.phtml');

        $autoloader = new Planet_Pay_Model_Autoloader();
        $autoloader->init();

        $language = Mage::app()->getLocale()->getLocale()->getLanguage();
        $this->brandNames = new BrandNames($language);
        $this->showbrandNamesIcons =  Mage::getStoreConfig('payment/planet_pay/show_icons_in_selection');
        $this->showIcons =  Mage::getStoreConfig('payment/planet_pay/show_icons_in_selection');
        parent::_construct();
    }
    
    /**
     * adding function to show form on payment step
     */
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
   /* finishing adding function in block */

    /**
     * @return Planet_Pay_Model_Resource_Method_Collection
     */
    protected function _getCollection()
    {
        return  Mage::getResourceModel('planet_pay/method_collection');
    }

    /**
     * @return Planet_Pay_Model_Resource_Method_Collection
     */
    public function getCollection()
    {
        if (is_null($this->_methodsCollection)) {
            $config = Mage::getStoreConfig('payment/planet_pay/payment_methods');
            $this->_methodsCollection = $this->_getCollection();
            $this->_methodsCollection->onlySpecifiedBrands($config);
            $this->_methodsCollection->setOrder('brandid', 'DESC');
            $this->_methodsCollection->onlyActive();
        }
        return $this->_methodsCollection;
    }

    /**
     * @param Planet_Pay_Model_Resource_Method $methodItem
     * @return string
     */
    public function getName($methodItem)
    {
        if ($this->brandNames->localeExists()) {
            $brandName = $this->brandNames->getBrandName($methodItem->getBrandcode());
            if ($brandName) {
                return $brandName;
            }
        }
        return $methodItem->getBrandname();
    }

    /**
     * @return bool
     */
    public function showIcons()
    {
        return $this->showIcons;
    }

    /**
     * @param $methodItem
     * @return null|string
     */
    public function getImagePath($methodItem)
    {
        $path = Mage::getBaseDir('media').'/planet/img/'.$methodItem->getBrandcode().'.jpg';
        if (file_exists($path)) {
            $url =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'/planet/img/'.$methodItem->getBrandcode().'.jpg';
            return $url;
        }
        return null;
    }

    /**
     * @return mixed
     */
    public function getPaymentSelectionMessage()
    {
        $paymentSelectionMessage = Mage::getStoreConfig('payment/planet_pay/payment_selection_message');
        return $paymentSelectionMessage;
    }

}