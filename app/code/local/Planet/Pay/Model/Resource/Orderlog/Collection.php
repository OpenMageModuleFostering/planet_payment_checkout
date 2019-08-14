<?php
/**
 * 
 * Class Planet_Pay_Model_Resource_Orderlog_Collection
 */
class Planet_Pay_Model_Resource_Orderlog_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('planet_pay/orderlog');
        $this->setOrder('date', 'desc');
    }

    /**
     * @param Planet_Pay_Model_Order $planetOrder
     * @return $this
     */
    public function setOrderFilter($planetOrder)
    {
        $this->addFilter('planet_order_id', $planetOrder->getId());
        return $this;
    }

    /**
     * @param $method
     * @return $this
     */
    public function setMethodFilter($method)
    {
        $methodMapper = $this->getMethodMapper();
        $this->addFilter('method_name', $methodMapper[$method]);
        return $this;
    }

    /**
     * @return $this
     */
    public function setSuccessFilter()
    {
        $this->addFilter('success', true);
        return $this;
    }

    /**
     * @return array
     */
    private function getMethodMapper()
    {
        return array(
            'order_request' => 'SubmitNewOrderResult',
            'delivery_note' => 'SubmitNewDeliveryResult',
            'credit_note'   => 'SubmitNewCreditResult'
        );
    }

}