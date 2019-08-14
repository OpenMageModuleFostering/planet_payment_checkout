<?php
/**
 * Class Planet_Pay_Block_Method_List
 */
class Planet_Pay_Block_Method_List extends Mage_Core_Block_Template
{
    /**
     * @var Planet_Pay_Model_Resource_Method_Collection
     */
    protected $_methodsCollection = null;

    /**
     * @return Planet_Pay_Model_Resource_Method_Collection
     */
    protected function _getCollection()
    {
        return  Mage::getResourceModel('planet_pay/method_collection');
    }

    /**
     *
     * @return Planet_Pay_Model_Resource_Method_Collection
     */
    public function getCollection()
    {
        if (is_null($this->_methodsCollection)) {
            $this->_methodsCollection = $this->_getCollection();
            $this->_methodsCollection->onlyActive();
        }

        return $this->_methodsCollection;
    }

}
