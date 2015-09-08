<?php


$update_order = array();
$data['host'] = $hostname;


if (isset($data['msg'])){

    require_once __DIR__ . DS . 'TransactionRequestBean.php';
    require_once __DIR__ . DS . 'TransactionResponseBean.php';

    $techprocess_encryption_key = get_option('techprocess_encryption_key', 'payments');
    $techprocess_encryption_iv = get_option('techprocess_encryption_iv', 'payments');
    $str = $data['msg'];
    $val = array();
    $val['key'] = $techprocess_encryption_key;
    $val['iv'] = $techprocess_encryption_iv;

    $transactionResponseBean = new TransactionResponseBean();
    $transactionResponseBean->setResponsePayload($str);
    $transactionResponseBean->setKey($val['key']);
    $transactionResponseBean->setIv($val['iv']);

    $response = $transactionResponseBean->getResponsePayload();
    $response_data = array();
    if (is_string($response)){
        $resp = explode("|", $response);
        foreach ($resp as $res) {
            $res_array = explode("=", $res);
            if (isset($res_array[0]) and isset($res_array[1])){
                $response_data[ $res_array[0] ] = $res_array[1];
            }
        }
    }


    if (isset($response_data['txn_msg']) and $response_data['txn_msg']=='success'){
        if (isset($response_data['txn_amt'])){
            $update_order['payment_amount'] = $response_data['txn_amt'];;
            $update_order['transaction_id'] = $response_data['tpsl_txn_id'];
            $update_order['is_paid'] = 1;
            $update_order['order_completed'] = 1;
        }
    }


}


