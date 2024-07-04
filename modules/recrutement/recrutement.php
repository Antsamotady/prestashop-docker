<?php
/**
 * by Tsilavina Sat May 4, 2024
 *
 */


use DateTime;
use PrestaShopBundle\Form\Admin\Type\DatePickerType;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Recrutement extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'recrutement';
        $this->author = 'Resma';
        $this->version = '1.0.0';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l("Recrutement block");
        $this->description = $this->l("Recrutement block on the homepage");
    }

    protected function updateTableCms($action = "install")
    {
        $error=0;
        $sql = [];

        if ($action == 'install'){
            //$sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'cms_lang` ADD COLUMN `date_upd_page` datetime NOT NULL';
            $sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'cms_lang` ADD COLUMN `date_upd_page` varchar(32) NOT NULL DEFAULT \'\'';
        } elseif ($action == 'uninstall') {
            if ($this->columnExists('date_upd_page', 'cms_lang')) {
                $sql[] = 'ALTER TABLE `' . _DB_PREFIX_ . 'cms_lang` DROP COLUMN `date_upd_page`';
            }
        }

        if (count($sql)>0) {
            foreach ($sql as $key => $value) {
                if(Db::getInstance()->execute($value)==false){
                    $error++;
                }
            }
        }

        if($error>0){
            return false;
        }

        return true;
    }

    private function columnExists($columnName, $table = "cms")
    {
        $db = Db::getInstance();
        $result = $db->executeS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . $table.'` LIKE \'' . pSQL($columnName) . '\'');

        return !empty($result);
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() 
            ||
            !$this->registerHook('displayHome') ||
            //!$this->registerHook('actionCmsPageFormBuilderModifier') ||
            //!$this->registerHook('actionAfterCreateCmsPageFormHandler') ||
            //!$this->registerHook('actionAfterUpdateCmsPageFormHandler') ||
            // !$this->registerHook('actionGeneralPageForm') ||
            !$this->updateTableCms()
            ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()
            ) {
            return false;
        }
        $updateTableCms = $this->updateTableCms('uninstall');

        return true;
    }

    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        $id_lang = $this->context->language->id;
        $id_shop = $this->context->shop->id;
    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign([
            'menu' => $this->getWidgetVariables($hookName, $configuration),
        ]);

       return $this->fetch(
           'module:recrutement/views/templates/widget/widget.tpl'
       );
    }

    //public function hookActionAfterCreateCmsPageFormHandler(array $params)
    //{
    //    $this->updateData($params['id'], $params['form_data']);
    //}

    //public function hookActionAfterUpdateCmsPageFormHandler(array $params)
    //{
    //    $this->updateData($params['id'], $params['form_data']);
    //}

    protected function updateData(int $id_cms, array $data)
    {
        $cms = new CMS($id_cms);

        //dump($data['date_upd_page']); die;
        if (!empty($data['date_upd_page'])) {
            $cms->date_upd_page = $data['date_upd_page'];
        }

        $cms->save();
    }
}

