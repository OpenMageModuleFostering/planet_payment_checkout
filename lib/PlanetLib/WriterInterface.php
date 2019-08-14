<?php
namespace PlanetLib;

/**
 * Interface WriterInterface
 * @package PlanetLib
 */
interface WriterInterface
{
    /**
     * Save token to storage
     * @param $token
     * @return bool
     */
    public function writeToken($token);

    /**
     * Get token from storage
     * @return string
     */
    public function readToken();
}