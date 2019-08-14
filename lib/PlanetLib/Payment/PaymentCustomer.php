<?php

namespace PlanetLib\Payment;

use PlanetLib\Filter\FilterString;
use PlanetLib\Validator\ValidatorDate;
use PlanetLib\Validator\ValidatorEmail;
use PlanetLib\Validator\ValidatorIp;
use PlanetLib\Validator\ValidatorPhone;
use PlanetLib\Validator\ValidatorString;

/**
 * Class PaymentCustomer
 * @package PlanetLib\Payment
 */
class PaymentCustomer {

    protected $mapper;
    protected $nameSalutation;
    protected $nameGiven;
    protected $nameSurname;
    protected $nameBirthdate;
    protected $addressStreet;
    protected $addressZip;
    protected $addressState;
    protected $addressCity;
    protected $nameBillingCountry;
    protected $contactPhone;
    protected $contactMobile;
    protected $contactIp;
    protected $contactEmail;
    protected $defaultStringValidator;

    /**
     *
     */
    public function __construct() {
        $this->defaultStringValidator = new ValidatorString(array('min' => 1));
        $this->mapper = $this->fillMapper();
    }

    /**
     * @param $data
     * @return array|bool
     */
    public function setData($data) {
        $errors = array();
        foreach ($this->mapper as $name) {
            $value = isset($data[$name]) ? $data[$name] : null;
            $result = call_user_func_array(array($this, 'set' . ucfirst($name)), array($value));
            if (is_array($result)) {
                $errors[$name] = $result;
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
     * @param mixed $customerAddressCity
     * @return $this|array
     */
    public function setAddressCity($customerAddressCity) {
        if (!$this->defaultStringValidator->isValid($customerAddressCity)) {
            return $this->defaultStringValidator->getErrorMessages();
        }
        $this->addressCity = $customerAddressCity;
        return true;
    }

    /**
     * @param mixed $customerAddressCountry
     * @return array|bool
     */
    public function setNameBillingCountry($customerAddressCountry) {
        if (!$this->defaultStringValidator->isValid($customerAddressCountry)) {
            return $this->defaultStringValidator->getErrorMessages();
        }
        $this->nameBillingCountry = $customerAddressCountry;
        return true;
    }

    /**
     * @param mixed $customerAddressState
     * @return array|bool
     */
    public function setAddressState($customerAddressState) {
        if (!$this->defaultStringValidator->isValid($customerAddressState)) {
            return $this->defaultStringValidator->getErrorMessages();
        }
        $this->addressState = $customerAddressState;
        return true;
    }

    /**
     * @param mixed $customerAddressStreet
     * @return array|bool
     */
    public function setAddressStreet($customerAddressStreet) {
        if (!$this->defaultStringValidator->isValid($customerAddressStreet)) {
            return $this->defaultStringValidator->getErrorMessages();
        }
        $this->addressStreet = $customerAddressStreet;
        return true;
    }

    /**
     * @param mixed $customerAddressZip
     * @return array|bool
     */
    public function setAddressZip($customerAddressZip) {
        if (!$this->defaultStringValidator->isValid($customerAddressZip)) {
            return $this->defaultStringValidator->getErrorMessages();
        }
        $this->addressZip = $customerAddressZip;
        return true;
    }

    /**
     * @param mixed $customerContactEmail
     * @return array|bool
     */
    public function setContactEmail($customerContactEmail) {
        $validator = new ValidatorEmail();
        if (!$validator->isValid($customerContactEmail)) {
            return $validator->getErrorMessages();
        }
        $this->contactEmail = $customerContactEmail;
        return true;
    }

    /**
     * @param mixed $customerContactIp
     * @return array|bool
     */
    public function setContactIp($customerContactIp) {
        $validator = new ValidatorIp();
        if (!$validator->isValid($customerContactIp)) {
            return $validator->getErrorMessages();
        }
        $this->contactIp = $customerContactIp;
        return true;
    }

    /**
     * @param mixed $customerContactMobile
     * @return array|bool
     */
    public function setContactMobile($customerContactMobile) {
        $validator = new ValidatorPhone(array('empty' => true));
        if (!$validator->isValid($customerContactMobile)) {
            return $validator->getErrorMessages();
        }
        $this->contactMobile = $customerContactMobile;
        return true;
    }

    /**
     * @param mixed $customerContactPhone
     * @return array|bool
     */
    public function setContactPhone($customerContactPhone) {
        $validator = new ValidatorPhone();
        if (!$validator->isValid($customerContactPhone)) {
            return $validator->getErrorMessages();
        }
        $this->contactPhone = $customerContactPhone;
        return true;
    }

    /**
     * @param mixed $customerNameBirthdate
     * @return array|bool
     */
    public function setNameBirthdate($customerNameBirthdate) {
        $validator = new ValidatorDate();
        if (!$validator->isValid($customerNameBirthdate)) {
            return $validator->getErrorMessages();
        } else {
            $this->nameBirthdate = $validator->getDate('Y-m-d');
            return true;
        }
    }

    /**
     * @param mixed $customerNameFamily
     * @return array|bool
     */
    public function setNameSurname($customerNameFamily) {
        if (!$this->defaultStringValidator->isValid($customerNameFamily)) {
            return $this->defaultStringValidator->getErrorMessages();
        }
        $this->nameSurname = $customerNameFamily;
        return true;
    }

    /**
     * @param mixed $customerNameGiven
     * @return array|bool
     */
    public function setNameGiven($customerNameGiven) {
        if (!$this->defaultStringValidator->isValid($customerNameGiven)) {
            return $this->defaultStringValidator->getErrorMessages();
        }
        $this->nameGiven = $customerNameGiven;
        return true;
    }

    /**
     * @param mixed $customerNameSalutation
     * @return array|bool
     */
    public function setNameSalutation($customerNameSalutation) {
        if (!$this->defaultStringValidator->isValid($customerNameSalutation)) {
            return $this->defaultStringValidator->getErrorMessages();
        }
        $this->nameSalutation = $customerNameSalutation;
        return true;
    }

   /**
     * @return array
     */
    private function fillMapper()
    {
        return array('customer.givenName'      => 'nameGiven',
                     'customer.surname'     => 'nameSurname',
                     //'billing.street1'  => 'addressStreet',
                     'billing.postcode'     => 'addressZip',
                     'billing.state'   => 'addressState',
                     'billing.city'    => 'addressCity',
                     'customer.ip'      => 'contactIp',
                     'customer.email'   => 'contactEmail');
    }

}
