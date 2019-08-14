<?php
namespace PlanetLib\Payment;

use PlanetLib\Filter\FilterRound;
use PlanetLib\Filter\FilterString;
use PlanetLib\Validator\ValidatorFloat;
use PlanetLib\Validator\ValidatorInt;
use PlanetLib\Validator\ValidatorString;

/**
 * Class PaymentOrderposition
 * @package PlanetLib\Payment
 */
class PaymentOrderposition
{
    protected $mapper;
    protected $payment;

    protected $articleNumber;
    protected $articleDescription;
    protected $price;
    protected $priceOriginal;
    protected $quantity;
    protected $taxrate;
    protected $discount;
    protected $discountPct;
    protected $ean;
    protected $grossweight;
    protected $netweight;
    
    // Gross (brutto) price of product. 
    // Excluding all other costs. Discount is not subtracted.
    protected $shopPrice;
    
    // Index of order item. Necessary because first items are handeled differently.
    protected $index;

    /**
     *
     */
    public function __construct(\PlanetLib\Payment\PaymentCriterion $payment)
    {
    	$this->payment = $payment;
        $this->mapper = $this->fillMapper();
        
    }

    /**
     * @param $orderPositionIndex
     * @return array
     */
    public function setIndex($orderPositionIndex)
    {
    	$validator = new ValidatorInt();
    	if (!$validator->isValid($orderPositionIndex)) {
    		return $validator->getErrorMessages();
    	}
    	
    	$this->index = (int) $orderPositionIndex;
        return true;
    }

    /**
     * @param $data
     * @param null|PaymentCriterion $paymentCriterion
     * @return array|bool
     */
    public function setData($data, $paymentCriterion = null)
    {
        $errors = array();
        foreach ($this->mapper as $name) {
            $value = isset($data[$name]) ? $data[$name] : null;
            $methodName = 'set'.ucfirst($name);
            if (method_exists($this, $methodName)) {
                $result = call_user_func_array(array($this, $methodName) , array($value));
                if (is_array($result)) {
                    $errors[$name] = $result;
                }
            }
        }
        $this->_generatePrice();
        if (count($errors) == 0) {
            return true;
        }
        return $errors;
    }

    /**
     * @param $ord
     * @param bool $internalNames
     * @param bool $converToString
     * @return array
     */
    public function toArray($ord, $internalNames = false, $converToString = false)
    {
    	// refresh prices
    	$this->_generatePrice();
    	
        $data = array();

        if ($internalNames) {
            foreach ($this->mapper as $name) {
                $data[$name] = $this->$name;
            }
            $data['index'] = $this->getIndex();
        }
        else {
            $ordinaryIndexName = 'CRITERION.ORDERPOSITION.' . $ord . '.';
            foreach ($this->mapper as $index => $name) {
                $data[$ordinaryIndexName.$index] = $this->$name;
            }
            $data[$ordinaryIndexName.'index'] = $this->getIndex();
        }
        if ($converToString) {
            $data = FilterString::convertValuesToString($data);
        }
        return $data;
    }

    /**
     * @param mixed $orderpositionArticleDescription
     * @return array|bool
     */
    public function setArticleDescription($orderpositionArticleDescription)
    {
        $validator = new ValidatorString();
        if (!$validator->isValid($orderpositionArticleDescription)) {
            return $validator->getErrorMessages();
        }
        $this->articleDescription = $orderpositionArticleDescription;
        return true;
    }

    /**
     * @param mixed $orderpositionArticleNumber
     * @return array|bool
     */
    public function setArticleNumber($orderpositionArticleNumber)
    {
        $validator = new ValidatorString();
        if (!$validator->isValid($orderpositionArticleNumber)) {
            return $validator->getErrorMessages();
        }
        $this->articleNumber = $orderpositionArticleNumber;
        return true;
    }

    /**
     * @param mixed $orderpositionDiscount
     * @return array|bool
     */
    public function setDiscount($orderpositionDiscount)
    {
        $validator = new ValidatorFloat(array('empty' => true));
        if (!$validator->isValid($orderpositionDiscount)) {
            return $validator->getErrorMessages();
        }
        $this->discount = FilterRound::round($orderpositionDiscount, 2);
        return true;
    }

    /**
     * @param mixed $orderpositionDiscountPct
     * @return array|bool
     */
    public function setDiscountPct($orderpositionDiscountPct)
    {
        $validator = new ValidatorFloat(array('empty' => true));
        if (!$validator->isValid($orderpositionDiscountPct)) {
            return $validator->getErrorMessages();
        }
        $this->discountPct = FilterRound::round($orderpositionDiscountPct, 2);
        return true;
    }

    /**
     * @param mixed $orderpositionEan
     * @return array|bool
     */
    public function setEan($orderpositionEan)
    {
        $validator = new ValidatorString();
        if (!$validator->isValid($orderpositionEan)) {
            return $validator->getErrorMessages();
        }
        $this->ean = $orderpositionEan;
        return true;
    }

    /**
     * @param mixed $orderpositionGrossweight
     * @return array|bool
     */
    public function setGrossweight($orderpositionGrossweight)
    {
        $validator = new ValidatorFloat(array('empty' => true));
        if (!$validator->isValid($orderpositionGrossweight)) {
            return $validator->getErrorMessages();
        }
        $this->grossweight = $orderpositionGrossweight;
        return true;
    }

    /**
     * @param mixed $orderpositionNetweight
     * @return array|bool
     */
    public function setNetweight($orderpositionNetweight)
    {
        $validator = new ValidatorFloat(array('empty' => true));
        if (!$validator->isValid($orderpositionNetweight)) {
            return $validator->getErrorMessages();
        }
        $this->netweight = $orderpositionNetweight;
        return true;
    }

