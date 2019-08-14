<?php
/**
 * Class Planet_Pay_Model_Resource_Method_Collection
 */
class Planet_Pay_Model_Resource_Method_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('planet_pay/method');
    }

    /**
     * @return $this
     */
    public function onlyActive()
    {
        $this->addFilter('active', 1);
        return $this;
    }

    /**
     * @param $brands
     * @return $this
     */
    public function onlySpecifiedBrands($brands)
    {
        $brands = explode(',', $brands);
        $this->addFieldToFilter('brandcode', array("in"=>$brands));
        return $this;
    }

    /**
     * Convert collection items to select options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = $this->_toOptionArray('brandcode', 'brandname');
        usort($options, function($a, $b) {
            return strcasecmp($a['label'],$b['label']);
        });
        return $options;
    }

}