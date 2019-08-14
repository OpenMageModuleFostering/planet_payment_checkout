<?php
namespace PlanetLib\Validator;

/**
 * Class ValidatorPhone
 * @package PlanetLib\Validator
 */
class ValidatorPhone implements ValidatorInterface
{
    protected $messages = array();

    protected $min = 6;
    protected $empty = false;

    public function __construct($parameters = null)
    {
        if (is_array($parameters)) {
            if (isset($parameters['min'])) {
                $this->min = $parameters['min'];
            }
            if (isset($parameters['empty'])) {
                $this->empty = $parameters['empty'];
            }
        }
    }

    /**
     * @param $item
     * @return bool
     */
    public function isValid($item)
    {
        $this->messages = array();
        $valid = true;
        if (preg_match('/^([0-9\(\)\/\+ \-]*)$/', $item) !== 1) {
            $valid = false;
            $this->messages[] = 'Incorrect phone number';
        }

        if (strlen($item) < $this->min && !($this->empty && is_null($item))) {
            $valid = false;
            $this->messages[] = 'Phone number is too short';
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