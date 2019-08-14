<?php
namespace PlanetLib\Filter;

/**
 * Class FilterString
 * @package PlanetLib\Filter
 */
class FilterString
{

    /**
     * @param $data
     * @return mixed
     */
    public static function convertValuesToString($data)
    {
        foreach ($data as $name => $value) {
            if (is_array($value)) {
                $data[$name] = self::convertValuesToString($value);
            }
            $data[$name] = (string)$value;
        }
        return $data;
    }

} 