<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author PrestaShop SA <contact@prestashop.com>
 * @copyright  2007-2019 PrestaShop SA
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
class AdminITKBlockListingController extends ModuleAdminController
{
    /**
     * @param $content
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
        $itekblockId = (int) Tools::getValue('idpsr');
        $newStatus = ((int) Tools::getValue('status') == 1) ? 0 : 1;

        $dataToUpdate = [
            'status' => $newStatus,
            'date_upd' => $now->format('Y-m-d H:i:s'),
        ];
        $whereCondition = 'id_itekblock = ' . $itekblockId;

        $updateResult = Db::getInstance()->update('itekblock', $dataToUpdate, $whereCondition);

        // Response
        $this->ajaxRenderJson($updateResult ? 'success' : 'error');
    }

    /**
     * Add a log entry, supporting both PrestaShop and legacy logging systems.
     *
     * @param string $message      The log message.
     * @param int    $severity     Log severity level (1 for errors, 2 for warnings, 3 for informational).
     * @param mixed  $errorCode    Optional error code associated with the log entry.
     * @param string $objectType   Optional type of the object associated with the log entry.
     * @param int    $objectId     Optional ID of the object associated with the log entry.
     * @param bool   $allowDuplicate Whether to allow duplicate log entries.
     *
     * @return void
     */
    public function addLog($message, $severity = 1, $errorCode = null, $objectType = null, $objectId = null, $allowDuplicate = true)
    {
        // Check if PrestaShopLogger class exists (PrestaShop 1.7+)
        if (class_exists('PrestaShopLogger')) {
            PrestaShopLogger::addLog($message, $severity, $errorCode, $objectType, $objectId, $allowDuplicate);
        }
        // Check if Logger class exists (PrestaShop 1.6 and below)
        else if (class_exists('Logger')) {
            Logger::addLog($message, $severity, $errorCode, $objectType, $objectId, $allowDuplicate);
        }
        // If neither class exists, fallback to error_log
        else {
            error_log($message . "(" . $errorCode . ")");
        }
    }

