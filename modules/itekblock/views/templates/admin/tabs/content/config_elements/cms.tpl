<div class="form-group psr-cms {if (isset($block) && $block['type_link'] != $LINK_TYPE_CMS) || !isset($block)} inactive{/if}">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label">
                {l s='CMS Page' d='Modules.Itekblock.Admin'}
            </label>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
        <div class="input-group col-xs-12 col-sm-12 col-md-7 col-lg-12 psrea-flex">
            <select class="custom-select" name="ID_CMS_{if isset($block)}{$block['id_itekblock']}{/if}">
                {foreach from=$allCms item=cms}
                    <option value="{$cms['id_cms']}" {if isset($block) && $block['id_cms'] == $cms['id_cms']} selected="selected"{/if}>{$cms['meta_title']|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
</div>