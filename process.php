<?php
$techprocess_locator_url =  get_option('techprocess_locator_url', 'payments'); 
 
if($techprocess_locator_url == false) {
$techprocess_locator_url = 'https://www.tekprocess.co.in/PaymentGateway/TransactionDetailsNew.wsdl';	 
}


$techprocess_mrctCode = get_option('techprocess_mrctCode', 'payments');
$techprocess_schemeCode = get_option('techprocess_schemeCode', 'payments'); 
$techprocess_encryption_key = get_option('techprocess_encryption_key', 'payments');
$techprocess_encryption_iv = get_option('techprocess_encryption_iv', 'payments'); 

$payment_currency = get_option('payment_currency', 'payments');
$payment_currency_rate = get_option('payment_currency_rate', 'payments');


$strCurDate = date('d-m-Y');

$itc = $place_order['item_name'].' - '.$place_order['payment_verify_token'];

$mrctTxtID = date('YmdHis').$place_order['id'];



$val = array();
$val['locatorURL'] = $techprocess_locator_url;
$val['mrctCode'] = $techprocess_mrctCode;
$val['custname'] = $place_order['first_name'].' '.$place_order['item_name'];
$val['reqType'] = 'T';
$val['itc'] = $itc;
$val['mrctTxtID'] = $mrctTxtID;
$val['amount'] = $place_order['amount'];
$val['currencyType'] = $place_order['currency'];
//$val['returnURL'] = $mw_return_url;
$val['returnURL'] = $mw_ipn_url;

$val['s2SReturnURL'] = $mw_ipn_url;
$val['mrctCode'] = $techprocess_mrctCode;
$val['txnDate'] = $strCurDate;
$val['timeOut'] = 30;
$val['key']= $techprocess_encryption_key;
$val['iv']= $techprocess_encryption_iv;
//$mw_cancel_url

//•	ShoppingCartDetails parameter description are as follows
//	Sample: - “test_10.0_0.0”
//o	test = Scheme Code  
//o	10.0 = Transaction Amount
//o	0.0 = commission for this transaction (constant value 0.0)
$val['reqDetail'] = $techprocess_schemeCode.'_'.$val['amount'].'_0.0';


//$val['reqDetail'] = $place_order['item_name'];
//$val['reqDetail'] = $place_order['item_name'];
//$val['reqDetail'] = $place_order['item_name'];
//$val['reqDetail'] = $place_order['item_name'];

if(user_id()){
	$val['custID'] = user_id();
} else {
	$val['custID'] = date('YmdHis');
}


 
require_once __DIR__.DS.'TransactionRequestBean.php';
require_once __DIR__.DS.'TransactionResponseBean.php';

    $transactionRequestBean = new TransactionRequestBean();


$transactionRequestBean->setMerchantCode($val['mrctCode']);
  //  $transactionRequestBean->setAccountNo($val['tpvAccntNo']);
    $transactionRequestBean->setITC($val['itc']);
    //$transactionRequestBean->setMobileNumber($val['mobNo']);
    $transactionRequestBean->setCustomerName($val['custname']);
    $transactionRequestBean->setRequestType($val['reqType']);
    $transactionRequestBean->setMerchantTxnRefNumber($val['mrctTxtID']);
    $transactionRequestBean->setAmount($val['amount']);
    $transactionRequestBean->setCurrencyCode($val['currencyType']);
    $transactionRequestBean->setReturnURL($val['returnURL']);
    $transactionRequestBean->setS2SReturnURL($val['s2SReturnURL']);
    $transactionRequestBean->setShoppingCartDetails($val['reqDetail']);
    $transactionRequestBean->setTxnDate($val['txnDate']);
    //$transactionRequestBean->setBankCode($val['bankCode']);
    //$transactionRequestBean->setTPSLTxnID($val['tpsl_txn_id']);
    $transactionRequestBean->setCustId($val['custID']);
   // $transactionRequestBean->setCardId($val['cardID']);
    $transactionRequestBean->setKey($val['key']);
    $transactionRequestBean->setIv($val['iv']);
    $transactionRequestBean->setWebServiceLocator($val['locatorURL']);
    //$transactionRequestBean->setMMID($val['mmid']);
    //$transactionRequestBean->setOTP($val['otp']);
    //$transactionRequestBean->setCardName($val['cardName']);
    //$transactionRequestBean->setCardNo($val['cardNo']);
   // $transactionRequestBean->setCardCVV($val['cardCVV']);
    //$transactionRequestBean->setCardExpMM($val['cardExpMM']);
    //$transactionRequestBean->setCardExpYY($val['cardExpYY']);
    //$transactionRequestBean->setTimeOut($val['timeOut']);

    $url = $transactionRequestBean->getTransactionToken();

    $responseDetails = $transactionRequestBean->getTransactionToken();
    $responseDetails = (array)$responseDetails;
    $response = $responseDetails[0];

    if(is_string($response) && preg_match('/^msg=/',$response)){
        $outputStr = str_replace('msg=', '', $response);
        $outputArr = explode('&', $outputStr);
        $str = $outputArr[0];

        $transactionResponseBean = new TransactionResponseBean();
        $transactionResponseBean->setResponsePayload($str);
        $transactionResponseBean->setKey($val['key']);
        $transactionResponseBean->setIv($val['iv']);

        $response = $transactionResponseBean->getResponsePayload();
        
    }elseif(is_string($response) && preg_match('/^txn_status=/',$response)){
		 
	}

 if(substr($response, 0, 4) == 'http'){
	/* $response = "<script>";
	 $response .= "window.location.href = '{$response}';";
	 $response .= "</script>";*/
	 $place_order['redirect'] =$response;
	 $place_order['success'] ="Redirecting to payment provider";

 } else {
	 $response = print_r($response, true);
	 $place_order['success'] =$response;
 }
 // dd(substr($response, 0, 4));
// Let's start the train! 
$place_order['order_completed'] = 1;
$place_order['is_paid'] = 0;
