<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Enabledisable
 *
 * @author mohds
 */
class PlanetPayment_Core_Model_System_Config_Source_Enabledisable {

    /**
     * To provide an option of enable for dropdown
     * 
     * @return array
     */
    public function toOptionArray() {
        return array(
            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('Enable')),
        );
    }

}
