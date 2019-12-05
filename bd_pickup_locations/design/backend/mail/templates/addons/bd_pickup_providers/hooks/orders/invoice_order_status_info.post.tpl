{if strlen($order_info.bd_pickup_provider_data)>0}
    {$bd_pickup_provider_data = $order_info.bd_pickup_provider_data|json_decode}
    <tr>
        <td with="50%">&nbsp;</td>
        <td with="50%">{__('addons.bd_pickup_providers.pickup_point')}: {$bd_pickup_provider_data->location}</td>
    </tr>
{/if}