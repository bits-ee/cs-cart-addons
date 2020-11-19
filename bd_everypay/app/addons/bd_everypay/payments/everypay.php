<?php
use Tygh\Registry;
use Tygh\Http;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if (defined('PAYMENT_NOTIFICATION')) {
    /* processing payment gateway response */

    if(!isset($_REQUEST['payment_reference']) || !isset($_REQUEST['order_reference']) || $_REQUEST['payment'] !== 'everypay') {
        fn_log_event('general', 'runtime', ['message' => "Malformed response from everypay"]);
        exit;
    }
    
    $order_id = (int)$_REQUEST['order_reference'];

    if(!fn_check_payment_script('everypay.php', $order_id)) {
        fn_log_event('general', 'runtime', ['message' => "Everypay is not expected as payment gateway for Order ID: ".$order_id]);
        exit;
    }    

    $payment_id = db_get_field("SELECT payment_id FROM ?:orders WHERE order_id = ?i", $order_id);
    $processor_data = fn_get_payment_method_data($payment_id);

    $params = [
        'api_username' => $processor_data['processor_params']['api_username'],
        'payment_reference' => $_REQUEST['payment_reference']
    ];

    $api_host = $processor_data['processor_params']['env'] === 'live' ? EVERYPAY_PROD_URL : EVERYPAY_TEST_URL;
    $api_url = $api_host.'/payments/'.$_REQUEST['payment_reference'];

    $extra['basic_auth'][0] = $processor_data['processor_params']['api_username'];
    $extra['basic_auth'][1] = $processor_data['processor_params']['api_password'];
    
    $response = Http::get($api_url, $params, $extra);
    $response = json_decode($response, true, JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);
    
    if (isset($response['error']) || $response['order_reference'] != $order_id) {
        fn_log_event('general', 'runtime', ['message' => "Unable to update order status based on Everypay response for Order ID: ".$order_id]);
        exit;
    }

    $pp_response = [];
    $pp_response['transaction_id'] = $response['stan'];

    if (isset($response['cc_details'])) {
        $pp_response['reason_text'] = ucfirst($response['payment_state'])." ".$response['standing_amount']." EUR on ".date("d.m.Y H:i:s", strtotime($response['transaction_time']))." with ".$response['cc_details']['type']." valid until ".$response['cc_details']['month']."/".$response['cc_details']['year']." issued by ".$response['cc_details']['issuer']." (".$response['cc_details']['issuer_country'].") to ".$response['cc_details']['holder_name'];
    } elseif (isset($response['ob_details'])) {
        $pp_response['reason_text'] = ucfirst($response['payment_state'])." ".$response['standing_amount']." EUR on ".date("d.m.Y H:i:s", strtotime($response['transaction_time']))." with ".$response['payment_method']." from IBAN ".$response['ob_details']['debtor_iban'];
    } else {
        $pp_response['reason_text'] = ucfirst($response['payment_state'])." on ".date("d.m.Y H:i:s", strtotime($response['transaction_time']));
    }

    switch ($response['payment_state']) {
        case 'authorised':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['authorised'];
            break;
        case 'settled':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['settled'];
            break;
        case 'abandoned':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['abandoned'];
            break;               
        case 'voided':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['voided'];
            break;
        case 'failed':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['failed'];
            break;
        default:
            $pp_response['order_status'] = STATUS_INCOMPLETED_ORDER;
            $pp_response['everypay_status'] = $response['payment_state'];
    }

    fn_update_order_payment_info($order_id, $pp_response);
    fn_change_order_status($order_id, $pp_response['order_status']);

    if ($mode === 'return') {
        if ($pp_response['order_status'] === STATUS_INCOMPLETED_ORDER) {
            fn_redirect('checkout.checkout');
        } else {
            fn_finish_payment($order_id, $pp_response, true); //false means no order status notifications  
            fn_order_placement_routines('route', $order_id);
        }
    }

} else { 
    /* initiating request to payment gateway */

    $params = [
        'api_username' => $processor_data['processor_params']['api_username'],
        'account_name' => $processor_data['processor_params']['account_name'],
        'amount' => $order_info['total'],
        'customer_url' => Registry::get('config.https_location').'/?dispatch=payment_notification.return&payment=everypay',
        'order_reference' => $order_info['order_id'],
        'nonce' => uniqid(),
        'email' => $order_info['email'],
        'customer_ip' => $order_info['ip_address'], 
        'timestamp' => date(DateTime::ISO8601),
        'locale' => (CART_LANGUAGE == 'ee' ? 'et' : CART_LANGUAGE)
    ];

    $api_host = $processor_data['processor_params']['env'] === 'live' ? EVERYPAY_PROD_URL : EVERYPAY_TEST_URL;
    $api_url = $api_host.'/payments/oneoff';

    $extra['basic_auth'][0] = $processor_data['processor_params']['api_username'];
    $extra['basic_auth'][1] = $processor_data['processor_params']['api_password'];
    
    $response = Http::post($api_url, $params, $extra);
    $response = json_decode($response, true, JSON_PRESERVE_ZERO_FRACTION | JSON_NUMERIC_CHECK);
    if (!isset($response['error'])) {
        fn_redirect($response['payment_link'], true);
    }
}

exit;