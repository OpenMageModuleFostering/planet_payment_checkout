<?php
namespace PlanetLib\Validator;

/**
 * Class ValidatorDate
 * @package PlanetLib\Validator
 */
class ValidatorDate implements ValidatorInterface
{
    protected $messages = array();

    protected $date;


    /**
     * @param $item
     * @return bool
     */
    public function isValid($item)
    {
        $this->messages = array();
        $valid = true;

        $this->date = strtotime($item);
        if ($this->date === false) {
            $valid = false;
            $this->messages[] = 'Incorrect date format';
        }

        return $valid;
    }

    /**
     * @param $format
     * @return string
     */
    public function getDate($format)
    {
        return date($format, $this->date);
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->messages;
    }
}