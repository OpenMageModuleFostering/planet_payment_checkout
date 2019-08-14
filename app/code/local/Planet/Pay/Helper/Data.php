<?php
/**
 * Class Planet_Pay_Helper_Data
 */
class Planet_Pay_Helper_Data extends Mage_Checkout_Helper_Data
{
    
    
    
    /**
     * Returns data from the store config.
     *
     * @param string $key
     * @return string
     */
    public function getConfigData($key) {
        $path = 'payment/planet_pay/' . $key;
        return Mage::getStoreConfig($path);
    }
	/**
     * Send email for payment was failed
     *
     * @param Mage_Sales_Model_Quote $checkout
     * @param string $message
     * @param string $checkoutType
	 * @param int $orderId is unique id of the saved order for failed payment
     * @return Planet_Pay_Helper_Checkout_Mailhelper
     */
    public function sendPaymentFailedEmail($checkout, $message, $checkoutType = 'onepage')
    {
		$translate = Mage::getSingleton('core/translate');
		$lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
		$customerId = Mage::getSingleton('customer/session')->getCustomerId();
        //die($lastOrderId);
		/* @var $translate Mage_Core_Model_Translate */
        $translate->setTranslateInline(false);

        $mailTemplate = Mage::getModel('core/email_template');
        /* @var $mailTemplate Mage_Core_Model_Email_Template */

        //$template = Mage::getStoreConfig('checkout/payment_failed/template', $checkout->getStoreId());
		$template = $mailTemplate->loadDefault('checkout_payment_failed_template');
		//$template = $templateId = 2;
		
        $copyTo = $this->_getEmails('checkout/payment_failed/copy_to', $checkout->getStoreId());
		
        $copyMethod = Mage::getStoreConfig('checkout/payment_failed/copy_method', $checkout->getStoreId());
        if ($copyTo && $copyMethod == 'bcc') {
            $mailTemplate->addBcc($copyTo);
        }

        // to get receivers of the mail set by the admin in configuration
		$_reciever = Mage::getStoreConfig('checkout/payment_failed/reciever', $checkout->getStoreId());
		$sendTo = array(
            array(
                'email' => Mage::getStoreConfig('trans_email/ident_'.$_reciever.'/email', $checkout->getStoreId()),
                'name'  => Mage::getStoreConfig('trans_email/ident_'.$_reciever.'/name', $checkout->getStoreId())
            )
        );

        if ($copyTo && $copyMethod == 'copy') {
            foreach ($copyTo as $email) {
                $sendTo[] = array(
                    'email' => $email,
                    'name'  => null
                );
            }
        }
		
		// fetching and adding customer as recipient for failed email
		$custdetail[]=array("email"=>$checkout->getCustomerEmail(),'name'  => $checkout->getCustomerFirstname());
		$sendTo = array_merge ( $sendTo, $custdetail );
		
		// fetching shipping method
        $shippingMethod = '';
        if ($shippingInfo = $checkout->getShippingAddress()->getShippingMethod()) {
            $data = explode('_', $shippingInfo);
            $shippingMethod = $data[0];
        }

		//$shippingTotal = $shippingInfo->collectTotals();
		
		// fetching payment method
        $paymentMethod = '';
        if ($paymentInfo = $checkout->getPayment()) {
            $paymentMethod = $paymentInfo->getMethod();
        }
		
		// fetching currency code and symbol
		$currency_code = $checkout->getStoreCurrencyCode();
		$currency_symbol = Mage::app()->getLocale()->currency( $currency_code )->getSymbol();
		
		// fetching subtotal of current quote
		$sub_total = $checkout->getSubtotal();
		
		// fetching shipping amount of current quote
		$total_ship = $checkout->getShippingAddress()->getShippingAmount();
		
		// fetching grand total for current quote
		$total = $checkout->getGrandTotal();
		//$totals = $checkout->getTotals();
		
        
		// setting html for items to be set in template
		$items = '';
		$items = $checkout->getAllVisibleItems();
		
		//$block = Mage::app()->getLayout()
		//				->createBlock('core/template')
		//				->setData('items',$items)
		//				->setTemplate('planet_pay/email/items.phtml');
		//echo "<pre>";print_r($block); die();
		//$html = $block->getHtml();
		
		//print_r($html); die();
		//$items = 'items';
		
		$itemsHtml = '<table cellspacing="0" cellpadding="0" border="0" width="650" style="border:1px solid #EAEAEA;">
							<thead>
								<tr>
									<th align="left" bgcolor="#EAEAEA" style="font-size:13px; padding:3px 9px">Item</th>
									<th align="left" bgcolor="#EAEAEA" style="font-size:13px; padding:3px 9px">Sku</th>
									<th align="center" bgcolor="#EAEAEA" style="font-size:13px; padding:3px 9px">Qty</th>
									<th align="right" bgcolor="#EAEAEA" style="font-size:13px; padding:3px 9px">Subtotal</th>
								</tr>
							</thead>
							<tbody bgcolor="#F6F6F6">
						';
        foreach ($checkout->getAllVisibleItems() as $_item) {
            /* @var $_item Mage_Sales_Model_Quote_Item */
            //$items .= $_item->getProduct()->getName() . '  x '. $_item->getQty() . '  '
            //    . $checkout->getStoreCurrencyCode() . ' '
            //    . $_item->getProduct()->getFinalPrice($_item->getQty()) . "\n";
			
			$itemsHtml .= '<tr>
								<td align="left" valign="top" style="font-size:11px; padding:3px 9px; border-bottom:1px dotted #CCCCCC;">
									<strong style="font-size:11px;">'.$_item->getProduct()->getName().'</strong>
								</td>
								<td align="left" valign="top" style="font-size:11px; padding:3px 9px; border-bottom:1px dotted #CCCCCC;">'.$_item->getSku().'</td>
								<td align="center" valign="top" style="font-size:11px; padding:3px 9px; border-bottom:1px dotted #CCCCCC;">'.$_item->getQty().'</td>
								<td align="right" valign="top" style="font-size:11px; padding:3px 9px; border-bottom:1px dotted #CCCCCC;">
                                    <span class="price">'.$currency_symbol . number_format(($_item->getProduct()->getFinalPrice() * $_item->getQty()),2).'</span>            
								</td>
							</tr>';
		}
		
		$itemsHtml .= '</tbody>
    
							<tbody>
										<tr class="subtotal">
								<td colspan="3" align="right" style="padding:3px 9px">
												Subtotal                    </td>
								<td align="right" style="padding:3px 9px">
												<span class="price">'.$currency_symbol.number_format($sub_total,2).'</span>                    </td>
							</tr>
									<tr class="shipping">
								<td colspan="3" align="right" style="padding:3px 9px">
												Shipping &amp; Handling                    </td>
								<td align="right" style="padding:3px 9px">
												<span class="price">'.$currency_symbol.number_format($total_ship,2).'</span>                    </td>
							</tr>
									<tr class="grand_total">
								<td colspan="3" align="right" style="padding:3px 9px">
												<strong>Grand Total</strong>
											</td>
								<td align="right" style="padding:3px 9px">
												<strong><span class="price">'.$currency_symbol.number_format($total,2).'</span></strong>
											</td>
							</tr>
							</tbody>
						</table>';
        // items html formatting ends here
		
		
        foreach ($sendTo as $recipient) {
            $mailTemplate->setDesignConfig(array('area'=>'frontend', 'store'=>$checkout->getStoreId()))
                ->sendTransactional(
                $template,
                Mage::getStoreConfig('checkout/payment_failed/identity', $checkout->getStoreId()),
                $recipient['email'],
                $recipient['name'],
                array(
                    'reason' 		=> $message,
                    'checkoutType' 	=> $checkoutType,
                    'dateAndTime' 	=> Mage::app()->getLocale()->date(),
                    'customer_name' => $custdetail[0]['name'],//$recipient['name'],// . ' ' . $checkout->getCustomerLastname(),
                    'customer_email'=> $custdetail[0]['email'],//$checkout->getCustomerEmail(),
					'recipient_email'=> $recipient['email'],
					'recipient_name'=> $recipient['name'],
					'is_customer'	=> ($custdetail[0]['email'] == $recipient['email']),
					'is_guest'		=> empty($customerId),
				    'billingAddress'=> $checkout->getBillingAddress(),
                    'shippingAddress'=> $checkout->getShippingAddress(),
                    'shippingMethod'=> Mage::getStoreConfig('carriers/'.$shippingMethod.'/title'),
                    'paymentMethod' => Mage::getStoreConfig('payment/'.$paymentMethod.'/title'),
					'items' 		=> $itemsHtml,
                    'total' 		=> $total,
					'order_id'		=> $lastOrderId,
					'admin_order_link'=> Mage::getBaseUrl().'admin/sales_order/view/order_id/'.$lastOrderId.'/'
                )
            );
        }
		
        $translate->setTranslateInline(true);
		
        return $this;
    }

    /**
     * Is the payment method enabled?
     *
     * @return bool
     */
    public function isEnabled() {
        return (bool) $this->getConfigData('active');
    }
}