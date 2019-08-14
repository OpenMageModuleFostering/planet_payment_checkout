<?php
/**
 *  @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * @var $model Planet_Pay_Model_Method
 */
$model = Mage::getModel('planet_pay/method');

$autoloader = new Planet_Pay_Model_Autoloader();
$autoloader->init();

$paymentMethods = \PlanetLib\Resources\Content\Brands::getBrands();

foreach ($paymentMethods as $brandCode => $brandName) {
    $data = array(
        'brandname' => $brandName,
        'brandcode' => $brandCode,
        'active'    => 1
    );

    $model->setData($data)->setOrigData()->save();
}