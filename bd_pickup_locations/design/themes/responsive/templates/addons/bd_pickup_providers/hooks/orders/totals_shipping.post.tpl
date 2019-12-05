{if strlen($order_info.bd_pickup_provider_data)>0}
    <br>
    <span id="bd_pickup_point">
        {$bd_pickup_provider_data = $order_info.bd_pickup_provider_data|json_decode}
        {$bd_pickup_provider_data->location}
    </span>
{/if}