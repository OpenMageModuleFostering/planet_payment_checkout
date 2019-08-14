<?php
namespace PlanetLib\Writer;

use PlanetLib\WriterInterface;

/**
 * Class WriterSession
 * @package PlanetLib\Writer
 */
class WriterSession implements WriterInterface
{
    /**
     *
     */
    public function __construct()
    {
        if(session_id() == '') {
            session_start();
        }
    }

    /**
     * @param int $token
     * @return bool
     */
    public function writeToken($token)
    {
        $_SESSION['planet_token'] = $token;
        return true;
    }

    /**
     * @return string
     */
    public function readToken()
    {
        $token = $_SESSION['planet_token'];
        return $token;
    }
}