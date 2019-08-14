<?php
/**
 *  @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

$methodCodes = array('VISA', 'MASTER', 'AMEX', 'DISCOVER', 'DINERS');

/**
 * @var $model Planet_Pay_Model_Method
 */
$model = Mage::getModel('planet_pay/method');
/** @var Planet_Pay_Model_Resource_Method_Collection $collection */
$collection = $model->getCollection();
/** @var Planet_Pay_Model_Method $method */
foreach ($collection as $method) {
    if (!in_array($method->getBrandcode(), $methodCodes)) {
        $method->delete();
    }
}