<?php
namespace PlanetLib\Validator;

/**
 * Class ValidatorEmail
 * @package PlanetLib\Validator
 */
class ValidatorEmail implements ValidatorInterface
{
    protected $messages = array();


    /**
     * @param $item
     * @return bool
     */
    public function isValid($item)
    {
        $this->messages = array();
        if (filter_var($item, FILTER_VALIDATE_EMAIL)) {
            $valid = true;
        }
        else {
            $valid = false;
            $this->messages[] = 'Incorrect e-mail address';
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