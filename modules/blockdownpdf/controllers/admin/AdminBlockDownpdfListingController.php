<?php
/**
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
 */
class AdminBlockDownpdfListingController extends ModuleAdminController
{
    /** @var blockdownpdf */
    public $module;

    /**
     * @param string $content
     *
     * @throws PrestaShopException
     */
    protected function ajaxRenderJson($content)
    {
        header('Content-Type: application/json');
        $this->ajaxRender(json_encode($content));
    }

    /**
     * Enable or disable a block
     *
     * @throws PrestaShopException
     */
    public function displayAjaxChangeBlockStatus()
    {
        $now = new DateTime();
        $blockdownpdfId = (int) Tools::getValue('idpsr');
        $newStatus = ((int) Tools::getValue('status') == 1) ? 0 : 1;

        $dataToUpdate = [
            'status' => $newStatus,
            'date_upd' => $now->format('Y-m-d H:i:s'),
        ];
        $whereCondition = 'id_blockdownpdf = ' . $blockdownpdfId;

        $updateResult = Db::getInstance()->update('blockdownpdf', $dataToUpdate, $whereCondition);

        // Response
        $this->ajaxRenderJson($updateResult ? 'success' : 'error');
    }

    /**
     * Delete a block
     *
     * @throws PrestaShopException
     */
    public function displayAjaxDeleteBlock()
    {
        $result = false;
        $idPSR = (int) Tools::getValue('idBlock');
        $blockPSR = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'blockdownpdf WHERE `id_blockdownpdf` = ' . (int) $idPSR);
        if (!empty($blockPSR)) {
            $result = true;
            // Remove Custom icon
            if (!empty($blockPSR['custom_icon'])) {
                $filePath = _PS_ROOT_DIR_ . $this->module->img_path_perso . '/' . basename($blockPSR['custom_icon']);
                if (file_exists($filePath)) {
                    $result = unlink($filePath);
                }
            }
            // Remove Block Translations
            if ($result) {
                $result = Db::getInstance()->delete('blockdownpdf_lang', 'id_blockdownpdf = ' . (int) $idPSR);
            }
            // Remove Block
            if ($result) {
                $result = Db::getInstance()->delete('blockdownpdf', 'id_blockdownpdf = ' . (int) $idPSR);
            }
        }

