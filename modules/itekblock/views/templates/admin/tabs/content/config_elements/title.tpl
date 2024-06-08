<div class="form-group content_by_lang lang-{$language.id_lang|escape:'htmlall':'UTF-8'} {if $language.id_lang != $defaultFormLanguage}inactive{/if}"
     data-type="title" data-lang="{$language.id_lang|escape:'htmlall':'UTF-8'}">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label">
                {l s='Title' d='Modules.Itekblock.Admin'}*
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
        <div class="input-group col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-12">
                <textarea name="text-title-{$language.id_lang|escape:'htmlall':'UTF-8'}" max="100"
                class="rte autoload_rte textarea-title-{if isset($block) && isset($allblockByShop) && isset($block['id_itekblock'])}{$block['id_itekblock']}{/if}">
                    {if isset($block) && isset($allblockByShop) && isset($block['id_itekblock']) && isset($allblockByShop[{$language.id_lang}][$block['id_itekblock']]['title'])}{$allblockByShop[{$language.id_lang}][$block['id_itekblock']]['title']|escape:'htmlall':'UTF-8'}{/if}
                </textarea>
                <input type="hidden" name="title-{$language.id_lang|escape:'htmlall':'UTF-8'}" max="100"
                    class="form-control title-{if isset($block) && isset($allblockByShop) && isset($block['id_itekblock'])}{$block['id_itekblock']}{/if}" 
                       value="{if isset($block) && isset($allblockByShop) && isset($block['id_itekblock']) && isset($allblockByShop[{$language.id_lang}][$block['id_itekblock']]['title'])}{$allblockByShop[{$language.id_lang}][$block['id_itekblock']]['title']|escape:'htmlall':'UTF-8'}{/if}">
            </div>
        </div>
        <div class="col-xs-12 help-block">
            <span class="limit_text">0</span>/100 characters
        </div>
    </div>
    <div class="clearfix"></div>
</div>
