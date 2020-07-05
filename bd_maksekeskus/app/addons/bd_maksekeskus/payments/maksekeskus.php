<?php
use Tygh\Registry;
use Tygh\Http;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

if (defined('PAYMENT_NOTIFICATION')) {
    /* processing payment gateway response */

    fn_log_event('requests', 'http', ['url' => $_REQUEST['dispatch'], 'data' => json_encode($_REQUEST, JSON_PRETTY_PRINT)]);

    if(!isset($_REQUEST['json']) || !isset($_REQUEST['mac']) || $_REQUEST['payment'] !== 'maksekeskus') {
        fn_log_event('general', 'runtime', ['message' => "Malformed response from maksekeskus"]);
        exit;
    }

    $payload = json_decode($_REQUEST['json'], true);
    
    $order_id = $payload['reference'];
    $payment_id = db_get_field("SELECT payment_id FROM ?:orders WHERE order_id = ?i", $order_id);
    $processor_data = fn_get_payment_method_data($payment_id);

    $mac = strtoupper(hash('sha512', $_REQUEST['json'].$processor_data['processor_params']['api_secret']));

    if(!fn_check_payment_script('maksekeskus.php', $order_id)) {
        fn_log_event('general', 'runtime', ['message' => "Maksekeskus is not expected as payment gateway for Order ID: ".$order_id]);
        exit;
    }

    if ($mac !== $_REQUEST['mac']) {
        fn_log_event('general', 'runtime', ['message' => "Wrong MAC for Order ID: ".$order_id]);
        exit;
    }

    $pp_response = [];
    $pp_response['transaction_id'] = $payload['transaction'];
    $pp_response['reason_text'] = $payload['status']." ".$payload['amount']." ".$payload['currency']." ".$payload['customer_name']." ".date("d.m.Y H:i:s", strtotime($payload['message_time']));

    switch ($payload['status']) {
        case 'COMPLETED':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['completed'];
            break;
        case 'APPROVED':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['approved'];
            break;               
        case 'CREATED':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['created'];
            break;
        case 'PENDING':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['pending'];
            break;
        case 'EXPIRED':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['expired'];
            break;
        case 'CANCELLED':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['cancelled'];
            break;
        case 'PART_REFUNDED':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['part_refunded'];
            break;
        case 'REFUNDED':
            $pp_response['order_status'] = $processor_data['processor_params']['status']['refunded'];
            break;
        default:
            $pp_response['order_status'] = STATUS_INCOMPLETED_ORDER; 
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

    $payment_data = [
        'shop' => $processor_data['processor_params']['shop_id'],
        'amount' => $order_info['total'],
        'reference' => $order_info['order_id'],
        'country' => strtolower($order_info['b_country']), 
        'currency' => 'EUR',
        'locale' => (CART_LANGUAGE == 'ee' ? 'et' : CART_LANGUAGE)
    ];

    $json = json_encode($payment_data);

    $fields = [
        "json" => $json,
        "mac" => strtoupper(hash('sha512', $json.$processor_data['processor_params']['api_secret']))
    ];

    $url = $processor_data['processor_params']['env'] === 'live' ? MAKSEKESKUS_GW_URL : MAKSEKESKUS_GW_TEST_URL;

    fn_create_payment_form($url, $fields, 'maksekeskus');
}

exit;