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
if (!defined('_PS_VERSION_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class blockvaleur extends Module implements WidgetInterface
{
    const ALLOWED_CONTROLLERS_CHECKOUT = [
        'cart',
        'order',
    ];
    const ALLOWED_CONTROLLERS_PRODUCT = [
        'product',
    ];
    const POSITION_NONE_CUSTOM = 1;
    const POSITION_NONE = 0;
    const POSITION_BELOW_HEADER = 1;
    const POSITION_ABOVE_HEADER = 2;

    const BLV_HOOK_HEADER = 'BLV_HOOK_HEADER';
    const BLV_HOOK_FOOTER = 'BLV_HOOK_FOOTER';
    const BLV_HOOK_PRODUCT = 'BLV_HOOK_PRODUCT';
    const BLV_HOOK_CHECKOUT = 'BLV_HOOK_CHECKOUT';

    /** @var string */
    public $name;
    /** @var string */
    public $version;
    /** @var string */
    public $author;
    /** @var bool */
    public $need_instance;
    /** @var string */
    public $controller_name;
    /** @var bool */
    public $bootstrap;
    /** @var string */
    public $displayName;
    /** @var string */
    public $description;
    /** @var string */
    public $js_path;
    /** @var string */
    public $css_path;
    /** @var string */
    public $img_path;
    /** @var string */
    public $old_path_img;
    /** @var string */
    public $img_path_perso;
    /** @var string */
    public $lib_path;
    /** @var string */
    public $docs_path;
    /** @var string */
    public $logo_path;
    /** @var string */
    public $module_path;
    /** @var string Text to display when ask for confirmation on uninstall action */
    public $confirmUninstall;
    /** @var string */
    public $ps_url;
    /** @var string */
    public $folder_file_upload;
    /** @var string */
    private $templateFile;

    public function __construct()
    {
        // Settings
        $this->name = 'blockvaleur';
        $this->tab = 'front_office_features';
        $this->version = '5.1.4';
        $this->author = 'Softibox';
        $this->need_instance = false;

        $this->bootstrap = true;
        parent::__construct();
        if ($this->context->link == null) {
            $protocolPrefix = Tools::getCurrentUrlProtocolPrefix();
            $this->context->link = new Link($protocolPrefix, $protocolPrefix);
        }

        $this->displayName = $this->trans('Customer Blockvaleur', [], 'Modules.Blockvaleur.Admin');
        $this->description = $this->trans('Add text and images', [], 'Modules.Blockvaleur.Admin');

        // Settings paths
        if (!$this->_path) {
            $this->_path = __PS_BASE_URI__ . 'modules/' . $this->name . '/';
        }
        $this->js_path = $this->_path . 'views/js/';
        $this->css_path = $this->_path . 'views/css/';
        $this->img_path = $this->_path . 'views/img/';
        $this->old_path_img = $this->_path . 'img/';
        $this->img_path_perso = $this->img_path . 'img_perso';
        $this->lib_path = $this->_path . 'views/lib/';
        $this->docs_path = $this->_path . 'docs/';
        $this->logo_path = $this->_path . 'logo.png';
        $this->module_path = $this->_path;
        $this->folder_file_upload = _PS_MODULE_DIR_ . $this->name . '/views/img/img_perso/';

        // Confirm uninstall
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall this module?', [], 'Modules.Blockvaleur.Admin');
        $this->ps_url = $this->context->link->getBaseLink();
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->templateFile = 'module:blockvaleur/views/templates/hook/blockvaleur.tpl';
    }

    /**
     * install pre-config
     *
     * @return bool
     */
    public function install()
    {
        // SQL
        $sqlQueries = [];
        $sqlQueries[] = ' CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'blockvaleur` (
            `id_blockvaleur` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `icon` varchar(255) NULL,
            `custom_icon` varchar(255) NULL,
            `status` int(10) unsigned NOT NULL,
            `position` int(10) unsigned NOT NULL,
            `type_link` int(10) unsigned NULL,
            `id_cms` int(10) unsigned NULL,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NULL,
            PRIMARY KEY (`id_blockvaleur`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';
        $sqlQueries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'blockvaleur_lang` (
            `id_blockvaleur` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `title` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            `link` varchar(255) NOT NULL,
            PRIMARY KEY (`id_blockvaleur`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        $sqlQueries[] = 'INSERT INTO ' . _DB_PREFIX_ . 'blockvaleur (icon, custom_icon, status, position, type_link, id_cms, date_add) VALUES '
            . "('" . $this->img_path . "blockvaleur/pack2/security.svg', null, 1, 1, null, null, now()),"
            . "('" . $this->img_path . "blockvaleur/pack2/carrier.svg', null, 1, 2, null, null, now()),"
            . "('" . $this->img_path . "blockvaleur/pack2/parcel.svg', null, 1, 3, null, null, now())";
        foreach (Language::getLanguages(false) as $lang) {
            $sqlQueries[] = 'INSERT INTO ' . _DB_PREFIX_ . 'blockvaleur_lang (id_blockvaleur, id_lang, title, description, link) VALUES '
                . '(1, ' . $lang['id_lang'] . ", '" . $this->trans('Valeur', [], 'Modules.Blockvaleur.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the Customer Blockvaleur module)', [], 'Modules.Blockvaleur.Shop', $lang['locale']) . "', ''),"
                . '(2, ' . $lang['id_lang'] . ", '" . $this->trans('Valeur', [], 'Modules.Blockvaleur.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the Customer Blockvaleur module)', [], 'Modules.Blockvaleur.Shop', $lang['locale']) . "', ''),"
                . '(3, ' . $lang['id_lang'] . ", '" . $this->trans('Valeur', [], 'Modules.Blockvaleur.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the Customer Blockvaleur module)', [], 'Modules.Blockvaleur.Shop', $lang['locale']) . "', '')";
        }

        foreach ($sqlQueries as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        // Configuration
        Configuration::updateValue('BLV_HOOK_HEADER', self::POSITION_NONE_CUSTOM);
        Configuration::updateValue('BLV_HOOK_FOOTER', self::POSITION_NONE);
        Configuration::updateValue('BLV_HOOK_PRODUCT', self::POSITION_BELOW_HEADER);
        Configuration::updateValue('BLV_HOOK_CHECKOUT', self::POSITION_BELOW_HEADER);
        Configuration::updateValue('BLV_ICON_COLOR', '#F19D76');
        Configuration::updateValue('BLV_TEXT_COLOR', '#000000');

        // Hooks
        if (parent::install() &&
            $this->registerHook('displayAfterBodyOpeningTag') &&
            $this->registerHook('displayNavFullWidth') &&
            $this->registerHook('displayFooterAfter') &&
            $this->registerHook('displayFooterBefore') &&
            $this->registerHook('displayBlockvaleur') &&
            $this->registerHook('actionFrontControllerSetMedia')
        ) {
            return true;
        }

        $this->_errors[] = $this->trans('There was an error during the installation. Please <a href="https://github.com/PrestaShop/PrestaShop/issues">open an issue</a> on the PrestaShop project.', [], 'Modules.Blockvaleur.Admin');

        return false;
    }

    /**
     * Uninstall module configuration
     *
     * @return bool
     */
    public function uninstall()
    {
        // SQL
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'blockvaleur`, `' . _DB_PREFIX_ . 'blockvaleur_lang`';
        if (Db::getInstance()->execute($sql) == false) {
            return false;
        }

        // Configuration
        Configuration::deleteByName('BLV_HOOK_HEADER');
        Configuration::deleteByName('BLV_HOOK_FOOTER');
        Configuration::deleteByName('BLV_HOOK_PRODUCT');
        Configuration::deleteByName('BLV_HOOK_CHECKOUT');
        Configuration::deleteByName('BLV_ICON_COLOR');
        Configuration::deleteByName('BLV_TEXT_COLOR');

        if (parent::uninstall()) {
            return true;
        }

        $this->_errors[] = $this->trans('There was an error during the uninstallation. Please <a href="https://github.com/PrestaShop/PrestaShop/issues">open an issue</a> on the PrestaShop project.', [], 'Modules.Blockvaleur.Admin');

        return false;
    }

    /**
     * load dependencies
     */
    public function loadAsset()
    {
        $this->addJsDefList();

        $this->context->controller->addCSS($this->_path . 'views/dist/back.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/dist/back.js');
    }

    /**
     * Check if folder img_perso is writable and executable
     *
     * @return bool
     */
    private function folderUploadFilesHasGoodRights()
    {
        // do not check is_executable on windows platform (https://www.php.net/manual/en/function.is-executable.php#refsect1-function.is-executable-notes)
        return is_writable($this->folder_file_upload) && !(preg_match('/^[a-zA-Z]{1}\:{1}\\\\{1}/', $this->folder_file_upload) !== 1) || is_executable($this->folder_file_upload);
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

        $moduleAdminLink = Context::getContext()->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name . '&module_name=' . $this->name;

        $allCms = CMS::listCms($id_lang);
        $fields_captions = [
            'position' => $this->trans('Position', [], 'Modules.Blockvaleur.Admin'),
            'image' => $this->trans('Image', [], 'Modules.Blockvaleur.Admin'),
            'title' => $this->trans('Title', [], 'Modules.Blockvaleur.Admin'),
            'description' => $this->trans('Description', [], 'Modules.Blockvaleur.Admin'),
            'redirection' => $this->trans('Redirection', [], 'Modules.Blockvaleur.Admin'),
            'actions' => $this->trans('Actions', [], 'Modules.Blockvaleur.Admin'),
        ];

        $this->context->smarty->assign([
            'blv_hook_header' => (int) Configuration::get('BLV_HOOK_HEADER'),
            'blv_hook_footer' => (int) Configuration::get('BLV_HOOK_FOOTER'),
            'blv_hook_product' => (int) Configuration::get('BLV_HOOK_PRODUCT'),
            'blv_hook_checkout' => (int) Configuration::get('BLV_HOOK_CHECKOUT'),
            'blv_text_color' => Configuration::get('BLV_TEXT_COLOR'),
            'blv_icon_color' => Configuration::get('BLV_ICON_COLOR'),
            'logo_path' => $this->logo_path,
            'languages' => Language::getLanguages(false),
            'allblock' => BlockvaleurActivity::getAllBlock(),
            'currentPage' => $currentPage,
            'moduleAdminLink' => $moduleAdminLink,
            'img_path' => $this->img_path,
            'allCms' => $allCms,
            'defaultFormLanguage' => (int) $this->context->employee->id_lang,
            'img_url' => $this->img_path,
            'old_img_url' => $this->old_path_img,
            'folderIsWritable' => $this->folderUploadFilesHasGoodRights(),
            'folderPath' => $this->img_path_perso,
            // constants
            'LINK_TYPE_NONE' => BlockvaleurActivity::TYPE_LINK_NONE,
            'LINK_TYPE_CMS' => BlockvaleurActivity::TYPE_LINK_CMS_PAGE,
            'LINK_TYPE_URL' => BlockvaleurActivity::TYPE_LINK_URL,
            'fields_captions' => $fields_captions,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    /**
     * @param array $params
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function hookdisplayAfterBodyOpeningTag($params)
    {
        $position = (int) Configuration::get('BLV_HOOK_HEADER');

        return $position === self::POSITION_ABOVE_HEADER ? $this->renderTemplateInHook('displayBlock.tpl') : '';
    }

    /**
     * @param array $params
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function hookdisplayNavFullWidth($params)
    {
        $position = (int) Configuration::get('BLV_HOOK_HEADER');

        return $position === self::POSITION_BELOW_HEADER ? $this->renderTemplateInHook('displayBlock.tpl') : '';
    }

    /**
     * @param array $params
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function hookdisplayFooterAfter($params)
    {
        $position = (int) Configuration::get('BLV_HOOK_FOOTER');

        return $position === self::POSITION_BELOW_HEADER ? $this->renderTemplateInHook('displayBlockWhite.tpl') : '';
    }

    /**
     * @param array $params
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function hookdisplayFooterBefore($params)
    {
        $position = (int) Configuration::get('BLV_HOOK_FOOTER');

        return $position === self::POSITION_ABOVE_HEADER ? $this->renderTemplateInHook('displayBlockWhite.tpl') : '';
    }

    /**
     * @param array $params
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function hookdisplayBlockvaleur($params)
    {
        $enableCheckout = (int) Configuration::get('BLV_HOOK_CHECKOUT');
        $enableProduct = (int) Configuration::get('BLV_HOOK_PRODUCT');
        $controller = Tools::getValue('controller');

        if (!$this->shouldWeDisplayOnBlockProduct($enableCheckout, $enableProduct, $controller)) {
            return '';
        }

        return $this->renderTemplateInHook('displayBlockProduct.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        Media::addJsDef([
            'blv_icon_color' => Configuration::get('BLV_ICON_COLOR'),
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

    /**
     * @param string $hookName
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function renderWidget($hookName = null, array $configuration = [])
    {
        if ($hookName === 'displayFooter') {
            return '';
        }
        if (!$this->isCached($this->templateFile, $this->getCacheId('blockvaleur'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('blockvaleur'));
    }

    /**
     * @param string $hookName
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $blocks = BlockvaleurActivity::getAllBlockByStatus(
            $this->context->language->id
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

            $elements[$key]['text'] = $value['title'] . ' ' . $value['description'];
            $elements[$key]['title'] = $value['title'];
            $elements[$key]['description'] = $value['description'];
            $elements[$key]['type_link'] = $value['type_link'];
            $elements[$key]['link'] = $value['link'];
        }

        return [
            'elements' => $elements,
            'LINK_TYPE_NONE' => BlockvaleurActivity::TYPE_LINK_NONE,
        ];
    }

    /**
     * Check if we can display the hook on product page or cart page.
     * The HOOK must be active
     *
     * @param int $enableCheckout
     * @param int $enableProduct
     * @param string $controller
     *
     * @return bool
     */
    private function shouldWeDisplayOnBlockProduct($enableCheckout, $enableProduct, $controller)
    {
        if ($enableCheckout === self::POSITION_BELOW_HEADER && in_array($controller, self::ALLOWED_CONTROLLERS_CHECKOUT)) {
            return true;
        }
        if ($enableProduct === self::POSITION_BELOW_HEADER && in_array($controller, self::ALLOWED_CONTROLLERS_PRODUCT)) {
            return true;
        }

        return false;
    }

    /**
     * Assign smarty variables and display the hook
     *
     * @param string $template
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    private function renderTemplateInHook($template)
    {
        $id_lang = $this->context->language->id;

        $this->context->smarty->assign([
            'blocks' => BlockvaleurActivity::getAllBlockByStatus($id_lang),
            'iconColor' => Configuration::get('BLV_ICON_COLOR'),
            'textColor' => Configuration::get('BLV_TEXT_COLOR'),
            // constants
            'LINK_TYPE_NONE' => BlockvaleurActivity::TYPE_LINK_NONE,
            'LINK_TYPE_CMS' => BlockvaleurActivity::TYPE_LINK_CMS_PAGE,
            'LINK_TYPE_URL' => BlockvaleurActivity::TYPE_LINK_URL,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/' . $template);
    }

    /**
     * @throws PrestaShopException
     */
    protected function addJsDefList()
    {
        Media::addJsDef([
            'blv_icon_color' => Configuration::get('BLV_ICON_COLOR'),
            'blv_text_color' => Configuration::get('BLV_TEXT_COLOR'),
            'blv_controller_block_url' => $this->context->link->getAdminLink('AdminBlockValeurListing'),
            'blv_controller_block' => 'AdminBlockValeurListing',
            'blv_lang' => (int) Configuration::get('PS_LANG_DEFAULT'),
            'block_updated' => $this->trans('Block updated', [], 'Modules.Blockvaleur.Admin'),
            'active_error' => $this->trans('Oops... looks like an error occurred', [], 'Modules.Blockvaleur.Admin'),
            'min_field_error' => $this->trans('The field %field_name% is required at least in your default language.', ['%field_name%' => sprintf('"%s"', $this->trans('Title', [], 'Admin.Global'))], 'Admin.Notifications.Error'),
            'psre_success' => $this->trans('Configuration updated successfully!', [], 'Modules.Blockvaleur.Admin'),
            'successPosition' => $this->trans('Position changed successfully!', [], 'Modules.Blockvaleur.Admin'),
            'errorPosition' => $this->trans('An error occurred when switching position', [], 'Modules.Blockvaleur.Admin'),
            'txtConfirmRemoveBlock' => $this->trans('Are you sure?', [], 'Admin.Notifications.Warning'),
            'errorRemove' => $this->trans('An error occurred when removing block', [], 'Modules.Blockvaleur.Admin'),
        ]);
    }
}
