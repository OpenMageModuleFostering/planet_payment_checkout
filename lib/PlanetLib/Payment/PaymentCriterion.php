<?php

namespace PlanetLib\Payment;

use PlanetLib\Filter\FilterRound;
use PlanetLib\Filter\FilterString;
use PlanetLib\Validator\ValidatorFloat;
use PlanetLib\Validator\ValidatorInt;
use PlanetLib\Validator\ValidatorString;

/**
 * Class PaymentCriterion
 * @package PlanetLib\Payment
 */
class PaymentCriterion {

    protected $mapper;
    protected $merchantItemId;
    protected $discount;
    protected $quantity;
    protected $name;
    protected $price;
    protected $tax;
    protected $orderTransportationCosts;
    protected $orderAdministrationFee;
    protected $orderOrderPositionsCount;
    protected $orderTotalAmount;
    protected $orderId;
    protected $orderExternalInvoiceId;
    protected $orderExternalReferenceNumber;
    protected $orderInstallmentsIir;
    protected $orderInstallmentsZeroInterest;
    protected $orderInstallmentsCount;
    protected $orderInstallmentsAmount;
    protected $orderInstallmentsPromotionAccountId;
    protected $orderPaymentMethod;
    // criterions for shipping address (LOV-293)
    protected $customerDeliveryaddressStreet;
    protected $customerDeliveryaddressCity;
    protected $customerDeliveryaddressState;
    protected $customerDeliveryaddressState2;
    protected $customerDeliveryaddressZip;
    protected $customerDeliveryaddressCountry;
    protected $customerDeliveryaddressAdditionalData;
    protected $customerDeliveryAddressFirstName;
    protected $customerDeliveryAddressLastName;
    protected $orderItems;
    protected $payment;

    /**
     *
     */
    public function __construct(\PlanetLib\Payment $payment) {
        $this->payment = $payment;
        $this->mapper = $this->fillMapper();
    }

    /**
     * @param $data
     * @return array|bool
     */
    public function setData($data) {
        $errors = array();
        foreach ($this->mapper as $name) {
            $setterName = 'set' . ucfirst($name);
            if (method_exists($this, $setterName)) {
                $value = isset($data[$name]) ? $data[$name] : null;
                $result = call_user_func_array(array($this, $setterName), array($value));
                if (is_array($result)) {
                    $errors[$name] = $result;
                }
            }
        }

        if (count($errors) == 0) {
            return true;
        }
        return $errors;
    }

    /**
     * @param bool $internalNames
     * @param bool $converToString
     * @return array
     */
    public function toArray($internalNames = false, $converToString = false) {
        $data = array();
        if ($internalNames) {
            foreach ($this->mapper as $name) {
                $data[$name] = $this->$name;
            }
        } else {
            foreach ($this->mapper as $index => $name) {
                $data[$index] = $this->$name;
            }
        }
        if ($converToString) {
            $data = FilterString::convertValuesToString($data);
        }

        return $data;
    }

    /**
     * @param mixed $criterionCustomerAddressAdditionalData
     * @return array|bool
     */
    public function setCustomerAddressAdditionalData($criterionCustomerAddressAdditionalData) {
        $validator = new ValidatorString(array('empty' => true));
        if (!$validator->isValid($criterionCustomerAddressAdditionalData)) {
            return $validator->getErrorMessages();
        }
        $this->customerAddressAdditionalData = $criterionCustomerAddressAdditionalData;
        return true;
    } 

    /**
     * @param $criterionOrderPaymentMethod
     * @return array|bool
     */
    public function setOrderPaymentMethod($criterionOrderPaymentMethod) {
        $validator = new ValidatorString();
        if (!$validator->isValid($criterionOrderPaymentMethod)) {
            return $validator->getErrorMessages();
        }
        $this->orderPaymentMethod = $criterionOrderPaymentMethod;
        return true;
    }

    /**
     * @param field_type $customerDeliveryaddressStreet
     */
    public function setCustomerDeliveryaddressStreet($customerDeliveryaddressStreet) {
        $this->customerDeliveryaddressStreet = $customerDeliveryaddressStreet;
        return true;
    }

    /**
     * @param field_type $customerDeliveryaddressCity
     */
    public function setCustomerDeliveryaddressCity($customerDeliveryaddressCity) {
        $this->customerDeliveryaddressCity = $customerDeliveryaddressCity;
        return true;
    }

    /**
     * @param field_type $customerDeliveryaddressState
     */
    public function setCustomerDeliveryaddressState($customerDeliveryaddressState) {
        $this->customerDeliveryaddressState = $customerDeliveryaddressState;
        return true;
    }
    /**
     * @param field_type $customerDeliveryaddressState
     */
    public function setCustomerDeliveryaddressState2($customerDeliveryaddressState) {
        $this->customerDeliveryaddressState2 = $customerDeliveryaddressState;
        return true;
    }

    /**
     * @param field_type $customerDeliveryaddressZip
     */
    public function setCustomerDeliveryaddressZip($customerDeliveryaddressZip) {
        $this->customerDeliveryaddressZip = $customerDeliveryaddressZip;
        return true;
    }

    /**
     * @param field_type $customerDeliveryaddressCountry
     */
    public function setCustomerDeliveryaddressCountry($customerDeliveryaddressCountry) {
        $this->customerDeliveryaddressCountry = $customerDeliveryaddressCountry;
        return true;
    }

    /**
     * @param field_type $customerDeliveryaddressAdditionalData
     */
    public function setCustomerDeliveryaddressAdditionalData($customerDeliveryaddressAdditionalData) {
        $this->customerDeliveryaddressAdditionalData = $customerDeliveryaddressAdditionalData;
        return true;
    }

    /**
     * @param mixed $customerDeliveryAddressFirstName
     */
    public function setCustomerDeliveryAddressFirstName($customerDeliveryAddressFirstName) {
        $this->customerDeliveryAddressFirstName = $customerDeliveryAddressFirstName;
        return true;
    }

    /**
     * @param mixed $customerDeliveryAddressLastName
     */
    public function setCustomerDeliveryAddressLastName($customerDeliveryAddressLastName) {
        $this->customerDeliveryAddressLastName = $customerDeliveryAddressLastName;
        return true;
    }   

    /**
     * @return array
     */
    private function fillMapper() {
        return array('shipping.givenName' => 'customerDeliveryAddressFirstName',
            'shipping.surname' => 'customerDeliveryAddressLastName',
            //'shipping.street1' => 'customerDeliveryaddressStreet',
            //'shipping.street2' => 'customerDeliveryaddressState2',
            'shipping.city' => 'customerDeliveryaddressCity',
            'shipping.state' => 'customerDeliveryaddressState',
            'shipping.postcode' => 'customerDeliveryaddressZip',
            'shipping.country' => 'customerDeliveryaddressCountry');
    }

}
