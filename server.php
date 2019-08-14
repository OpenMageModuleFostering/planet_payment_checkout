<?php
require_once('./app/Mage.php'); //Path to Magento
umask(0);
Mage::app();
$testMode = Mage::getStoreConfig('payment/planet_pay/test_mode');
if ($testMode) {
    $javurl = Mage::getStoreConfig('payment/planet_pay/payment_request_url_test');
} else {
    $javurl = Mage::getStoreConfig('payment/planet_pay/payment_request_url_live');
}
$baseurl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
$token = $_REQUEST['token'];
$urlf = $_REQUEST['url'];
$scripturl = $javurl . "/v1/paymentWidgets.js?checkoutId=" . $token;
$chobrand = $_REQUEST['chobrand'];
?>
<script src=<?php echo $scripturl; ?>></script>
<form action=<?php echo $urlf; ?> class="paymentWidgets">
 <?php echo $chobrand; ?>    
</form>


