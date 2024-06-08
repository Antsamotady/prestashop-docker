<?php
/**
 * 2007-2021 PrestaShop
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
 *  @author    ©Itekcom
 *  @copyright 2005 - 2024
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class itekblock extends Module implements WidgetInterface
{
    const ITKB_HOOK = [
        '0' => 'Reassurance',
        '1' => 'Header',
        '2' => 'Right',
        '3' => 'Middle',
        '4' => 'Newsletter',
        '5' => 'Seo',
        '6' => 'Pub',
        '7' => 'Ars'
    ];
    
    private $templateFile;

    public function __construct()
    {
        // Settings
        $this->name = 'itekblock';
        $this->tab  = 'seo';
        $this->version = '1.0.0';
        $this->author  = 'Itekcom';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();
        if ($this->context->link == null) {
            $protocolPrefix = Tools::getCurrentUrlProtocolPrefix();
            $this->context->link = new Link($protocolPrefix, $protocolPrefix);
        }

        $this->displayName = $this->trans('Itek block', [], 'Modules.Itekblock.Admin');
        $this->description = $this->trans("Personnalisez l'apparence de votre contenu en choisissant l'emplacement idéal à l'aide du hook intégré", [], 'Modules.Itekblock.Admin');

        // Settings paths
        if (!$this->_path) {
            $this->_path = __PS_BASE_URI__ . 'modules/' . $this->name . '/';
        }
        $this->img_path = $this->_path . 'views/img/';
        $this->old_path_img = $this->_path . 'img/';
        $this->img_path_perso = $this->img_path . 'img_perso';
        $this->lib_path     = $this->_path . 'views/lib/';
        $this->docs_path    = $this->_path . 'docs/';
        $this->logo_path    = $this->_path . 'logo.png';
        $this->module_path  = $this->_path;
        $this->folder_file_upload = _PS_MODULE_DIR_ . $this->name . '/views/img/img_perso/';

        // Confirm uninstall
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall this module?', [], 'Modules.Itekblock.Admin');
        $this->ps_url = $this->context->link->getBaseLink();
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->templateFile = 'module:itekblock/views/templates/hook/itekblock.tpl';
    }

    /**
     * install pre-config
     *
     * @return bool
     */
    public function install()
    {
        ini_set('post_max_size', '20M');
        ini_set('upload_max_filesize', '20M');
        // SQL
        $sqlQueries = [];

        $sqlQueries[] = ' CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'itekblock` (
            `id_itekblock` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `icon` varchar(255) NULL,
            `custom_icon` varchar(255) NULL,
            `status` int(10) unsigned NOT NULL,
            `position` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            `type_link` int(10) unsigned NULL,
            `id_cms` int(10) unsigned NULL,
            `hook` int(10) NOT NULL DEFAULT 0,
            `is_btn` tinyint(1) NOT NULL DEFAULT 0,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NULL,
            PRIMARY KEY (`id_itekblock`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        $sqlQueries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'itekblock_lang` (
            `id_itekblock` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            `title` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            `link` varchar(255) NOT NULL,
            `txt_btn` varchar(255) NOT NULL,
            PRIMARY KEY (`id_itekblock`,`id_shop`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        $sqlQueries[] = 'INSERT INTO ' . _DB_PREFIX_ . 'itekblock (icon, custom_icon, status, position, id_shop, type_link, id_cms, hook , date_add) VALUES '
            . "('" . $this->img_path . "reassurance/pack2/security.svg', null, 1, 1, 1, null, null , 0 , now()),"
            . "('" . $this->img_path . "reassurance/pack2/carrier.svg', null, 1, 2, 1, null, null , 0 , now()),"
            . "('" . $this->img_path . "reassurance/pack2/parcel.svg', null, 1, 3, 1, null, null , 0 , now())";
    

        foreach (Language::getLanguages(false) as $lang) {
            $sqlQueries[] = 'INSERT INTO ' . _DB_PREFIX_ . 'itekblock_lang (id_itekblock, id_lang, id_shop, title, description, link , txt_btn) VALUES '
                . '(1, ' . $lang['id_lang'] . ", 1, '" . $this->trans('Security policy', [], 'Modules.Itekblock.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the itek module)', [], 'Modules.Itekblock.Shop', $lang['locale']) . "', '' , ''),"
                . '(2, ' . $lang['id_lang'] . ", 1, '" . $this->trans('Delivery policy', [], 'Modules.Itekblock.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the itek module)', [], 'Modules.Itekblock.Shop', $lang['locale']) . "', '' , ''),"
                . '(3, ' . $lang['id_lang'] . ", 1, '" . $this->trans('Return policy', [], 'Modules.Itekblock.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the itek module)', [], 'Modules.Itekblock.Shop', $lang['locale']) . "', '' , '')";
        }

        foreach ($sqlQueries as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        // Hooks
        if (parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBlockReassurance') &&
            $this->registerHook('displayBlockHeader') &&
            $this->registerHook('displayBlockRight') &&
            $this->registerHook('displayBlockMiddle') &&
            $this->registerHook('displayBlockNewsletter') &&
            $this->registerHook('displayBlockSeo') &&
            $this->registerHook('displayBlockPub') &&
            $this->registerHook('displayBlockArs') &&
            $this->registerHook('actionFrontControllerSetMedia')
        ) {
            return true;
        }

        $this->_errors[] = $this->trans('There was an error during the installation. Please contact us through Addons website.', [], 'Modules.Itekblock.Admin');

        return false;
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . 'views/dist/itekblock.css', 'all');
        if ($this->context->controller->controller_type == 'back') {
            $this->context->controller->addJS($this->_path . 'views/dist/back.js');
        }
    }

    /**
     * load dependencies
     */
    public function loadAsset()
    {
        $this->addJsDefList();

        $this->context->controller->addJS(_PS_JS_DIR_ . 'tiny_mce/tiny_mce.js');
        $this->context->controller->addJS(_PS_JS_DIR_ . 'admin/tinymce.inc.js');

        $this->context->controller->addCSS($this->_path . 'views/dist/itekblock.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/dist/back.js');
        $this->context->controller->addJqueryPlugin('colorpicker');
        $this->context->controller->addJqueryUI('ui.sortable');
    }

    /**
     * Check if folder img_perso is writable and executable
     *
     * @return bool
     */
    private function folderUploadFilesHasGoodRights()
    {
        return is_writable($this->folder_file_upload)
            && is_executable($this->folder_file_upload);
    }

    /**
     * @return string
     *
     * @throws PrestaShopException
     */
    public function getContent()
    {
        $this->loadAsset();
        $id_lang = $this->context->language->id;

        $currentPage = 'global';
        $getPage = Tools::getValue('page');
        if (!empty($getPage)) {
            $currentPage = $getPage;
        }

        // dump(ItekblockObject::getAllBlockByLang($id_lang, $this->context->shop->id));
        // dump(ItekblockObject::getAllBlockByShop());
        // die();
        $moduleAdminLink = Context::getContext()->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name . '&module_name=' . $this->name;
        $allCms = CMS::listCms($id_lang);
        $this->context->smarty->assign([
            'logo_path'         => $this->logo_path,
            'languages'         => Language::getLanguages(),
            'allblock'          => ItekblockObject::getAllBlockByLang($id_lang, $this->context->shop->id),
            'allblockByShop'    => ItekblockObject::getAllBlockByShop(),
            'currentPage'       => $currentPage,
            'moduleAdminLink'   => $moduleAdminLink,
            'img_path'          => $this->img_path,
            'allCms'            => $allCms,
            'defaultFormLanguage' => (int) $this->context->employee->id_lang,
            'img_url'           => $this->img_path,
            'old_img_url'       => $this->old_path_img,
            'folderIsWritable'  => $this->folderUploadFilesHasGoodRights(),
            'folderPath'        => $this->img_path_perso,
            'allshook'          => self::ITKB_HOOK,
            // constants
            'LINK_TYPE_NONE'    => ItekblockObject::TYPE_LINK_NONE,
            'LINK_TYPE_CMS'     => ItekblockObject::TYPE_LINK_CMS_PAGE,
            'LINK_TYPE_URL'     => ItekblockObject::TYPE_LINK_URL            
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    /**
     * @throws PrestaShopException
     */
    protected function addJsDefList()
    {
        Media::addJsDef([
            'psr_icon_color' => '',
            'psr_text_color' => '',
            'psr_controller_block_url' => $this->context->link->getAdminLink('AdminITKBlockListing'),
            'psr_controller_block' => 'AdminITKBlockListing',
            'psr_lang' => (int) Configuration::get('PS_LANG_DEFAULT'),
            'block_updated' => $this->trans('Block updated', [], 'Modules.Itekblock.Admin'),
            'active_error' => $this->trans('Oops... looks like an error occurred', [], 'Modules.Itekblock.Admin'),
            'min_field_error' => $this->trans('The field %field_name% is required at least in your default language.', ['%field_name%' => sprintf('"%s"', $this->trans('Title', [], 'Admin.Global'))], 'Admin.Notifications.Error'),
            'psre_success' => $this->trans('Configuration updated successfully!', [], 'Modules.Itekblock.Admin'),
            'successPosition' => $this->trans('Position changed successfully!', [], 'Modules.Itekblock.Admin'),
            'errorPosition' => $this->trans('An error occurred when switching position', [], 'Modules.Itekblock.Admin'),
            'txtConfirmRemoveBlock' => $this->trans('Are you sure?', [], 'Admin.Notifications.Warning'),
            'errorRemove' => $this->trans('An error occurred when removing block', [], 'Modules.Itekblock.Admin'),
        ]);
    }

    /**
     * @param array $params
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function hookdisplayBlockReassurance($params)
    {        
        return $this->renderWidget(0);
    }

    public function hookdisplayBlockHeader($params)
    {        
        return $this->renderWidget(1);
    }

    public function hookdisplayBlockRight($params)
    {        
        return $this->renderWidget(2);
    }

    public function hookdisplayBlockMiddle($params) {
      
        return $this->renderWidget(3);
    }

    public function hookdisplayBlockNewsletter($params) {
      
        return $this->renderWidget(4);
    }

    public function hookdisplayBlockSeo($params) {
      
        return $this->renderWidget(5);
    }

    public function hookdisplayBlockPub($params) {
      
        return $this->renderWidget(6);
    }

    public function hookdisplayBlockArs($params) {
      
        return $this->renderWidget(7);
    }

    public function hookActionFrontControllerSetMedia()
    {
        Media::addJsDef([
            'psr_icon_color' => '',
        ]);

        $this->context->controller->registerStylesheet(
            'front-css',
            'modules/' . $this->name . '/views/dist/front.css'
        );
        $this->context->controller->registerJavascript(
            'front-js',
            'modules/' . $this->name . '/views/dist/front.js'
        );
    }

    public function getItekBlockHook($hook){
        switch ($hook) {
            case 'displayHome':
                return 0;
                break;          
            case 'displayFooter':
                return 2;
                break;
            default:
                return $hook;
                break;
        }
    }

    /**
     * @param string $hookName
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
     public function renderWidget($hookName = null, array $configuration = [])
     {        
        $hook = $this->getItekBlockHook($hookName);        
        $this->smarty->assign($this->getWidgetVariables($hook, $configuration));
        return $this->fetch($this->templateFile);
     }

     /**
     * @param string $hookName
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public function getWidgetVariables($hook = null, array $configuration = [])
    {
        $blocks = ItekblockObject::getAllBlockByStatus(
            $this->context->language->id,
            $this->context->shop->id,
            $hook,
        );
        
        $elements = [];
        foreach ($blocks as $key => $value) {
            if (!empty($value['icon'])) {
                $elements[$key]['image'] = $value['icon'];
            } elseif (!empty($value['custom_icon'])) {
                $elements[$key]['image'] = $value['custom_icon'];
            } else {
                $elements[$key]['image'] = '';
            }

            $elements[$key]['text']     = $value['title'] . ' ' . $value['description'];
            $elements[$key]['title']    = $value['title'];
            $elements[$key]['description']  = $value['description'];
            $elements[$key]['type_link']    = $value['type_link'];
            $elements[$key]['txt_btn']  = $value['txt_btn'];
            $elements[$key]['is_btn']   = $value['is_btn'];
            $elements[$key]['link']     = $value['link'];
        }

        return [
            'elements' => $elements,
            'hook'     => self::ITKB_HOOK[$hook]
        ];
    }

}
