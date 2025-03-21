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

class My_Blockreassurance extends Module implements WidgetInterface
{
    const ALLOWED_CONTROLLERS_CHECKOUT = [
        'cart',
        'order',
    ];
    const ALLOWED_CONTROLLERS_PRODUCT = [
        'product',
    ];
    const POSITION_NONE = 0;
    const POSITION_BELOW_HEADER = 1;
    const POSITION_ABOVE_HEADER = 2;

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
    /** @var string */
    private $prefix;

    public function __construct()
    {
        // Settings
        $this->name = 'my_blockreassurance';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Tsilavina';
        $this->need_instance = false;

        $this->bootstrap = true;
        parent::__construct();
        if ($this->context->link == null) {
            $protocolPrefix = Tools::getCurrentUrlProtocolPrefix();
            $this->context->link = new Link($protocolPrefix, $protocolPrefix);
        }

        $this->displayName = $this->trans('Customer Text and Image', [], 'Modules.Blockreassurance.Admin');
        $this->description = $this->trans('Add and edit text with image you want', [], 'Modules.Blockreassurance.Admin');

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
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall this module?', [], 'Modules.Blockreassurance.Admin');
        $this->ps_url = $this->context->link->getBaseLink();
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        $this->templateFile = 'module:my_blockreassurance/views/templates/hook/blockreassurance.tpl';
        $this->prefix = 'tsl_';
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
        $sqlQueries[] = ' CREATE TABLE IF NOT EXISTS `' . $this->prefix . 'myreassurance` (
            `id_myreassurance` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `icon` varchar(255) NULL,
            `custom_icon` varchar(255) NULL,
            `status` int(10) unsigned NOT NULL,
            `position` int(10) unsigned NOT NULL,
            `type_link` int(10) unsigned NULL,
            `id_cms` int(10) unsigned NULL,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NULL,
            PRIMARY KEY (`id_myreassurance`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';
        $sqlQueries[] = 'CREATE TABLE IF NOT EXISTS `' . $this->prefix . 'myreassurance_lang` (
            `id_myreassurance` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `title` varchar(255) NOT NULL,
            `description` varchar(255) NOT NULL,
            `link` varchar(255) NOT NULL,
            PRIMARY KEY (`id_myreassurance`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

        $sqlQueries[] = 'INSERT INTO ' . $this->prefix . 'myreassurance (icon, custom_icon, status, position, type_link, id_cms, date_add) VALUES '
            . "('" . $this->img_path . "reassurance/pack2/security.svg', null, 1, 1, null, null, now()),"
            . "('" . $this->img_path . "reassurance/pack2/carrier.svg', null, 1, 2, null, null, now()),"
            . "('" . $this->img_path . "reassurance/pack2/parcel.svg', null, 1, 3, null, null, now())";
        foreach (Language::getLanguages(false) as $lang) {
            $sqlQueries[] = 'INSERT INTO ' . $this->prefix . 'myreassurance_lang (id_myreassurance, id_lang, title, description, link) VALUES '
                . '(1, ' . $lang['id_lang'] . ", '" . $this->trans('Security policy', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the Customer Reassurance module)', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', ''),"
                . '(2, ' . $lang['id_lang'] . ", '" . $this->trans('Delivery policy', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the Customer Reassurance module)', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', ''),"
                . '(3, ' . $lang['id_lang'] . ", '" . $this->trans('Return policy', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', '" . $this->trans('(edit with the Customer Reassurance module)', [], 'Modules.Blockreassurance.Shop', $lang['locale']) . "', '')";
        }

        foreach ($sqlQueries as $query) {
            if (Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        // Configuration
        Configuration::updateValue('PSR_HOOK_MY_HEADER', self::POSITION_NONE);
        Configuration::updateValue('PSR_HOOK_MY_FOOTER', self::POSITION_NONE);
        Configuration::updateValue('PSR_HOOK_MY_PRODUCT', self::POSITION_BELOW_HEADER);
        Configuration::updateValue('PSR_HOOK_MY_CHECKOUT', self::POSITION_BELOW_HEADER);
        Configuration::updateValue('PSR_ICON_COLOR', '#F19D76');
        Configuration::updateValue('PSR_TEXT_COLOR', '#000000');

        // Hooks
        if (parent::install() &&
            $this->registerHook('displayAfterBodyOpeningTag') &&
            $this->registerHook('displayNavFullWidth') &&
            $this->registerHook('displayFooterAfter') &&
            $this->registerHook('displayFooterBefore') &&
            $this->registerHook('displayReassurance') &&
            $this->registerHook('actionFrontControllerSetMedia')
        ) {
            return true;
        }

        $this->_errors[] = $this->trans('There was an error during the installation. Please <a href="https://github.com/PrestaShop/PrestaShop/issues">open an issue</a> on the PrestaShop project.', [], 'Modules.Blockreassurance.Admin');

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
        $sql = 'DROP TABLE IF EXISTS `' . $this->prefix . 'myreassurance`, `' . $this->prefix . 'myreassurance_lang`';
        if (Db::getInstance()->execute($sql) == false) {
            return false;
        }

        // Configuration
        Configuration::deleteByName('PSR_HOOK_MY_HEADER');
        Configuration::deleteByName('PSR_HOOK_MY_FOOTER');
        Configuration::deleteByName('PSR_HOOK_MY_PRODUCT');
        Configuration::deleteByName('PSR_HOOK_MY_CHECKOUT');
        Configuration::deleteByName('PSR_ICON_COLOR');
        Configuration::deleteByName('PSR_TEXT_COLOR');

        if (parent::uninstall()) {
            return true;
        }

        $this->_errors[] = $this->trans('There was an error during the uninstallation. Please <a href="https://github.com/PrestaShop/PrestaShop/issues">open an issue</a> on the PrestaShop project.', [], 'Modules.Blockreassurance.Admin');

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

        $moduleAdminLink = Context::getContext()->link->getAdminLink('AdminModules', true) . '&configure=' . $this->name . '&module_name=' . $this->name;

        $allCms = CMS::listCms($id_lang);
        $fields_captions = [
            'position' => $this->trans('Position', [], 'Modules.Blockreassurance.Admin'),
            'image' => $this->trans('Image', [], 'Modules.Blockreassurance.Admin'),
            'title' => $this->trans('Title', [], 'Modules.Blockreassurance.Admin'),
            'description' => $this->trans('Description', [], 'Modules.Blockreassurance.Admin'),
            'redirection' => $this->trans('Redirection', [], 'Modules.Blockreassurance.Admin'),
            'actions' => $this->trans('Actions', [], 'Modules.Blockreassurance.Admin'),
        ];

        $this->context->smarty->assign([
            'psr_hook_header' => (int) Configuration::get('PSR_HOOK_MY_HEADER'),
            'psr_hook_footer' => (int) Configuration::get('PSR_HOOK_MY_FOOTER'),
            'psr_hook_product' => (int) Configuration::get('PSR_HOOK_MY_PRODUCT'),
            'psr_hook_checkout' => (int) Configuration::get('PSR_HOOK_MY_CHECKOUT'),
            'psr_text_color' => Configuration::get('PSR_TEXT_COLOR'),
            'psr_icon_color' => Configuration::get('PSR_ICON_COLOR'),
            'logo_path' => $this->logo_path,
            'languages' => Language::getLanguages(false),
            'allblock' => ReassuranceActivity::getAllBlock(),
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
            'LINK_TYPE_NONE' => ReassuranceActivity::TYPE_LINK_NONE,
            'LINK_TYPE_CMS' => ReassuranceActivity::TYPE_LINK_CMS_PAGE,
            'LINK_TYPE_URL' => ReassuranceActivity::TYPE_LINK_URL,
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
        $position = (int) Configuration::get('PSR_HOOK_MY_HEADER');

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
        $position = (int) Configuration::get('PSR_HOOK_MY_HEADER');

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
        $position = (int) Configuration::get('PSR_HOOK_MY_FOOTER');

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
        $position = (int) Configuration::get('PSR_HOOK_MY_FOOTER');

        return $position === self::POSITION_ABOVE_HEADER ? $this->renderTemplateInHook('displayBlockWhite.tpl') : '';
    }

    /**
     * @param array $params
     *
     * @return string
     *
     * @throws PrestaShopDatabaseException
     */
    public function hookdisplayReassurance($params)
    {
        $enableCheckout = (int) Configuration::get('PSR_HOOK_MY_CHECKOUT');
        $enableProduct = (int) Configuration::get('PSR_HOOK_MY_PRODUCT');
        $controller = Tools::getValue('controller');

        if (!$this->shouldWeDisplayOnBlockProduct($enableCheckout, $enableProduct, $controller)) {
            return '';
        }

        return $this->renderTemplateInHook('displayBlockProduct.tpl');
    }

    public function hookActionFrontControllerSetMedia()
    {
        Media::addJsDef([
            'psr_icon_color' => Configuration::get('PSR_ICON_COLOR'),
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
        if (!$this->isCached($this->templateFile, $this->getCacheId('myreassurance'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('myreassurance'));
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
        $blocks = ReassuranceActivity::getAllBlockByStatus(
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
            'LINK_TYPE_NONE' => ReassuranceActivity::TYPE_LINK_NONE,
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
            'blocks' => ReassuranceActivity::getAllBlockByStatus($id_lang),
            'iconColor' => Configuration::get('PSR_ICON_COLOR'),
            'textColor' => Configuration::get('PSR_TEXT_COLOR'),
            // constants
            'LINK_TYPE_NONE' => ReassuranceActivity::TYPE_LINK_NONE,
            'LINK_TYPE_CMS' => ReassuranceActivity::TYPE_LINK_CMS_PAGE,
            'LINK_TYPE_URL' => ReassuranceActivity::TYPE_LINK_URL,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/' . $template);
    }

    /**
     * @throws PrestaShopException
     */
    protected function addJsDefList()
    {
        Media::addJsDef([
            'psr_icon_color' => Configuration::get('PSR_ICON_COLOR'),
            'psr_text_color' => Configuration::get('PSR_TEXT_COLOR'),
            'psr_controller_block_url' => $this->context->link->getAdminLink('AdminBlockListing'),
            'psr_controller_block' => 'AdminBlockListing',
            'psr_lang' => (int) Configuration::get('PS_LANG_DEFAULT'),
            'block_updated' => $this->trans('Block updated', [], 'Modules.Blockreassurance.Admin'),
            'active_error' => $this->trans('Oops... looks like an error occurred', [], 'Modules.Blockreassurance.Admin'),
            'min_field_error' => $this->trans('The field %field_name% is required at least in your default language.', ['%field_name%' => sprintf('"%s"', $this->trans('Title', [], 'Admin.Global'))], 'Admin.Notifications.Error'),
            'psre_success' => $this->trans('Configuration updated successfully!', [], 'Modules.Blockreassurance.Admin'),
            'successPosition' => $this->trans('Position changed successfully!', [], 'Modules.Blockreassurance.Admin'),
            'errorPosition' => $this->trans('An error occurred when switching position', [], 'Modules.Blockreassurance.Admin'),
            'txtConfirmRemoveBlock' => $this->trans('Are you sure?', [], 'Admin.Notifications.Warning'),
            'errorRemove' => $this->trans('An error occurred when removing block', [], 'Modules.Blockreassurance.Admin'),
        ]);
    }
}
