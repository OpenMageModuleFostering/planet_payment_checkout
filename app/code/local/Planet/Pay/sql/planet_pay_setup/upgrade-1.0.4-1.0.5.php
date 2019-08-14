<?php

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 *
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('planet_pay/carddetail'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Entity id')
    ->addColumn('cust_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'nullable' => false
    ), 'Customer Id')
     ->addColumn('registeration_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => false
    ), 'Registeration Id')
     ->addColumn('brand_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => false
    ), 'Payment Brand')
     ->addColumn('payment_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => false
    ), 'Payment Type')
     ->addColumn('cc_last_four', Varien_Db_Ddl_Table::TYPE_VARCHAR, 11, array(
        'nullable' => false
    ), 'Card Number')
     ->addColumn('cc_exp_month', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'nullable' => false
    ), 'Expiry Month')
     ->addColumn('cc_exp_year', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array(
        'nullable' => false
    ), 'Expiry Year')
     ->addColumn('holder', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
        'nullable' => false
    ), 'Card Holder');
    

$installer->getConnection()->createTable($table);
