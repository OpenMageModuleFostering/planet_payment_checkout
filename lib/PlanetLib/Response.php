<?php
namespace PlanetLib;
use PlanetLib\Resources\Translation\ErrorCodes;

/**
 * Class for storage and checking response.
 *
 * Class Response
 * @package PlanetLib
 */
class Response
{
    protected $data;

    /**
     * @param $response
     */
    public function __construct($response)
    {
        $this->data = json_decode($response, true);
    }

    /**
     * Returns true if response is valid
     *
     * @return bool
     */
    public function isValid()
    {   
        $code = $this->data['result']['code'];
        
        if (!isset($this->data['result']['code'])){
            return false;
        }
       elseif(substr($this->data["result"]["code"], 0, 3) !== "000"){
            return false;
        }
        else{
        return true;
        }
    }

    /**
     * Returns message from response
     *
     * @param null $language
     * @return string
     */
    public function getMessage($language = null)
    {
        if (!is_null($language) && !is_null($this->getCode())) {
            $errorCodes = new ErrorCodes($language);
            if ($errorCodes->localeExists()) {
                return $errorCodes->getErrorMessage($this->getCode());
            }
        }
        if (isset($this->data["result"]["description"])){
            return $this->data["result"]['code'].'/'.$this->data["result"]["description"];
        }
       // return $this->data['transaction']['processing']['return']['message'];
    }

    /**
     *
     * @return string|null
     */
    public function getCode()
    {
        if (isset($this->data["result"]['code'])){
            return $this->data["result"]['code'];
        }
        return null;
    }

    /**
     * Retunrs timestamp of transaction processing
     *
     * @return string
     */
    public function getTime()
    {
        return $this->data['transaction']['processing']['timestamp'];
    }

    /**
     * @return null|string
     */
    public function getOrderId()
    {
        $orderId = null;
        if (isset($this->data['transaction']) && is_array($this->data['transaction']['criterions'])) {
            foreach ($this->data['transaction']['criterions'] as $criterion) {
                if ($criterion['name'] == 'ORDER.ID') {
                    $orderId = $criterion['value'];
                    break;
                }
            }
        }
        return $orderId;
    }

    /**
     * @return null|string
     */
    public function getTransactionId()
    {
        return $this->data['id'];
    }

    /**
     * Returns all response as array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

} 