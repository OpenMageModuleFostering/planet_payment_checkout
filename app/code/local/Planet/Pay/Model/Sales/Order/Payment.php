<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Order payment information
 *
 * @method Mage_Sales_Model_Resource_Order_Payment _getResource()
 * @method Mage_Sales_Model_Resource_Order_Payment getResource()
 * @method int getParentId()
 * @method Mage_Sales_Model_Order_Payment setParentId(int $value)
 * @method float getBaseShippingCaptured()
 * @method Mage_Sales_Model_Order_Payment setBaseShippingCaptured(float $value)
 * @method float getShippingCaptured()
 * @method Mage_Sales_Model_Order_Payment setShippingCaptured(float $value)
 * @method float getAmountRefunded()
 * @method Mage_Sales_Model_Order_Payment setAmountRefunded(float $value)
 * @method float getBaseAmountPaid()
 * @method Mage_Sales_Model_Order_Payment setBaseAmountPaid(float $value)
 * @method float getAmountCanceled()
 * @method Mage_Sales_Model_Order_Payment setAmountCanceled(float $value)
 * @method float getBaseAmountAuthorized()
 * @method Mage_Sales_Model_Order_Payment setBaseAmountAuthorized(float $value)
 * @method float getBaseAmountPaidOnline()
 * @method Mage_Sales_Model_Order_Payment setBaseAmountPaidOnline(float $value)
 * @method float getBaseAmountRefundedOnline()
 * @method Mage_Sales_Model_Order_Payment setBaseAmountRefundedOnline(float $value)
 * @method float getBaseShippingAmount()
 * @method Mage_Sales_Model_Order_Payment setBaseShippingAmount(float $value)
 * @method float getShippingAmount()
 * @method Mage_Sales_Model_Order_Payment setShippingAmount(float $value)
 * @method float getAmountPaid()
 * @method Mage_Sales_Model_Order_Payment setAmountPaid(float $value)
 * @method float getAmountAuthorized()
 * @method Mage_Sales_Model_Order_Payment setAmountAuthorized(float $value)
 * @method float getBaseAmountOrdered()
 * @method Mage_Sales_Model_Order_Payment setBaseAmountOrdered(float $value)
 * @method float getBaseShippingRefunded()
 * @method Mage_Sales_Model_Order_Payment setBaseShippingRefunded(float $value)
 * @method float getShippingRefunded()
 * @method Mage_Sales_Model_Order_Payment setShippingRefunded(float $value)
 * @method float getBaseAmountRefunded()
 * @method Mage_Sales_Model_Order_Payment setBaseAmountRefunded(float $value)
 * @method float getAmountOrdered()
 * @method Mage_Sales_Model_Order_Payment setAmountOrdered(float $value)
 * @method float getBaseAmountCanceled()
 * @method Mage_Sales_Model_Order_Payment setBaseAmountCanceled(float $value)
 * @method int getIdealTransactionChecked()
 * @method Mage_Sales_Model_Order_Payment setIdealTransactionChecked(int $value)
 * @method int getQuotePaymentId()
 * @method Mage_Sales_Model_Order_Payment setQuotePaymentId(int $value)
 * @method string getAdditionalData()
 * @method Mage_Sales_Model_Order_Payment setAdditionalData(string $value)
 * @method string getCcExpMonth()
 * @method Mage_Sales_Model_Order_Payment setCcExpMonth(string $value)
 * @method string getCcSsStartYear()
 * @method Mage_Sales_Model_Order_Payment setCcSsStartYear(string $value)
 * @method string getEcheckBankName()
 * @method Mage_Sales_Model_Order_Payment setEcheckBankName(string $value)
 * @method string getMethod()
 * @method Mage_Sales_Model_Order_Payment setMethod(string $value)
 * @method string getCcDebugRequestBody()
 * @method Mage_Sales_Model_Order_Payment setCcDebugRequestBody(string $value)
 * @method string getCcSecureVerify()
 * @method Mage_Sales_Model_Order_Payment setCcSecureVerify(string $value)
 * @method string getCybersourceToken()
 * @method Mage_Sales_Model_Order_Payment setCybersourceToken(string $value)
 * @method string getIdealIssuerTitle()
 * @method Mage_Sales_Model_Order_Payment setIdealIssuerTitle(string $value)
 * @method string getProtectionEligibility()
 * @method Mage_Sales_Model_Order_Payment setProtectionEligibility(string $value)
 * @method string getCcApproval()
 * @method Mage_Sales_Model_Order_Payment setCcApproval(string $value)
 * @method string getCcLast4()
 * @method Mage_Sales_Model_Order_Payment setCcLast4(string $value)
 * @method string getCcStatusDescription()
 * @method Mage_Sales_Model_Order_Payment setCcStatusDescription(string $value)
 * @method string getEcheckType()
 * @method Mage_Sales_Model_Order_Payment setEcheckType(string $value)
 * @method string getPayboxQuestionNumber()
 * @method Mage_Sales_Model_Order_Payment setPayboxQuestionNumber(string $value)
 * @method string getCcDebugResponseSerialized()
 * @method Mage_Sales_Model_Order_Payment setCcDebugResponseSerialized(string $value)
 * @method string getCcSsStartMonth()
 * @method Mage_Sales_Model_Order_Payment setCcSsStartMonth(string $value)
 * @method string getEcheckAccountType()
 * @method Mage_Sales_Model_Order_Payment setEcheckAccountType(string $value)
 * @method string getLastTransId()
 * @method Mage_Sales_Model_Order_Payment setLastTransId(string $value)
 * @method string getCcCidStatus()
 * @method Mage_Sales_Model_Order_Payment setCcCidStatus(string $value)
 * @method string getCcOwner()
 * @method Mage_Sales_Model_Order_Payment setCcOwner(string $value)
 * @method string getCcType()
 * @method Mage_Sales_Model_Order_Payment setCcType(string $value)
 * @method string getIdealIssuerId()
 * @method Mage_Sales_Model_Order_Payment setIdealIssuerId(string $value)
 * @method string getPoNumber()
 * @method Mage_Sales_Model_Order_Payment setPoNumber(string $value)
 * @method string getCcExpYear()
 * @method Mage_Sales_Model_Order_Payment setCcExpYear(string $value)
 * @method string getCcStatus()
 * @method Mage_Sales_Model_Order_Payment setCcStatus(string $value)
 * @method string getEcheckRoutingNumber()
 * @method Mage_Sales_Model_Order_Payment setEcheckRoutingNumber(string $value)
 * @method string getAccountStatus()
 * @method Mage_Sales_Model_Order_Payment setAccountStatus(string $value)
 * @method string getAnetTransMethod()
 * @method Mage_Sales_Model_Order_Payment setAnetTransMethod(string $value)
 * @method string getCcDebugResponseBody()
 * @method Mage_Sales_Model_Order_Payment setCcDebugResponseBody(string $value)
 * @method string getCcSsIssue()
 * @method Mage_Sales_Model_Order_Payment setCcSsIssue(string $value)
 * @method string getEcheckAccountName()
 * @method Mage_Sales_Model_Order_Payment setEcheckAccountName(string $value)
 * @method string getCcAvsStatus()
 * @method Mage_Sales_Model_Order_Payment setCcAvsStatus(string $value)
 * @method string getCcNumberEnc()
 * @method Mage_Sales_Model_Order_Payment setCcNumberEnc(string $value)
 * @method string getCcTransId()
 * @method Mage_Sales_Model_Order_Payment setCcTransId(string $value)
 * @method string getFlo2cashAccountId()
 * @method Mage_Sales_Model_Order_Payment setFlo2cashAccountId(string $value)
 * @method string getPayboxRequestNumber()
 * @method Mage_Sales_Model_Order_Payment setPayboxRequestNumber(string $value)
 * @method string getAddressStatus()
 * @method Mage_Sales_Model_Order_Payment setAddressStatus(string $value)
 *
 * @category    Mage
 * @package     Mage_Sales
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Planet_Pay_Model_Sales_Order_Payment extends Mage_Payment_Model_Info
{
    /**
     * Actions for payment when it triggered review state:
     *
     * Accept action
     */
    const REVIEW_ACTION_ACCEPT = 'accept';

    /**
     * Deny action
     */
    const REVIEW_ACTION_DENY   = 'deny';

    /**
     * Update action
     */
    const REVIEW_ACTION_UPDATE = 'update';

    /**
     * Order model object
     *
     * @var Mage_Sales_Model_Order
     */
    protected $_order;

    /**
     * Billing agreement instance that may be created during payment processing
     *
     * @var Mage_Sales_Model_Billing_Agreement
     */
    protected $_billingAgreement = null;

    /**
     * Whether can void
     * @var string
     */
    protected $_canVoidLookup = null;

    /**
     * Transactions registry to spare resource calls
     * array(txn_id => sales/order_payment_transaction)
     * @var array
     */
    protected $_transactionsLookup = array();

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'sales_order_payment';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'payment';

    /**
     * Transaction addditional information container
     *
     * @var array
     */
    protected $_transactionAdditionalInfo = array();

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('sales/order_payment');
    }
    /**
     * Decide whether authorization transaction may close (if the amount to capture will cover entire order)
     * @param float $amountToCapture
     * @return bool
     */
    protected function _isCaptureFinal($amountToCapture)
    {
        $amountToCapture = $this->_formatAmount($amountToCapture, true);
        $orderGrandTotal = $this->_formatAmount($this->getOrder()->getBaseGrandTotal(), true);
        if ($orderGrandTotal == $this->_formatAmount($this->getBaseAmountPaid(), true) + $amountToCapture) {
            if (false !== $this->getShouldCloseParentTransaction()) {
                $this->setShouldCloseParentTransaction(true);
            }
            return true;
        }
        return false;
    }
    /**
     * Round up and cast specified amount to float or string
     *
     * @param string|float $amount
     * @param bool $asFloat
     * @return string|float
     */
    protected function _formatAmount($amount, $asFloat = false)
    {
         $amount = Mage::app()->getStore()->roundPrice($amount);
         return !$asFloat ? (string)$amount : $amount;
    }
    //    /**
