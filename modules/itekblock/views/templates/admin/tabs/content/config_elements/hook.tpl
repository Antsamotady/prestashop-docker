<div class="form-group">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label">
                {l s='Hook' d='Modules.Itekblock.Admin'}*
            </label>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
        <div class="input-group col-xs-12 col-sm-12 col-md-7 col-lg-12 psrea-flex">
            <select class="custom-select" name="itkb_hook_{if isset($block)}{$block['id_itekblock']}{/if}">
                {foreach from=$allshook key=index item=hook}
                    <option value="{$index}" {if isset($block) && $block['hook'] == $index} selected="selected"{/if}>{$hook|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
            </select>
        </div>
    </div>
    

    <div class="clearfix"></div>
</div>