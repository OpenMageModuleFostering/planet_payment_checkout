<?php
namespace PlanetLib\Validator;

/**
 * Class ValidatorFloat
 * @package PlanetLib\Validator
 */
class ValidatorFloat implements ValidatorInterface
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
            $this->messages[] = 'Value can not be empty';
        }
        else {
            if (!filter_var($item, FILTER_VALIDATE_FLOAT)) {
                $valid = false;
                if (empty($item)) {
                    $this->messages[] = 'Value can not be empty';
                }
                else {
                    $this->messages[] = 'Value '.$item.' is not a float';
                }
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