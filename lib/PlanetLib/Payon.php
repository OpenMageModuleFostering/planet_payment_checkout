<?php
namespace PlanetLib;
use PlanetLib\Request\Curl;
use PlanetLib\Request\Stream;

/**
 * The Pay.On system is working different for certain payment methods. E.g. for SOFORT.
 *
 * Class Payon
 * @package PlanetLib
 */
class Payon
{
    protected $url;

    protected $sequritySender;
    protected $queryEntity;
    protected $userLogin;
    protected $userPwd;
    protected $transactionMode;

    /**
     * constructor for class to read from configuration file
	 *
     */
    public function __construct()
    {
        if (file_exists("config.ini")) {
            $config = parse_ini_file("config.ini", true);
            $this->setUrl($config['general']['payonUrl']);
            $this->setCredentials($config['payment']);
        }
    }

    /**
	 * This set the credntilas needed for api
	 * 
     * @param $credentials
     * @return $this
     * @throws PlanetException
     */
    public function setCredentials($credentials)
    {
        if(!is_array($credentials))
        {
            // if no config and no params available, throw exception
            throw new PlanetException("No valid credentials set.");
        }

        $this->_setSequritySender($credentials['sequritySender']);
        $this->_setQueryEntity($credentials['queryEntity']);
        $this->_setUserLogin($credentials['userLogin']);
        $this->_setUserPassword($credentials['userPwd']);
        $this->_setTransactionMode($credentials['transactionMode']);

        if (isset($credentials['url'])) {
            $this->setUrl($credentials['url']);
        }

        return $this;
    }

    /**
	 * To set the URL
	 *
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
	 * To check for Curl version
	 *
     * @return bool
     */
    protected function isCurl()
    {
        return function_exists('curl_version');
    }

    /**
	 * To set the sequrity sender
	 *
     * @param string $sender
     * @return bool|array
     */
    protected function _setSequritySender($sender)
    {
        $this->sequritySender = $sender;
        return true;
    }


    /**
	 * To Set the User login
	 *
     * @param string $login
     * @return bool|array
     */
    protected function _setUserLogin($login)
    {
        $this->userLogin = $login;
        return true;
    }

    /**
	 * To Set The User Password
	 *
     * @param string $pwd
     * @return bool|array
     */
    protected function _setUserPassword($pwd)
    {
        $this->userPwd = $pwd;
        return true;
    }

    /**
	 * To Set The Transaction mode
	 *
     * @param string $mode
     * @return bool|array
     */
    protected function _setTransactionMode($mode)
    {
        $this->transactionMode = $mode;
        return true;
    }

}