{if strlen($shipping.bd_pickup_provider)>0}
    {* $checked is inherited from parent template (shipping_method) and indicates if correcponding shipping method is selected *}
    <div id="bd_pickup_provider_{$shipping.bd_pickup_provider}" class="bd_pickup_provider">
        <select name="bd_pickup_provider_data_for_{$shipping.shipping_id}" 
                id="bd_pickup_provider_{$shipping.bd_pickup_provider}" 
                {if not $checked} disabled="disabled" {/if}>
            {fn_bd_pickup_providers_list_options_for($shipping.bd_pickup_provider) nofilter}            
        </select>
    </div>
{/if}