<?php
namespace PlanetLib\Resources\Translation;

/**
 * Class BrandNames
 * @package PlanetLib\Resources\Translation
 */
class BrandNames
{
    protected $locales = array('de');

    /** @var  BrandNames */
    protected $localeClass;

    /**
     * @param $locale
     */
    public function __construct($locale)
    {
        if (in_array($locale, $this->locales)) {
            $className = '\PlanetLib\Resources\Translation\\'.$locale.'\BrandNames';
            $this->localeClass = new $className;
        }
    }

    /**
     * @return bool
     */
    public function localeExists()
    {
        return is_object($this->localeClass);
    }

    /**
     * @param $brandCode
     * @return null|string
     */
    public function getBrandName($brandCode)
    {
        if (!is_object($this->localeClass)) {
            return null;
        }
        return $this->localeClass->_getBrandName($brandCode);
    }

    /**
     * @return null|string
     */
    public function getBrandNames()
    {
        if (!is_object($this->localeClass)) {
            return null;
        }
        return $this->localeClass->_getBrandNames();
    }

    /**
     * @return mixed
     */
    protected function _getBrandNames()
    {
        return null;
    }

    /**
     * @param $brandCode
     * @return mixed
     */
    protected function _getBrandName($brandCode)
    {
        return null;
    }

} 