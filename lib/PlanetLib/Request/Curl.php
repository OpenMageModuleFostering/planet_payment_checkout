<?php

namespace PlanetLib\Request;

class Curl {

    /**
	 * To get the response from server
	 *
     * @param string $url
     * @param null $options
     * @return mixed
     * @throws PlanetException
     */
    public function getResponse($url, $options = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($options) && is_array($options)) {
            foreach ($options as $option => $value) {
                curl_setopt($ch, $option, $value);
            }
        }
        $result = curl_exec($ch);
        curl_close($ch);
        if (is_null($result)) {
            throw new PlanetException("Problem with reading data from $url");
        }
        return $result;
    }

}
