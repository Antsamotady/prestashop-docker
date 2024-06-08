{if isset($block)}
<div class="form-group">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label" for="ckb_btn">
                {l s='Button' d='Modules.Itekblock.Admin'}
            </label>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
        <div class="input-group col-xs-12 col-sm-12 col-md-7 col-lg-12 psrea-flex">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="type_btn_{$block['id_itekblock']}" value="1" id="type_btn_{$block['id_itekblock']}_on" class="chkb_input_btn ckb-id-{$block['id_itekblock']}" data-id="{$block['id_itekblock']}"
                    {if $block['is_btn']}checked="checked"{/if}
                />
                {strip}
                <label for="type_btn_{$block['id_itekblock']}_on">
                        {l s='Yes' d='Admin.Global'}
                </label>
                {/strip}

                <input type="radio" name="type_btn_{$block['id_itekblock']}" value="0" id="type_btn_{$block['id_itekblock']}_off" class="chkb_input_btn ckb-id-{$block['id_itekblock']}" data-id="{$block['id_itekblock']}"
                {if !$block['is_btn']}checked="checked"{/if}
                />
                {strip}
                <label for="type_btn_{$block['id_itekblock']}_off">
                        {l s='No' d='Admin.Global'}
                </label>
                {/strip}

                <a class="slide-button btn"></a>
            </span>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="form-group content_by_lang id-{$block['id_itekblock']} itkb_input_txt_btn{if (isset($block) && !$block['is_btn'] ) || !isset($block)} inactive{/if} lang-{$language.id_lang|escape:'htmlall':'UTF-8'} {if $language.id_lang != $defaultFormLanguage}inactive{/if}"
    data-type="txt_btn" data-lang="{$language.id_lang|escape:'htmlall':'UTF-8'}">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label" for="ckb_btn_{$block['id_itekblock']}">
                {l s='Text button' d='Modules.Itekblock.Admin'}
            </label>
        </div>
    </div>
    <div class="input-group col-xs-12 col-sm-12 col-md-7 col-lg-4">
        <input type="text" name="txt_btn-{$language.id_lang|escape:'htmlall':'UTF-8'}" max="100"
                class="form-control"
                value="{if isset($block)}{$allblockByShop[{$language.id_lang}][$block['id_itekblock']]['txt_btn']|escape:'htmlall':'UTF-8'}{/if}">
        <span class="limit_text">0</span>/100 characters
    </div>
</div>
{/if}
