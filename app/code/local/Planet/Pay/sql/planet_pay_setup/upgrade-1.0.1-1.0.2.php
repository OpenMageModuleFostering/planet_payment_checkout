<?php

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 *
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('planet_pay/order'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Entity Id')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false
    ), 'Order Id')
    ->addColumn('payment_status', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false
    ), 'Payment Status')
    ->addColumn('total_order_sum', Varien_Db_Ddl_Table::TYPE_DECIMAL, array(12,4), array(
        'nullable'  => false,
        'default'   => '0.0000'
    ), 'Total Order Sum')
    ->addColumn('payment_request', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Payment Request')
    ->addColumn('payment_response', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
    ), 'Payment Response')
    ->addColumn('create_date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
    ), 'Create Date')
    ->addColumn('transaction_id', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
    ), 'Transaction Id')
    ->addColumn('brand_name', Varien_Db_Ddl_Table::TYPE_TEXT, 32, array(
        'nullable' => false
    ), 'Brand Name');

$installer->getConnection()->createTable($table);