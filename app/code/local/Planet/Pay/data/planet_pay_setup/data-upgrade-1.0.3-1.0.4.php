<?php

/**
 *  @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

$methodCodes = array(
    'VISA' => 6,
    'MASTER' => 6,
    'AMEX' => 6,
    'DISCOVER' => 6,
    'DINERS' => 6
);

/**
 * @var $model Planet_Pay_Model_Method
 */
$model = Mage::getModel('planet_pay/method');
/** @var Planet_Pay_Model_Resource_Method_Collection $collection */
$collection = $model->getCollection();
foreach ($collection as $method) {
    if (isset($methodCodes[$method->getBrandcode()])) {
        $method->setBrandid($methodCodes[$method->getBrandcode()]);
    } else {
        $method->setBrandid(99);
    }
    $method->save();
}