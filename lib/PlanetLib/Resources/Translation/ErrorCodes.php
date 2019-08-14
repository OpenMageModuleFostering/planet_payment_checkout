<?php
namespace PlanetLib\Resources\Translation;

/**
 * Class ErrorCodes
 * @package PlanetLib\Resources\Translation
 */
class ErrorCodes
{
    protected $locales = array('de');

    /** @var  ErrorCodes */
    protected $localeClass;

    /**
     * @param $locale
     */
    public function __construct($locale)
    {
        if (in_array($locale, $this->locales)) {
            $className = '\PlanetLib\Resources\Translation\\'.$locale.'\ErrorCodes';
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
     * @param $code
     * @return null|string
     */
    public function getErrorMessage($code)
    {
        if (!is_object($this->localeClass)) {
            return null;
        }
        return $this->localeClass->_getErrorMessage($code);
    }

    /**
     * @return null|string
     */
    public function getErrorCodes()
    {
        if (!is_object($this->localeClass)) {
            return null;
        }
        return $this->localeClass->_getErrorCodes();
    }

    /**
     * @return mixed
     */
    protected function _getErrorCodes()
    {
        return null;
    }

    /**
     * @param $code
     * @return mixed
     */
    protected function _getErrorMessage($code)
    {
        return null;
    }

} 