    /**
     * @param mixed $orderpositionQuantity
     * @return array|bool
     */
    public function setQuantity($orderpositionQuantity)
    {
        $validator = new ValidatorInt(array('min' => 1));
        if (!$validator->isValid($orderpositionQuantity)) {
            return $validator->getErrorMessages();
        }
        $this->quantity = $orderpositionQuantity;
        return true;
    }

    /**
     * @param mixed $orderpositionTaxrate
     * @return array|bool
     */
    public function setTaxrate($orderpositionTaxrate)
    {
        $validator = new ValidatorFloat(array('empty' => true));
        if (!$validator->isValid($orderpositionTaxrate)) {
            return $validator->getErrorMessages();
        }
        $this->taxrate = FilterRound::round($orderpositionTaxrate, 2);
        return true;
    }
    
    /**
     * @param mixed $orderpositionArticleDescription
     * @return array|bool
     */
    public function setShopPrice($orderpositionShopPrice)
    {
    	$validator = new ValidatorFloat();
    	if (!$validator->isValid($orderpositionShopPrice)) {
    		return $validator->getErrorMessages();
    	}
    	$this->shopPrice = $orderpositionShopPrice;
    	
    	$this->_generatePrice();
    	return true;
    }   

    /**
     * @return float 
     */
    public function getShopPrice()
    {
    	return FilterRound::round($this->shopPrice, 2);
    }
    
    /**
     * @param mixed $orderpositionPrice
     * @return array|bool
     */
    protected function _setPrice($orderpositionPrice)
    {
    	$validator = new ValidatorFloat(array('min' => 0.01));
    	if (!$validator->isValid($orderpositionPrice)) {
    		return $validator->getErrorMessages();
    	}
    	$this->price = FilterRound::round($orderpositionPrice, 2);
    	return true;
    }
    
    /**
     * @param $orderpositionPriceOriginal
     * @return array|bool
     */
    protected function _setPriceOriginal($orderpositionPriceOriginal)
    {
    	$validator = new ValidatorFloat(array('min' => 0.01));
    	if (!$validator->isValid($orderpositionPriceOriginal)) {
    		return $validator->getErrorMessages();
    	}
    	$this->priceOriginal = FilterRound::round($orderpositionPriceOriginal, 2);
    	return true;
    }
    

    /**
     * @return mixed
     */
    public function getTaxrate()
    {
        return FilterRound::round($this->taxrate, 2);
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return FilterRound::round($this->price, 2);
    }

    /**
     * @return mixed
     */
    public function getPriceOriginal()
    {
    	return FilterRound::round($this->priceOriginal, 2);
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
    	return FilterRound::round($this->discount, 2);
    }
    
    /**
     * @return mixed
     */
    public function getDiscountPercent()
    {
    	return FilterRound::round($this->discountPct, 2);
    }
    
    /**
     * @param PaymentCriterion $paymentCriterion
     * @return mixed
     */
    private function _generatePrice()
    {
    	$paymentCriterion = $this->payment;
    	/*
    	 * for first order item add shipping costs, administration fee 
    	 * and additional transportation costs in priceOriginal
    	 */ 
    	$priceOriginal = $this->getShopPrice();
    	if($this->getIndex() == 1)
    	{	
    		$orderShippingCosts = $paymentCriterion->getOrderShippingCosts();
    		$orderAdministrationFee = $paymentCriterion->getOrderAdministrationFee();
    		$orderAdditionalTransportationCosts = $paymentCriterion->getOrderTransportationCosts();
    		// add costs from order 
    		$priceOriginal = $priceOriginal + $orderShippingCosts + $orderAdministrationFee + $orderAdditionalTransportationCosts; 
    	}
    	$this->_setPriceOriginal($priceOriginal);
    	
    	/*
    	 *  calculate price: including installments
    	 *  
    	 *  Discounts:
    	 *    P = gross price of article in shop
    	 *    ORDERPOSITION.X.PRICE = P - (P * $this->discountPct / 100) - $this->discount;
    	 *  	
    	 *  installments:
    	 *    P = gross price of article in shop, discount subtracted
    	 *    ORDERPOSITION.X.PRICE = P + P * (InterestRate /12*months) = P + P * (0.139/12*12)
    	 *    
    	 */
    	
    	$shopPrice = $this->getShopPrice();
    	$price = $shopPrice; 
    	// discounts
    	// $price = $shopPrice - ($shopPrice * $this->discountPct / 100) - $this->discount;
    	
    	// installments
    	if(!is_null($paymentCriterion) && !is_null($paymentCriterion->getOrderInstallmentsIir()))
    	{
    		$price = $price + $price * (((float)$paymentCriterion->getOrderInstallmentsIir() / 100) / 12 * $paymentCriterion->getOrderInstallmentsCount());
    	}
    	
    	
    	$this->_setPrice($price);

    	return true;
    }
    
    /**
     * returns numeric index of order item (1...n)
     * @return int
     */ 
    public function getIndex()
    {
    	return (int) $this->index;
    }
    
    /**
     * @return array
     */
    private function fillMapper()
    {
        return array('ARTICLE_NUMBER'      => 'articleNumber',
                     'ARTICLE_DESCRIPTION' => 'articleDescription',
                     'PRICE'               => 'price',
        			 'ORIG_PRICE'          => 'priceOriginal',
                     'QUANTITY'            => 'quantity',
                     'TAXRATE'             => 'taxrate',
                     'DISCOUNT'            => 'discount',
                     'DISCOUNT_PCT'        => 'discountPct',
                     'EAN'                 => 'ean',
                     'GROSSWEIGHT'         => 'grossweight',
                     'NETWEIGHT'           => 'netweight',
        			 'SHOP_PRICE'  		   => 'shopPrice');
    }


} 