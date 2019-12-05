{if strlen($order_info.bd_pickup_provider_data)>0}
    {$bd_pickup_provider_data = $order_info.bd_pickup_provider_data|json_decode}
    <hr>
    <div class="control-group">
        <label class="control-label" for="carrier_key">{__('addons.bd_pickup_providers.pickup_point')}</label>
        <div class="controls">{$bd_pickup_provider_data->location}</div>
    </div>
{/if}