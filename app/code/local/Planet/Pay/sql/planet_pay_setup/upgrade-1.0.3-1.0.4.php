<?php

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('planet_pay/method'),
                                          'brandid',
                                          array(
                                              'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
                                              'length'    => 8,
                                              'comment' => 'Brand Id'
                                          ));

$installer->endSetup();