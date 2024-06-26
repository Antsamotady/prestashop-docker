{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *}

<div class="panel panel-default panel-blockdownpdf col-lg-10 col-lg-offset-1 col-xs-12 col-xs-offset-0">
    <div class="panel-heading">
        {l s='Customize Module Design' d='Modules.Blockdownpdf.Admin'}
    </div>

    <div class="panel-body">
        <div class="clearfix">

            <div class="form-group">
                <div class="col-xs-5 col-md-5 col-lg-3">
                    <div class="text-right">
                        <label class="control-label">
                            {l s='Icon color' d='Modules.Blockdownpdf.Admin'}
                        </label>
                    </div>
                </div>
                <div class="col-xs-7 col-md-7 col-lg-2">
                    <input type="color" id="color_1" name="BLD_ICON_COLOR" class="bld_icon_color"
                        value="{if isset($bld_icon_color)}{$bld_icon_color|escape:'htmlall':'UTF-8'}{/if}" />
                </div>

                <div class="clearfix"></div>
            </div>

            <div class="form-group">
                <div class="col-xs-5 col-md-5 col-lg-3">
                    <div class="text-right">
                        <label class="control-label">
                            {l s='Text color' d='Modules.Blockdownpdf.Admin'}
                        </label>
                    </div>
                </div>
                <div class="col-xs-7 col-md-7 col-lg-2">
                    <input type="color" id="color_2" name="BLD_TEXT_COLOR" class="bld_text_color"
                        value="{if isset($bld_text_color)}{$bld_text_color|escape:'htmlall':'UTF-8'}{/if}" />
                </div>
            </div>

        </div>
    </div>

    <div class="panel-footer">
        <div class="col-xs-12 text-right">
            <button name="saveConfiguration" id="saveConfiguration" type="submit"
                class="btn btn-primary">{l s='Save' d='Modules.Blockdownpdf.Admin'}</button>
        </div>
    </div>
</div>