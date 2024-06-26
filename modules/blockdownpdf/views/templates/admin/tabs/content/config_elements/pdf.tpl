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

<div class="form-group">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3 first-block">
        <div class="text-right">
            <label class="control-label">
                {l s='PDF' d='Modules.Blockdownpdf.Admin'}
            </label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4 first-block">
        <div class="input-group upload_file_button">
            <label class="file_label" for="file{if isset($block)}{$block['id_blockdownpdf']}{/if}"
                data-label="{l s='upload pdf file' d='Modules.Blockdownpdf.Admin'}">{l s='upload pdf file' d='Modules.Blockdownpdf.Admin'}</label>
            <label class="input-group-btn">
                <span>
                    <i class="icon-file"></i><input id="file{if isset($block)}{$block['id_blockdownpdf']}{/if}"
                        class="slide_image pdf-file" data-preview="image-preview-lang" type="file" name="image-lang">
                </span>
            </label>
        </div>
        <div class="help-block">
            {l s='Choose PDF file only: .pdf' d='Modules.Blockdownpdf.Admin'}
        </div>
    </div>
    <div class="clearfix"></div>
</div>