        // Response
        $this->ajaxRenderJson($result ? 'success' : 'error');
    }

    /**
     * Update how the blocks are displayed in the front-office
     *
     * @throws PrestaShopException
     */
    public function displayAjaxSavePositionByHook()
    {
        $hook = Tools::getValue('hook');
        $value = Tools::getValue('value');
        $result = false;

        if ($this->isAuthorizedHookConfigurationKey($hook) && $this->isAuthorizedPositionValue($value)) {
            $result = Configuration::updateValue($hook, $value);
        }

        // Response
        $this->ajaxRenderJson($result ? 'success' : 'error');
    }

    /**
     * Update color settings to be used in front-office display
     *
     * @throws PrestaShopException
     */
    public function displayAjaxSaveColor()
    {
        $color1 = Tools::getValue('color1');
        $color2 = Tools::getValue('color2');
        $result = false;

        if (!empty($color1) && !empty($color2)) {
            $result = Configuration::updateValue('BLD_ICON_COLOR', $color1)
                && Configuration::updateValue('BLD_TEXT_COLOR', $color2);
        }

        // Response
        $this->ajaxRenderJson($result ? 'success' : 'error');
    }

    /**
     * Modify the settings of one block from BO "configure" page
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function displayAjaxSaveBlockContent()
    {
        $errors = [];

        $picto = Tools::getValue('picto');
        $id_block = empty(Tools::getValue('id_block')) ? null : Tools::getValue('id_block');
        $type_link = (int) Tools::getValue('typelink');
        $id_cms = Tools::getValue('id_cms');
        $bld_languages = (array) json_decode(Tools::getValue('lang_values'));
        $authExtensions = ['gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg'];
        $authMimeType = ['image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/svg', 'image/svg+xml'];

        if (!empty($picto) && !in_array(pathinfo($picto, PATHINFO_EXTENSION), $authExtensions)) {
            $errors[] = Context::getContext()->getTranslator()->trans('Image format not recognized, allowed formats are: .gif, .jpg, .png', [], 'Admin.Notifications.Error');

            return $this->ajaxRenderJson(empty($errors) ? 'success' : 'error');
        }

        $blockPsr = new BlockdownpdfActivity($id_block);
        if (!$id_block) {
            // Last position
            $blockPsr->position = (int) Db::getInstance()->getValue('SELECT MAX(position) AS max FROM ' . _DB_PREFIX_ . 'blockdownpdf');
            $blockPsr->position = $blockPsr->position ? $blockPsr->position + 1 : 1;
            $blockPsr->status = false;
        }
        $blockPsr->handleBlockValues($bld_languages, $type_link, $id_cms);
        if (strpos($picto, $this->module->img_path_perso) !== false) {
            $blockPsr->icon = '';
            $blockPsr->custom_icon = $picto;
        } else {
            $blockPsr->icon = $picto;
            $blockPsr->custom_icon = '';
        }
        $blockPsr->date_add = date('Y-m-d H:i:s');
        $blockPsr->date_upd = date('Y-m-d H:i:s');

        if (!empty($_FILES)) {
            $customImage = $_FILES['file'];
            $fileTmpName = $customImage['tmp_name'];
            $filename = $customImage['name'];

            // validateUpload return false if no error (false -> OK)
            if (version_compare(_PS_VERSION_, '1.7.7.0', '>=')) {
                // PrestaShop 1.7.7.0+
                $validUpload = ImageManager::validateUpload(
                    $customImage,
                    0,
                    $authExtensions,
                    $authMimeType
                );
            } else {
                // PrestaShop < 1.7.7
                $validUpload = false;
                $mimeType = BlockdownpdfActivity::getMimeType($customImage['tmp_name']);
                if ($mimeType && (
                    !in_array($mimeType, $authMimeType)
                    || !ImageManager::isCorrectImageFileExt($customImage['name'], $authExtensions)
                    || preg_match('/\%00/', $customImage['name'])
                )) {
                    $validUpload = Context::getContext()->getTranslator()->trans('Image format not recognized, allowed formats are: .gif, .jpg, .png', [], 'Admin.Notifications.Error');
                }
                if ($customImage['error']) {
                    $validUpload = Context::getContext()->getTranslator()->trans('Error while uploading image; please change your server\'s settings. (Error code: %s)', [$customImage['error']], 'Admin.Notifications.Error');
                }
            }
            if (is_bool($validUpload) && $validUpload === false) {
                move_uploaded_file($fileTmpName, $this->module->folder_file_upload . $filename);
                $blockPsr->custom_icon = $this->module->img_path_perso . '/' . $filename;
                $blockPsr->icon = '';
            } else {
                $errors[] = $validUpload;
            }
        }
        if (empty($errors)) {
            if ($id_block) {
                $blockPsr->update();
            } else {
                $blockPsr->add();
            }
        }

        // Response
        $this->ajaxRenderJson(empty($errors) ? 'success' : 'error');
    }

    /**
     * Reorder the blocks positions
     *
     * @throws PrestaShopException
     */
    public function displayAjaxUpdatePosition()
    {
        $blocks = Tools::getValue('blocks');
        $result = false;

        if (!empty($blocks) && is_array($blocks)) {
            $updateResult = true;
            foreach ($blocks as $key => $id_block) {
                // Set the position of the Blockdownpdf block
                $position = $key + 1;

                $dataToUpdate = ['position' => (int) $position];
                $whereCondition = 'id_blockdownpdf = ' . (int) $id_block;
                $updateResult = (bool) Db::getInstance()->update('blockdownpdf', $dataToUpdate, $whereCondition);

                // If the update can't be done, we return false
                if (!$updateResult) {
                    break;
                }
            }
            $result = $updateResult ? true : false;
        }

        // Response
        $this->ajaxRenderJson($result ? 'success' : 'error');
    }

    /**
     * @param string $hook
     *
     * @return bool
     */
    private function isAuthorizedHookConfigurationKey($hook)
    {
        return
            !empty($hook) &&
            in_array($hook, [
                blockdownpdf::BLD_HOOK_HEADER,
                blockdownpdf::BLD_HOOK_FOOTER,
                blockdownpdf::BLD_HOOK_PRODUCT,
                blockdownpdf::BLD_HOOK_CHECKOUT,
            ], true)
        ;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    private function isAuthorizedPositionValue($value)
    {
        return in_array((int) $value, [
            blockdownpdf::POSITION_NONE,
            blockdownpdf::POSITION_BELOW_HEADER,
            blockdownpdf::POSITION_ABOVE_HEADER,
        ], true);
    }
}
