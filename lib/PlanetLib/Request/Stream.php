<?php
namespace PlanetLib\Request;

class Stream
{

    /**
	 * To Get the response from server
	 *
     * @param string $url
     * @param null $options
     * @return mixed
     * @throws PlanetException
     */
    public function getResponse($url, $options = null)
    {
        $ctx = stream_context_create($options);
        $fp = @fopen($url, 'rb', false, $ctx);

        if (!$fp) {
            throw new PlanetException("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);

        if ($response === false) {
            throw new PlanetException("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }
}