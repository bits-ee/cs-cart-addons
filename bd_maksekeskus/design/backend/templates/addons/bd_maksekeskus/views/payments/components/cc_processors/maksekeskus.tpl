{include file="common/subheader.tpl" title=__("bd_maksekeskus.api_settings") target="#main"}
<p>{__("bd_maksekeskus.api_settings_notice")}</p>
<hr>
<div id="main" class="in collapse">
    <div class="control-group">
        <label class="control-label" for="shop_id">{__('bd_maksekeskus.shop_id')}:</label>
        <div class="controls">
            <input type="text" name="payment_data[processor_params][shop_id]" id="shop_id" value="{$processor_params.shop_id}" class="input-text" style="width: 500px;" />
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="api_secret">{__('bd_maksekeskus.api_secret')}:</label>
        <div class="controls">
            <input type="text" name="payment_data[processor_params][api_secret]" id="api_secret" value="{$processor_params.api_secret}" class="input-text" style="width: 500px;" />
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

    <div class="control-group">
        <label class="control-label" for="shop_id">{__('currency')}:</label>
        <div class="controls">EUR</div>
    </div>
</div>

{include file="common/subheader.tpl" title=__("bd_maksekeskus.state_mapping") target="#status_map"}
<p>{__("bd_maksekeskus.state_mapping_notice")}</p>
<hr>
<div id="status_map" class="in collapse">
    <fieldset>
        <div class="control-group">
            <strong class="control-label">{__('bd_maksekeskus.state')}</strong>
            <div class="controls">
                <strong style="float: left; padding-top: 5px;">{__('order_status')} / {__("action")}</strong>
            </div>
        </div>
        {assign var="statuses" value=$smarty.const.STATUSES_ORDER|fn_get_simple_statuses}
        <div class="control-group">
            <label class="control-label" for="status_completed">{__("completed")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][completed]" id="status_completed">
                    <option value="$smarty.const.STATUS_INCOMPLETED_ORDER" {if (isset($processor_params.status.completed) && $processor_params.status.completed == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("action")}: {__("proceed_to_checkout")}</option>
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.completed) && $processor_params.status.completed == $k) || (!isset($processor_params.status.completed) && $k == 'P')}selected="selected"{/if}>{__("status")}: {$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="status_pending">{__("pending")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][pending]" id="status_pending">
                    <option value="$smarty.const.STATUS_INCOMPLETED_ORDER" {if (isset($processor_params.status.pending) && $processor_params.status.pending == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("action")}: {__("proceed_to_checkout")}</option>
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.pending) && $processor_params.status.pending == $k) || (!isset($processor_params.status.pending) && $k == 'O')}selected="selected"{/if}>{__("status")}: {$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="status_cancelled">{__("cancelled")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][cancelled]" id="status_cancelled">
                    <option value="$smarty.const.STATUS_INCOMPLETED_ORDER" {if (isset($processor_params.status.cancelled) && $processor_params.status.cancelled == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("action")}: {__("proceed_to_checkout")}</option>
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.cancelled) && $processor_params.status.cancelled == $k) || (!isset($processor_params.status.cancelled) && $k == 'I')}selected="selected"{/if}>{__("status")}: {$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="status_created">{__("created")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][created]" id="status_created">
                    <option value="$smarty.const.STATUS_INCOMPLETED_ORDER" {if (isset($processor_params.status.created) && $processor_params.status.created == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("action")}: {__("proceed_to_checkout")}</option>
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.created) && $processor_params.status.created == $k) || (!isset($processor_params.status.created) && $k == 'O')}selected="selected"{/if}>{__("status")}: {$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="status_expired">{__("expired")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][expired]" id="status_expired">
                    <option value="$smarty.const.STATUS_INCOMPLETED_ORDER" {if (isset($processor_params.status.expired) && $processor_params.status.expired == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("action")}: {__("proceed_to_checkout")}</option>
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.expired) && $processor_params.status.expired == $k) || (!isset($processor_params.status.expired) && $k == 'F')}selected="selected"{/if}>{__("status")}: {$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="status_approved">{__("approved")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][approved]" id="status_approved">
                    <option value="$smarty.const.STATUS_INCOMPLETED_ORDER" {if (isset($processor_params.status.approved) && $processor_params.status.approved == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("action")}: {__("proceed_to_checkout")}</option>
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.approved) && $processor_params.status.approved == $k) || (!isset($processor_params.status.approved) && $k == 'P')}selected="selected"{/if}>{__("status")}: {$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="status_refunded">{__("refunded")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][refunded]" id="status_refunded">
                    <option value="$smarty.const.STATUS_INCOMPLETED_ORDER" {if (isset($processor_params.status.refunded) && $processor_params.status.refunded == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("action")}: {__("proceed_to_checkout")}</option>
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.refunded) && $processor_params.status.refunded == $k) || (!isset($processor_params.status.refunded) && $k == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("status")}: {$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="status_part_refunded">{__("part_refunded")}:</label>
            <div class="controls">
                <select name="payment_data[processor_params][status][part_refunded]" id="status_part_refunded">
                    <option value="$smarty.const.STATUS_INCOMPLETED_ORDER" {if (isset($processor_params.status.part_refunded) && $processor_params.status.part_refunded == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("action")}: {__("proceed_to_checkout")}</option>
                    {foreach from=$statuses item="s" key="k"}
                    <option value="{$k}" {if (isset($processor_params.status.part_refunded) && $processor_params.status.part_refunded == $k) || (!isset($processor_params.status.part_refunded) && $k == $smarty.const.STATUS_INCOMPLETED_ORDER)}selected="selected"{/if}>{__("status")}: {$s}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </fieldset>
</div>