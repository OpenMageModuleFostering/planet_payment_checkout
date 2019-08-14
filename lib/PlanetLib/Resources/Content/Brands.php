<?php
namespace PlanetLib\Resources\Content;

/**
 * Class Brands
 * @package LoviitLib\Resources\Content
 */
class Brands
{
    private static $brands = array(
            'AMEX'                    => array(
                'name' => 'American Express',
                'code' => 6,
                'flags' => ''
            ),
            'MASTER'                  => array(
                'name' => 'MasterCard',
                'code' => 6,
                'flags' => ''
            ),
            'VISA'                    => array(
                'name' => 'Visa',
                'code' => 6,
                'flags' => ''
            ),
            'DISCOVER'                    => array(
                'name' => 'discover',
                'code' => 6,
                'flags' => ''
            ),
            'DINERS'                    => array(
                'name' => 'diners',
                'code' => 6,
                'flags' => ''
            )
        );

    /**
     * @return array
     */
    public static function getBrands()
    {
        $brands = array();
        foreach(self::$brands as $brand=>$values){
            $brands[$brand] = $values['name'];
        }
        return $brands;
    }

    /**
     * @param $brand
     * @return array|null
     */
    public static function getFlags($brand)
    {
        if (!empty(self::$brands[$brand]['flags'])){
            return self::$brands[$brand]['flags'];
        }
        return null;
    }
}