    /**
     * Check if the logged-in employee has admin permissions.
     *
     * @param Employee $employee
     * @return bool
     */
    private function justifyAsAdmin($employee)
    {
        $isItekEmployee = false;

        if ($employee) {
            $employeeProfile = $employee->id_profile;
            if ($employeeProfile == 1) {
                $isItekEmployee = true;
            }
        }

        return $isItekEmployee;
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
        $blockPSR = Db::getInstance()->getRow('SELECT * FROM ' . _DB_PREFIX_ . 'itekblock WHERE `id_itekblock` = ' . (int) $idPSR);
        $employee = Context::getContext()->employee;

        if (!$this->justifyAsAdmin($employee)) {
            $this->ajaxRenderJson('You do not have permission to delete blocks.');
            return;
        }

        if (!empty($blockPSR)) {
            $result = true;
            // Remove Custom icon
            if (!empty($blockPSR['custom_icon'])) {
                $filePath = str_replace(__PS_BASE_URI__, _PS_ROOT_DIR_ . DIRECTORY_SEPARATOR, $blockPSR['custom_icon']);
                if (file_exists($filePath)) {
                    $result = unlink($filePath);
                }
            }
            // Remove Block Translations
            if ($result) {
                $result = Db::getInstance()->delete('itekblock_lang', 'id_itekblock = ' . (int) $idPSR);
            }
            // Remove Block
            if ($result) {
                $result = Db::getInstance()->delete('itekblock', 'id_itekblock = ' . (int) $idPSR);
            }

            // Add log
            $this->addLog('Block ' . $idPSR . ' deleted', 1);
        }

        // Response
        $this->ajaxRenderJson($result ? 'Success' : 'error');
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
        $psr_languages = (array) json_decode(Tools::getValue('lang_values'));

        $blockPsr = new ItekblockObject($id_block);
        $employee = Context::getContext()->employee;

        if (!$id_block) {
            // Last position
            $blockPsr->position = Db::getInstance()->getValue('SELECT MAX(position) AS max FROM ' . _DB_PREFIX_ . 'itekblock');
            $blockPsr->position = $blockPsr->position ? $blockPsr->position + 1 : 1;
            $blockPsr->status = false;
        }

        // Check if it's a new block and if the employee has admin permissions
        if (!$id_block && !$this->justifyAsAdmin($employee)) {
            $this->ajaxRenderJson('You do not have permission to add blocks.');
            return;
        }
        
        // If has type link or id cms
        if($type_link ||  $id_cms){
            $blockPsr->handleBlockValues($psr_languages, $type_link, $id_cms);
        }
        
        $blockPsr->icon = $picto;
        if (empty($picto)) {
            $blockPsr->custom_icon = '';
        }
        $blockPsr->hook = (int)Tools::getValue('hook');
        $blockPsr->is_btn = (int)Tools::getValue('is_type_btn');
        $blockPsr->date_add = date('Y-m-d H:i:s');
        $blockPsr->date_update = date('Y-m-d H:i:s');

        if (isset($_FILES) && !empty($_FILES)) {
            $customImage = $_FILES['file'];
            $fileTmpName = $customImage['tmp_name'];
            $filename = $customImage['name'];

            // validateUpload return false if no error (false -> OK)
            $authExtensions = ['gif', 'jpg', 'jpeg', 'jpe', 'png', 'svg'];
            $authMimeType = ['image/gif', 'image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/svg'];
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
                $mimeType = $this->getMimeType($customImage['tmp_name']);
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
                // add log once update and add block name information in the log
                $this->addLog('Update itekBlock', 1, null, 'Block', $blockPsr->id);
                
            } else {          
                if (!$this->justifyAsAdmin($employee)) {
                    $this->ajaxRenderJson('You do not have permission to delete blocks.');
                    return;
                }

                $blockPsr->add();
                $this->addLog('Add itekBlock', 1, null, 'Block', $blockPsr->id);
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
            foreach ($blocks as $key => $id_block) {
                // Set the position of the Reassurance block
                $position = $key + 1;

                $dataToUpdate = ['position' => (int) $position];
                $whereCondition = 'id_itekblock = ' . (int) $id_block;
                $updateResult = (bool) Db::getInstance()->update('itekblock', $dataToUpdate, $whereCondition);

                // If the update can't be done, we return false
                if (!$updateResult) {
                    break;
                }
            }
            $result = $updateResult ? true : false;
            $this->addLog('Update position of itekBlocks', 1, null, 'Block', null);
        }

        // Response
        $this->ajaxRenderJson($result ? 'Success update position' : 'Error update position');
    }

    /**
     * @return string|bool
     */
    private function getMimeType(string $filename)
    {
        $mimeType = false;
        // Try with GD
        if (function_exists('getimagesize')) {
            $imageInfo = @getimagesize($filename);
            if ($imageInfo) {
                $mimeType = $imageInfo['mime'];
            }
        }
        // Try with FileInfo
        if (!$mimeType && function_exists('finfo_open')) {
            $const = defined('FILEINFO_MIME_TYPE') ? FILEINFO_MIME_TYPE : FILEINFO_MIME;
            $finfo = finfo_open($const);
            $mimeType = finfo_file($finfo, $filename);
            finfo_close($finfo);
        }
        // Try with Mime
        if (!$mimeType && function_exists('mime_content_type')) {
            $mimeType = mime_content_type($filename);
        }
        // Try with exec command and file binary
        if (!$mimeType && function_exists('exec')) {
            $mimeType = trim(exec('file -b --mime-type ' . escapeshellarg($filename)));
            if (!$mimeType) {
                $mimeType = trim(exec('file --mime ' . escapeshellarg($filename)));
            }
            if (!$mimeType) {
                $mimeType = trim(exec('file -bi ' . escapeshellarg($filename)));
            }
        }

        return $mimeType;
    }
}
