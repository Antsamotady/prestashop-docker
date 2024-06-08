
{if $languages|count > 1}
<div class="form-group">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label">
                {l s='Languages' d='Modules.Itekblock.Admin'}
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
        <div class="input-group col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-12">
                <select class="custom-select" name="psr-language">
                    {foreach from=$languages item=lang}
                        <option {if $lang.id_lang == $defaultFormLanguage}selected="selected"{/if}
                                value="{$lang.id_lang}">{$lang.name|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
{/if}
