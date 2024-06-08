<div class="form-group">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3 first-block">
        <div class="text-right">
            <label class="control-label">
                {l s='Image' d='Modules.Itekblock.Admin'}
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4 first-block">
        <div class="psr_picto_showing input-group col-lg-4">
            <img class="psr-picto picto_by_module svg"
                 src="{if isset($block) && $block['icon']}{$block['icon']}{elseif isset($block) && $block['custom_icon']}{$block['custom_icon']}{/if}"/>
            <div>
                <i class="material-icons landscape">landscape</i>
            </div>
            <div class="svg_chosed_here">
                <img class="image-preview-lang img-thumbnail hide" src="" alt="" width="24px" height="24px"/>
            </div>
            <span class="modify_icon" data-id="{if isset($block)}{$block['id_itekblock']}{/if}">{l s='Modify icon' d='Modules.Itekblock.Admin'}</span>
        </div>
        <div class="input-group upload_file_button">
            <label class="file_label" for="file{if isset($block)}{$block['id_itekblock']}{/if}" data-label="{l s='or upload file' d='Modules.Itekblock.Admin'}">{l s='or upload file' d='Modules.Itekblock.Admin'}</label>
            <label class="input-group-btn">
                <span>
                    <i class="icon-file"></i><input id="file{if isset($block)}{$block['id_itekblock']}{/if}" class="slide_image" data-preview="image-preview-lang" type="file" name="image-lang">
                </span>
            </label>
        </div>
        <div class="help-block">
            {l s='Choose SVG for better customization. Other allowed formats are: .gif, .jpg, .png, and maximal allow file upload 1MB' d='Modules.Itekblock.Admin'}
        </div>
    </div>
    <div class="clearfix"></div>
</div>
