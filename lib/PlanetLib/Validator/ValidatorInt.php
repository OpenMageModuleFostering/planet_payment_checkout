<?php
namespace PlanetLib\Validator;

/**
 * Class ValidatorInt
 * @package PlanetLib\Validator
 */
class ValidatorInt implements ValidatorInterface
{
    protected $messages = array();

    protected $empty = false;
    protected $min;

    public function __construct($parameters = null)
    {
        if (is_array($parameters)) {
            if (isset($parameters['empty'])) {
                $this->empty = $parameters['empty'];
            }
            if (isset($parameters['min'])) {
                $this->min = $parameters['min'];
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

        if (is_null($item) && !$this->empty) {
            $valid = false;
            $this->messages[] = 'Value is null';
        }
        else {
            if (!filter_var($item, FILTER_VALIDATE_INT)) {
                $valid = false;
                $this->messages[] = 'Value is not an integer';
            }
            if (isset($this->min) && $this->min > $item) {
                $valid = false;
                $this->messages[] = 'Value must be bigger than '.$this->min;
            }
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