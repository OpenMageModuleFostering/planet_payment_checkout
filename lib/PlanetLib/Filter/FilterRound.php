<?php
namespace PlanetLib\Filter;

/**
 * Class FilterRound
 * @package PlanetLib\Filter
 */
class FilterRound
{

    /**
     * @param float $value
     * @param int $precision
     * @param bool $convertNull
     * @return float
     */
    public static function round($value, $precision, $convertNull = false)
    {
        if (is_null($value)) {
            if ($convertNull) {
                $value = 0;
            }
            else {
                return null;
            }
        }
        //$value = round($value, $precision, PHP_ROUND_HALF_UP);
		$value = number_format($value, $precision, '.', '');
        return $value;
    }

} 