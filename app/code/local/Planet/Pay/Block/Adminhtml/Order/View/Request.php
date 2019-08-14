<?php
/**
 * Class Planet_Pay_Block_Adminhtml_Order_View_Request
 */
class Planet_Pay_Block_Adminhtml_Order_View_Request extends Mage_Adminhtml_Block_Template
{
    protected $planetRequest;

    /**
     * @return array
     */
    public function getOrderRequestOptions()
    {
        $requestOptions = array();
        $requestData = $this->getRequestData();
        foreach ($requestData as $name => $value) {
            if (!is_array($value)) {
                $requestOptions[] = array(
                    'name' => $name,
                    'value'=> $value
                );
            }
        }
        return $requestOptions;
    }

    /**
     * @return array
     */
    public function getOrderRequestCustomer()
    {
        $requestData = $this->getRequestData();
        if (isset($requestData['customerData'])) {
            return $requestData['customerData'];
        }
        else {
            return array();
        }
    }

    /**
     * @return array
     */
    public function getOrderRequestCriterionData()
    {
        $criterionData = array();
        $requestData = $this->getRequestData();
        if (isset($requestData['criterionData'])) {
            foreach ($requestData['criterionData'] as $name => $value) {
                if (!is_array($value)) {
                    $criterionData[] = array(
                        'name' => $name,
                        'value'=> $value
                    );
                }
            }
        }
        return $criterionData;
    }

    /**
     * @return array
     */
    public function getOrderRequestOrderItems()
    {
        $orderItems = array();
        $requestData = $this->getRequestData();
        if (isset($requestData['criterionData']['orderItems'])) {
            foreach ($requestData['criterionData']['orderItems'] as $id => $orderItem) {
                foreach ($orderItem as $name => $value) {
                    $orderItems[$id][] = array(
                        'name' => $name,
                        'value'=> $value
                    );
                }
            }
        }
        return $orderItems;
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return Mage::registry('sales_order');
    }

    /**
     * @return array|mixed
     */
    protected function getRequestData()
    {
        if (!is_null($this->planetRequest)){
            return $this->planetRequest;
        }
        else {
            /** @var Planet_Pay_Model_Order $planetOrder */
            $planetOrder =  Mage::getModel('planet_pay/order');
            $planetOrder->loadByMageOrder($this->getOrder());
            $request = $planetOrder->getPaymentRequest();
            if (!is_null($request)) {
                return json_decode($request, true);
            }
            else {
                return array();
            }
        }
    }

}
