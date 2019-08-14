<?php

namespace PlanetLib;

use PlanetLib\Writer\WriterSession;

/**
 * Class used for token generation and obtaining payment status.
 * For token generation generateToken method is used. It accepts object of \PlanetLib\Payment
 * and returns token. Token also can be saved in store (by default session is used)
 * for automatic obtaining it from \PlanetLib\View for form generation.
 * For obtaining payment status getResult is used. It accepts token from planet callback
 * and returns object of \PlanetLib\Response, which can be used for checking status.
 *
 * Class Planet
 * @package PlanetLib
 *
 */
class Planet {

    protected $url;
    protected $writer;

    /**
     * Writer can be transmited on creation, or later using setter
     *
     * @param WriterInterface $writer
     */
    public function __construct(WriterInterface $writer = null) {
        if (file_exists("config.ini")) {
            $config = parse_ini_file("config.ini", true);
            $this->setUrl($config['general']['url']);
        }

        if (is_null($writer)) {
            $writer = new WriterSession();
        }
        $this->writer = $writer;
    }

    /**
     * Method used for token generation.
     * Sends request to planet service and saves obtained token to storage using writer.
     * For request will be used Curl if it's installed or stream if not
     *
     * @param Payment $payment
     * @return string - token, obtained from service
     * @throws PlanetException
     */
    public function generateToken(Payment $payment) {
        if (is_null($this->url)) {
            throw new PlanetException('The url is not set');
        }
        $url = $this->url . '/v1/checkouts';
        if ($this->isCurl()) {
            $responseObj = new \PlanetLib\Request\Curl();
            $options = array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payment->getRequestData()
            );
        } else {
            $responseObj = new \PlanetLib\Request\Stream();
            $options = array('http' => array(
                    'method' => 'POST',
                    'content' => $payment->getRequestData()
            ));
        }
        $response = $responseObj->getResponse($url, $options);
        $response = json_decode($response, true);
        $token = $response['id'];
		$optionexp = explode('&',$options['10015']);
        //$file1 = '../var/log/requestparamters.txt';
        //$file2 = '../var/log/responseparamters.txt';
        file_put_contents("var/log/requestparamters.txt", print_r($optionexp,true),FILE_APPEND);
        file_put_contents("var/log/responseparamters.txt", print_r($response,true),FILE_APPEND);
		if(!empty($response['result']['parameterErrors'])){
            $description = $response['result']['parameterErrors'];
            throw new PlanetException('Response error:-'.$description);
        }
        $this->writer->writeToken($token);
        return $token;
    }

    /**
     * Method for checking payment status.
     * Token, obtained from planet callback is accepted as param
     * Returns object of \PlanetLib\Response
     *
     * @param string $token
     * @return Response
     */
    public function getResult($token) {
        $url = $this->url . "/v1/checkouts/" . $token . "/payment";
        if ($this->isCurl()) {
            $responseObj = new \PlanetLib\Request\Curl();
            $options = array(
                CURLOPT_FOLLOWLOCATION => 1
            );
        } else {
            $responseObj = new \PlanetLib\Request\Stream();
            $options = null;
        }
        $response = $responseObj->getResponse($url, $options);
        $result = new Response($response);
        return $result;
    }

    /**
	 * To set the Url
	 *
     * @param $url
     * @return $this
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
	 * To Check the Curl version
	 *
     * @return bool
     */
    protected function isCurl() {
        return function_exists('curl_version');
    }

}
