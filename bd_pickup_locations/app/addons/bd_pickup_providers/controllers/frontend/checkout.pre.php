<?php
if (!defined('BOOTSTRAP')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $mode == 'update_steps' && isset($_REQUEST['shipping_ids'])) { 
    
    $bd_pickup_sh_id = is_array($_REQUEST['shipping_ids']) ? (int)reset($_REQUEST['shipping_ids']) : (int)$_REQUEST['shipping_ids']; 

    if(isset($_REQUEST['bd_pickup_provider_data_for_'.$bd_pickup_sh_id])) {
        $bd_pickup_provider_data = json_decode($_REQUEST['bd_pickup_provider_data_for_'.$bd_pickup_sh_id], TRUE);
        $bd_pickup_provider_data['shipping_id'] = $bd_pickup_sh_id;
        $_SESSION['bd_pickup_provider']['shipping_id'] = $bd_pickup_sh_id;
        $_SESSION['bd_pickup_provider']['data'] = json_encode($bd_pickup_provider_data);
    }
}