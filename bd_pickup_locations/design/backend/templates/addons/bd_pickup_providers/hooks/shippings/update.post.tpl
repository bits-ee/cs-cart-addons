<div class="control-group">
  <label class="control-label" for="bd_pickup_provider">{__("addons.bd_pickup_providers.pickup_provider")}:</label>
  <div class="controls">
    <select name="shipping_data[bd_pickup_provider]" id="bd_pickup_provider">
        <option value="" {if !$shipping.bd_pickup_provider}selected="selected"{/if}>-</option>
        <optgroup label="Itella SmartPOST">
            <option value="smartpost_ee" {if $shipping.bd_pickup_provider == 'smartpost_ee'}selected="selected"{/if}>Itella SmartPOST Estonia</option>
            <option value="smartpost_fi" {if $shipping.bd_pickup_provider == 'smartpost_fi'}selected="selected"{/if}>Itella SmartPOST Finland</option>
        </optgroup>
        <optgroup label="Omniva">
            <option value="omniva_ee" {if $shipping.bd_pickup_provider == 'omniva_ee'}selected="selected"{/if}>Omniva Estonia</option>
            <option value="omniva_lv" {if $shipping.bd_pickup_provider == 'omniva_lv'}selected="selected"{/if}>Omniva Latvia</option>
            <option value="omniva_lt" {if $shipping.bd_pickup_provider == 'omniva_lt'}selected="selected"{/if}>Omniva Lithuania</option>
        </optgroup>
        <optgroup label="DPD Pickup">
            <option value="dpd_ee" {if $shipping.bd_pickup_provider == 'dpd_ee'}selected="selected"{/if}>DPD Pickup Estonia</option>
            <option value="dpd_lv" {if $shipping.bd_pickup_provider == 'dpd_lv'}selected="selected"{/if}>DPD Pickup Latvia</option>
            <option value="dpd_lt" {if $shipping.bd_pickup_provider == 'dpd_lt'}selected="selected"{/if}>DPD Pickup Lithuania</option>
        </optgroup>
    </select>
  </div>
</div>