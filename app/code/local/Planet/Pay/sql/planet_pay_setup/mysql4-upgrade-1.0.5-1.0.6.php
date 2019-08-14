<?php
	
	$installer = $this;

	$installer->startSetup();
	
	$installer->run("
		
		INSERT IGNORE INTO `{$installer->getTable('sales/order_status')}` (status,label) VALUES ('decline','Declined');
		
		INSERT IGNORE INTO `{$installer->getTable('sales/order_status_state')}` (status,state) VALUES ('decline','processing');
		       
	");
	
	$installer->endSetup();

	/*
    // Required tables
    $statusTable = $installer->getTable('sales/order_status');
    $statusStateTable = $installer->getTable('sales/order_status_state');

    // Insert statuses
    $installer->getConnection()->insertArray(
        $statusTable,
        array('status', 'label'),
        array(
            array(
                'status' => 'decline', 
                'label' => 'Declined'
            )
        )
    );

    // Insert states and mapping of statuses to states
    $installer->getConnection()->insertArray(
        $statusStateTable,
        array('status', 'state', 'is_default'),
            array(
                array(
                    'status' => 'decline', 
                    'state' => 'processing', 
                    'is_default' => 0
                )
            )
        );	
	
	*/
?>