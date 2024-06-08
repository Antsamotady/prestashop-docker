<div class="form-group psr-url {if (isset($block) && isset($block['type_link']) && $block['type_link'] != $LINK_TYPE_URL) || !isset($block)} inactive{/if}" data-type="url">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label">
                {l s='URL' d='Modules.Itekblock.Admin'}
            </label>
        </div>
    </div>

    {foreach from=$languages item=language}
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4 content_by_lang lang-{$language.id_lang|escape:'htmlall':'UTF-8'} {if $language.id_lang != $defaultFormLanguage}inactive{/if}"
             data-type="url" data-lang="{$language.id_lang|escape:'htmlall':'UTF-8'}">
            <div class="input-group col-xs-12 col-sm-12 col-md-7 col-lg-12 psrea-flex">
                <div class="input-group-append">
                    <span class="input-group-text picto-url"><i class="material-icons">link</i></span>
                </div>
                <input class="block_url form-control" type="text" name="URL"
                       value="{if isset($block) && isset($allblockByShop) && isset($block['id_itekblock']) && isset($allblockByShop[{$language.id_lang}][$block['id_itekblock']]['url'])}{$allblockByShop[{$language.id_lang}][$block['id_itekblock']]['url']}{/if}">
            </div>
        </div>
    {/foreach}
    <div class="clearfix"></div>
</div>