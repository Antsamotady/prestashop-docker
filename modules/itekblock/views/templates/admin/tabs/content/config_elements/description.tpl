<div class="form-group content_by_lang lang-{$language.id_lang|escape:'htmlall':'UTF-8'} {if $language.id_lang != $defaultFormLanguage}inactive{/if}"
    data-type="description" data-lang="{$language.id_lang|escape:'htmlall':'UTF-8'}">
   <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
       <div class="text-right">
           <label class="control-label">
               {l s='Description (optional)' d='Modules.Itekblock.Admin'}
           </label>
       </div>
   </div>
   <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
       <div class="input-group col-lg-12">
           <textarea name="description-{$language.id_lang}" class="rte autoload_rte">
           {if isset($block)}{$allblockByShop[{$language.id_lang}][$block['id_itekblock']]['description']|escape:'html':'UTF-8'}{/if}
           </textarea>
       </div>
   </div>
   <div class="clearfix"></div>
</div>
