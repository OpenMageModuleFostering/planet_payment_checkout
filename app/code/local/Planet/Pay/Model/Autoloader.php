<?php

/**
 * This is autoloader script
 * 
 * Class Planet_Pay_Model_Autoloader
 */
class Planet_Pay_Model_Autoloader
{
    public function init()
    {
        spl_autoload_register(function ($class) {
            if (strpos($class, 'PlanetLib\\') === 0) {
                $libPath = Mage::getBaseDir('lib');
                require_once $libPath.'/'.str_replace('\\', '/', $class). '.php';
            }
        }, false, true);
    }
}