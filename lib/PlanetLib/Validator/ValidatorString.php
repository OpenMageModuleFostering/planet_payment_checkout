<?php
namespace PlanetLib\Validator;

/**
 * Class ValidatorString
 * @package PlanetLib\Validator
 */
class ValidatorString implements ValidatorInterface
{
    protected $min = 0;
    protected $max;
    protected $empty = false;

    protected $messages = array();

    /**
     * @param array $params
     */
    public function __construct($params = null)
    {
        if (is_array($params)) {
            if (isset($params['min'])) {
                $this->min = $params['min'];
            }
            if (isset($params['max'])) {
                $this->max = $params['max'];
            }
            if (isset($params['empty'])) {
                $this->empty = $params['empty'];
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
        if (!is_string($item)) {
            $valid = false;
            $this->messages[] = 'Value is not a string';
        }
        elseif (!$this->empty && empty($item)) {
            $valid = false;
            $this->messages[] = 'Value can not be empty';
        }
        else {
            if (strlen($item) < $this->min ) {
                $valid = false;
                $this->messages[] = 'Value is too short';
            }
            if (isset($this->max) && strlen($item) > $this->max) {
                $valid = false;
                $this->messages[] = 'Value is too long';
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