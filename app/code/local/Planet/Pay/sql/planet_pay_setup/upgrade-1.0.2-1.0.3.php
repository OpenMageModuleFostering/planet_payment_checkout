<?php

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();
/**
 *
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('planet_pay/orderlog'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Entity Id')
    ->addColumn('planet_order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false
    ), 'Planet Order Id')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 64, array(
    ), 'Description')
    ->addColumn('code', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false
    ), 'Code')
    ->addColumn('method_name', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false
    ), 'Method Name')
    ->addColumn('success', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        'nullable' => false,
        'default'  => 0,
    ), 'Success')
    ->addColumn('request', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Request')
    ->addColumn('response', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Response')
    ->addColumn('date', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
    ), 'Date');

$installer->getConnection()->createTable($table);

$installer->getConnection()->modifyColumn($installer->getTable('planet_pay/order'),
                                          'create_date',
                                          array(
                                              'type' => Varien_Db_Ddl_Table::TYPE_DATETIME,
                                              'comment' => 'Create Date'
                                          ));

$installer->endSetup();