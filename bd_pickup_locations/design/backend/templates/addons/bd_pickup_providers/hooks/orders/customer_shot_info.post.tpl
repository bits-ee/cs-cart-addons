{if strlen($order_info.bd_pickup_provider_data)>0}
    {$bd_pickup_provider_data = $order_info.bd_pickup_provider_data|json_decode}
    <div class="well orders-right-pane form-horizontal">
        <div class="control-group">
            <div class="control-label">{__('addons.bd_pickup_providers.pickup_point')}</div>
            <div class="controls">
                {$bd_pickup_provider_data->location}
            </div>
        </div>
    </div>
{/if}