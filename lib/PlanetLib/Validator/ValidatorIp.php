<?php
namespace PlanetLib\Validator;

/**
 * Class ValidatorIp
 * @package PlanetLib\Validator
 */
class ValidatorIp implements ValidatorInterface
{
    protected $messages = array();


    /**
     * @param $item
     * @return bool
     */
    public function isValid($item)
    {
        $this->messages = array();
        if (filter_var($item, FILTER_VALIDATE_IP)) {
            $valid = true;
        }
        else {
            $valid = false;
            $this->messages[] = 'Incorrect ip address';
        }
        return $valid;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->messages;
    }
}