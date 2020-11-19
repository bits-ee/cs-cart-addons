{include file="common/subheader.tpl" title=__("bd_everypay.api_settings") target="#main"}
<p>{__("bd_everypay.api_settings_notice")}</p>
<hr>
<div id="main" class="in collapse">
    <div class="control-group">
        <label class="control-label" for="account_name">{__('bd_everypay.account_name')}:</label>
        <div class="controls">
            <input type="text" name="payment_data[processor_params][account_name]" id="account_name" value="{$processor_params.account_name}" placeholder="EUR3D1" class="input-text" style="width: 500px;" />
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="api_username">{__('bd_everypay.api_username')}:</label>
        <div class="controls">
            <input type="text" name="payment_data[processor_params][api_username]" id="api_username" value="{$processor_params.api_username}" class="input-text" style="width: 500px;" />
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="api_password">{__('bd_everypay.api_password')}:</label>
        <div class="controls">
            <input type="text" name="payment_data[processor_params][api_password]" id="api_password" value="{$processor_params.api_password}" class="input-text" style="width: 500px;" />
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="env">{__('test_live_mode')}:</label>
        <div class="controls">
            <select name="payment_data[processor_params][env]" id="env">
              <option value="live" {if $processor_params.env == "live"}selected="selected"{/if}>{__('live')}</option>
              <option value="test" {if $processor_params.env == "test"}selected="selected"{/if}>{__('test')}</option>
            </select>
        </div>
    </div>
</div>

{include file="common/subheader.tpl" title=__("bd_everypay.state_mapping") target="#status_map"}
<p>{__("bd_everypay.state_mapping_notice")}</p>
<hr>
<div id="status_map" class="in collapse">
    <fieldset>
        <div class="control-group">
            <strong class="control-label">{__('bd_everypay.state')}</strong>
            <div class="controls">
                <strong style="float: left; padding-top: 5px;">{__('order_status')}</strong>
            </div>
        </div>
        {assign var="statuses" value=$smarty.const.STATUSES_ORDER|fn_get_simple_statuses}
        
        <div class="control-group">
            <label class="control-label" for="status_authorised">{__("bd_everypay.authorised")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][authorised]" id="status_authorised">
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.authorised) && $processor_params.status.authorised == $k) || (!isset($processor_params.status.authorised) && $k == 'O')}selected="selected"{/if}>{$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="status_settled">{__("bd_everypay.settled")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][settled]" id="status_settled">
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.settled) && $processor_params.status.settled == $k) || (!isset($processor_params.status.settled) && $k == 'P')}selected="selected"{/if}>{$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="status_abandoned">{__("bd_everypay.abandoned")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][abandoned]" id="status_abandoned">
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.abandoned) && $processor_params.status.abandoned == $k) || (!isset($processor_params.status.abandoned) && $k == 'I')}selected="selected"{/if}>{$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="status_voided">{__("bd_everypay.voided")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][voided]" id="status_voided">
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.voided) && $processor_params.status.voided == $k) || (!isset($processor_params.status.voided) && $k == 'I')}selected="selected"{/if}>{$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="status_failed">{__("bd_everypay.failed")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][failed]" id="status_failed">
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.failed) && $processor_params.status.failed == $k) || (!isset($processor_params.status.failed) && $k == 'F')}selected="selected"{/if}>{$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>

    </fieldset>
</div>