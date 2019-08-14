<?php
/**
 * Planet method model for different brand
 * 
 * Class Planet_Pay_Model_Method
 */
class Planet_Pay_Model_Method extends Mage_Core_Model_Abstract
{
    /**
     * To hold various option
     * 
     * @var string
     */
    protected $_options;

    protected function _construct()
    {
        $this->_init('planet_pay/method');
    }

    /**
     * This is brandcode
     * 
     * @param $brandcode
     */
    public function loadByBrandcode($brandcode)
    {
        $this->load($brandcode, 'brandcode');
    }

    /**
     * This return multi option as selected
     * 
     * @param bool $isMultiselect
     * @return mixed
     */
    public function toOptionArray($isMultiselect=true)
    {
        if (!$this->_options) {
            $this->_options = Mage::getResourceModel('planet_pay/method_collection')->loadData()->toOptionArray(false);
        }

        $options = $this->_options;
        return $options;
    }

}