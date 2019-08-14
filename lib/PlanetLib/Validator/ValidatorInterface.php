<?php
namespace PlanetLib\Validator;

/**
 * Interface ValidatorInterface
 * @package PlanetLib\Validator
 */
interface ValidatorInterface
{

    /**
     * @param $item
     * @return bool
     */
    public function isValid($item);

    /**
     * @return array
     */
    public function getErrorMessages();

} 