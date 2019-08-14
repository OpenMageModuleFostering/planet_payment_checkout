<?php

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 *
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('planet_pay/method'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Entity id')
    ->addColumn('brandname', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false
    ), 'Brand Name')
    ->addColumn('brandcode', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false
    ), 'Brand Code')
    ->addColumn('active', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        'nullable' => false,
        'default'  => 1,
    ), 'Active');

$installer->getConnection()->createTable($table);
