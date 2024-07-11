<div class="form-group psr-cms">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label">
                {l s='CMS Page' d='Modules.Blockdownpdf.Admin'}
            </label>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
        <div class="input-group col-xs-12 col-sm-12 col-md-7 col-lg-12 psrea-flex">
            <select class="custom-select" name="ID_CMS">
                {foreach from=$allCms item=cms}
                    <option value="{$cms['id_cms']}">{$cms['meta_title']|escape:'htmlall':'UTF-8'}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
