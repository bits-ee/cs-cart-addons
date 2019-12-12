{if strlen($order_info.bd_pickup_provider_data)>0}
    <tr>
        <td>{__("addons.bd_pickup_providers.pickup_point")}:&nbsp;</td>
        <td style="width: 57%" data-ct-orders-summary="summary-payment">
            {$bd_pickup_provider_data = $order_info.bd_pickup_provider_data|json_decode}
            {$bd_pickup_provider_data->location}
        </td>
    </tr>
{/if}