//     * TODO: implement this
//     * @param Mage_Sales_Model_Order_Invoice $invoice
//     * @return Mage_Sales_Model_Order_Payment
//     */
//    public function cancelCapture($invoice = null)
//    {
//    }

    /**
     * Create transaction,
     * prepare its insertion into hierarchy and add its information to payment and comments
     *
     * To add transactions and related information,
     * the following information should be set to payment before processing:
     * - transaction_id
     * - is_transaction_closed (optional) - whether transaction should be closed or open (closed by default)
     * - parent_transaction_id (optional)
     * - should_close_parent_transaction (optional) - whether to close parent transaction (closed by default)
     *
     * If the sales document is specified, it will be linked to the transaction as related for future usage.
     * Currently transaction ID is set into the sales object
     * This method writes the added transaction ID into last_trans_id field of the payment object
     *
     * To make sure transaction object won't cause trouble before saving, use $failsafe = true
     *
     * @param string $type
     * @param Mage_Sales_Model_Abstract $salesDocument
     * @param bool $failsafe
     * @return null|Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _addTransaction($type, $salesDocument = null, $failsafe = false)
    {
        if ($this->getSkipTransactionCreation()) {
            $this->unsTransactionId();
            return null;
        }

        // look for set transaction ids
        $transactionId = $this->getTransactionId();
        if (null !== $transactionId) {
            // set transaction parameters
            $transaction = false;
            if ($this->getOrder()->getId()) {
                $transaction = $this->_lookupTransaction($transactionId);
            }
            if (!$transaction) {
                $transaction = Mage::getModel('sales/order_payment_transaction')->setTxnId($transactionId);
            }
            $transaction
                ->setOrderPaymentObject($this)
                ->setTxnType($type)
                ->isFailsafe($failsafe);

            if ($this->hasIsTransactionClosed()) {
                $transaction->setIsClosed((int)$this->getIsTransactionClosed());
            }

            //set transaction addition information
            if ($this->_transactionAdditionalInfo) {
                foreach ($this->_transactionAdditionalInfo as $key => $value) {
                    $transaction->setAdditionalInformation($key, $value);
                }
            }

            // link with sales entities
            $this->setLastTransId($transactionId);
            $this->setCreatedTransaction($transaction);
            $this->getOrder()->addRelatedObject($transaction);
            if ($salesDocument && $salesDocument instanceof Mage_Sales_Model_Abstract) {
                $salesDocument->setTransactionId($transactionId);
                // TODO: linking transaction with the sales document
            }

            // link with parent transaction
            $parentTransactionId = $this->getParentTransactionId();

            if ($parentTransactionId) {
                $transaction->setParentTxnId($parentTransactionId);
                if ($this->getShouldCloseParentTransaction()) {
                    $parentTransaction = $this->_lookupTransaction($parentTransactionId);
                    if ($parentTransaction) {
                        if (!$parentTransaction->getIsClosed()) {
                            $parentTransaction->isFailsafe($failsafe)->close(false);
                        }
                        $this->getOrder()->addRelatedObject($parentTransaction);
                    }
                }
            }
            return $transaction;
        }
    }
     /**
     * Public acces to _addTransaction method
     *
     * @param string $type
     * @param Mage_Sales_Model_Abstract $salesDocument
     * @param bool $failsafe
     * @param string $message
     * @return null|Mage_Sales_Model_Order_Payment_Transaction
     */
    public function addTransaction($type, $salesDocument = null, $failsafe = false, $message = false)
    {
        $transaction = $this->_addTransaction($type, $salesDocument, $failsafe);

        if ($message) {
            $order = $this->getOrder();
            $message = $this->_appendTransactionToMessage($transaction, $message);
            $order->addStatusHistoryComment($message);
        }

        return $transaction;
    }
     /**
     * Prepend a "prepared_message" that may be set to the payment instance before, to the specified message
     * Prepends value to the specified string or to the comment of specified order status history item instance
     *
     * @param string|Mage_Sales_Model_Order_Status_History $messagePrependTo
     * @return string|Mage_Sales_Model_Order_Status_History
     */
    protected function _prependMessage($messagePrependTo)
    {
        $preparedMessage = $this->getPreparedMessage();
        if ($preparedMessage) {
            if (is_string($preparedMessage)) {
                return $preparedMessage . ' ' . $messagePrependTo;
            } elseif (is_object($preparedMessage)
                && ($preparedMessage instanceof Mage_Sales_Model_Order_Status_History)
            ) {
                $comment = $preparedMessage->getComment() . ' ' . $messagePrependTo;
                $preparedMessage->setComment($comment);
                return $comment;
            }
        }
        return $messagePrependTo;
    }
    /**
     * Authorize payment either online or offline (process auth notification)
     * Updates transactions hierarchy, if required
     * Prevents transaction double processing
     * Updates payment totals, updates order status and adds proper comments
     *
     * @param bool $isOnline
     * @param float $amount
     * @return Mage_Sales_Model_Order_Payment
     */
    protected function _authorize($isOnline, $amount)
    {
        // check for authorization amount to be equal to grand total
        $this->setShouldCloseParentTransaction(false);
        if (!$this->_isCaptureFinal($amount)) {
            $this->setIsFraudDetected(true);
        }

        // update totals
        $amount = $this->_formatAmount($amount, true);
        $this->setBaseAmountAuthorized($amount);

        // do authorization
        $order  = $this->getOrder();
        $state  = Mage_Sales_Model_Order::STATE_PROCESSING;
        $status = true;
        if ($isOnline) {
            // invoke authorization on gateway
            $this->getMethodInstance()->setStore($order->getStoreId())->authorize($this, $amount);
        }

        // similar logic of "payment review" order as in capturing
        if ($this->getIsTransactionPending()) {
            $message = Mage::helper('sales')->__('Authorizing amount of %s is pending approval on gateway.', $this->_formatPrice($amount));
            $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
            if ($this->getIsFraudDetected()) {
                $status = Mage_Sales_Model_Order::STATUS_FRAUD;
            }
        } else {
            if ($this->getIsFraudDetected()) {
                $state = Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW;
                $message = Mage::helper('sales')->__('Order is suspended as its authorizing amount %s is suspected to be fraudulent.', $this->_formatPrice($amount));
                $status = Mage_Sales_Model_Order::STATUS_FRAUD;
            } else {
                $message = Mage::helper('sales')->__('Authorized amount of %s.', $this->_formatPrice($amount));
            }
        }

        // update transactions, order state and add comments
        $transaction = $this->_addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH);
        if ($order->isNominal()) {
            $message = $this->_prependMessage(Mage::helper('sales')->__('Nominal order registered.'));
        } else {
            $message = $this->_prependMessage($message);
            $message = $this->_appendTransactionToMessage($transaction, $message);
        }
        $order->setState($state, $status, $message);

        return $this;
    }
     /**
     * Authorize or authorize and capture payment on gateway, if applicable
     * This method is supposed to be called only when order is placed
     *
     * @return Mage_Sales_Model_Order_Payment
     */
    public function place()
    {
        Mage::dispatchEvent('sales_order_payment_place_start', array('payment' => $this));
        $order = $this->getOrder();

        $this->setAmountOrdered($order->getTotalDue());
        $this->setBaseAmountOrdered($order->getBaseTotalDue());
        $this->setShippingAmount($order->getShippingAmount());
        $this->setBaseShippingAmount($order->getBaseShippingAmount());

        $methodInstance = $this->getMethodInstance();
        $methodInstance->setStore($order->getStoreId());
        $orderState = Mage_Sales_Model_Order::STATE_NEW;
        $stateObject = new Varien_Object();

        /**
         * Do order payment validation on payment method level
         */
        //$methodInstance->validate();
        $action = $methodInstance->getConfigPaymentAction();
        if ($action) {
            if ($methodInstance->isInitializeNeeded()) {
                /**
                 * For method initialization we have to use original config value for payment action
                 */
                $methodInstance->initialize($methodInstance->getConfigData('payment_action'), $stateObject);
            } else {
                $orderState = Mage_Sales_Model_Order::STATE_PROCESSING;
                switch ($action) {
                    case Mage_Payment_Model_Method_Abstract::ACTION_ORDER:
                        $this->_order($order->getBaseTotalDue());
                        break;
                    case Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE:
                        $this->_authorize(true, $order->getBaseTotalDue()); // base amount will be set inside
                        $this->setAmountAuthorized($order->getTotalDue());
                        break;
                    case Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE_CAPTURE:
                        $this->setAmountAuthorized($order->getTotalDue());
                        $this->setBaseAmountAuthorized($order->getBaseTotalDue());
                        $this->capture(null);
                        break;
                    default:
                        break;
                }
            }
        }

        $this->_createBillingAgreement();

        $orderIsNotified = null;
        if ($stateObject->getState() && $stateObject->getStatus()) {
            $orderState      = $stateObject->getState();
            $orderStatus     = $stateObject->getStatus();
            $orderIsNotified = $stateObject->getIsNotified();
        } else {
            $orderStatus = $methodInstance->getConfigData('order_status');
            if (!$orderStatus) {
                $orderStatus = $order->getConfig()->getStateDefaultStatus($orderState);
            } else {
                // check if $orderStatus has assigned a state
                $states = $order->getConfig()->getStatusStates($orderStatus);
                if (count($states) == 0) {
                    $orderStatus = $order->getConfig()->getStateDefaultStatus($orderState);
                }
            }
        }
        $isCustomerNotified = (null !== $orderIsNotified) ? $orderIsNotified : $order->getCustomerNoteNotify();
        $message = $order->getCustomerNote();

        // add message if order was put into review during authorization or capture
        if ($order->getState() == Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW) {
            if ($message) {
                $order->addStatusToHistory($order->getStatus(), $message, $isCustomerNotified);
            }
        } elseif ($order->getState() && ($orderStatus !== $order->getStatus() || $message)) {
            // add message to history if order state already declared
            $order->setState($orderState, $orderStatus, $message, $isCustomerNotified);
        } elseif (($order->getState() != $orderState) || ($order->getStatus() != $orderStatus) || $message) {
            // set order state
            $order->setState($orderState, $orderStatus, $message, $isCustomerNotified);
        }

        Mage::dispatchEvent('sales_order_payment_place_end', array('payment' => $this));

        return $this;
    }


    /**
     * Void payment either online or offline (process void notification)
     * NOTE: that in some cases authorization can be voided after a capture. In such case it makes sense to use
     *       the amount void amount, for informational purposes.
     * Updates payment totals, updates order status and adds proper comments
     *
     * @param bool $isOnline
     * @param float $amount
     * @param string $gatewayCallback
     * @return Mage_Sales_Model_Order_Payment
     */
    protected function _void($isOnline, $amount = null, $gatewayCallback = 'void')
    {
        $order = $this->getOrder();
        $authTransaction = $this->getAuthorizationTransaction();
        $this->_generateTransactionId(Mage_Sales_Model_Order_Payment_Transaction::TYPE_VOID, $authTransaction);
        $this->setShouldCloseParentTransaction(true);

        // attempt to void
        if ($isOnline) {
            $this->getMethodInstance()->setStore($order->getStoreId())->$gatewayCallback($this);
        }
        if ($this->_isTransactionExists()) {
            return $this;
        }

        // if the authorization was untouched, we may assume voided amount = order grand total
        // but only if the payment auth amount equals to order grand total
        if ($authTransaction && ($order->getBaseGrandTotal() == $this->getBaseAmountAuthorized())
            && (0 == $this->getBaseAmountCanceled())) {
            if ($authTransaction->canVoidAuthorizationCompletely()) {
                $amount = (float)$order->getBaseGrandTotal();
            }
        }

        if ($amount) {
            $amount = $this->_formatAmount($amount);
        }

        // update transactions, order state and add comments
        $transaction = $this->_addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_VOID, null, true);
        $message = $this->hasMessage() ? $this->getMessage() : Mage::helper('sales')->__('Voided authorization.');
        $message = $this->_prependMessage($message);
        if ($amount) {
            $message .= ' ' . Mage::helper('sales')->__('Amount: %s.', $this->_formatPrice($amount));
        }
        $message = $this->_appendTransactionToMessage($transaction, $message);
       // $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, $message);
        return $this;
    }

   
}
