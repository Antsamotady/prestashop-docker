<div class="form-group">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3">
        <div class="text-right">
            <label class="control-label">
                {l s='Redirection' d='Modules.Itekblock.Admin'}
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
        <div class="input-group col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-12 customradiodesign">
                <div>
                    <input id="PSR_REDIRECTION_NONE_{if isset($block)}{$block['id_itekblock']}{/if}" type="radio"
                           name="PSR_REDIRECTION_{if isset($block)}{$block['id_itekblock']}{/if}"
                           value="{$LINK_TYPE_NONE}" {if (isset($block) && isset($block['type_link']) && $block['type_link'] == $LINK_TYPE_NONE) || !isset($block)} checked="checked"{/if}>

                    <label for="PSR_REDIRECTION_NONE_{if isset($block)}{$block['id_itekblock']}{/if}"
                         class="input-redirection"><span><span></span></span>{l s='None' d='Modules.Itekblock.Admin'}</label>
                </div>
                <div>
                    <input id="PSR_REDIRECTION_CMS_{if isset($block)}{$block['id_itekblock']}{/if}" type="radio"
                           name="PSR_REDIRECTION_{if isset($block)}{$block['id_itekblock']}{/if}"
                           value="{$LINK_TYPE_CMS}" {if isset($block) && isset($block['type_link']) && $block['type_link'] == $LINK_TYPE_CMS} checked="checked"{/if}>

                    <label for="PSR_REDIRECTION_CMS_{if isset($block)}{$block['id_itekblock']}{/if}"
                           class="input-redirection"><span><span></span></span>{l s='CMS page' d='Modules.Itekblock.Admin'}</label>
                </div>
                <div>
                  <input id="PSR_REDIRECTION_URL_{if isset($block)}{$block['id_itekblock']}{/if}" type="radio"
                         name="PSR_REDIRECTION_{if isset($block)}{$block['id_itekblock']}{/if}"
                         value="{$LINK_TYPE_URL}" {if isset($block) && isset($block['type_link']) && $block['type_link'] == $LINK_TYPE_URL} checked="checked"{/if}>

                  <label for="PSR_REDIRECTION_URL_{if isset($block)}{$block['id_itekblock']}{/if}"
                         class="input-redirection"><span><span></span></span>{l s='URL' d='Modules.Itekblock.Admin'}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
