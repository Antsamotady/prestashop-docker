<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

declare(strict_types=1);
 
//use DateTime;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\Module\BlockRecrutement\Install\Installer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

class BlockRecrutement extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'blockrecrutement';
        $this->author = 'Resma';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Le block recrutement');
        $this->description = $this->l('Le block recrutement de la homepage');
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $installer = new Installer();

        return $installer->install($this);
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        $installer = new Installer();

        return $installer->uninstall($this);
    }


    public function renderWidget($hookName, array $params)
    {
       $this->smarty->assign([
           'latest_recrutements' => $this->getWidgetVariables($hookName, $params)
       ]);

       return $this->fetch(
           'module:blockrecrutement/views/templates/widget/widget.tpl'
       );
    }

    public function getWidgetVariables($hookName, array $params)
    {
        return $this->getCMSPagesByCmsCategory(2);
    }

    public function hookActionCmsPageFormBuilderModifier(array $params)
    {
        $locales = $this->get('prestashop.adapter.legacy.context')->getLanguages();
        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $params['form_builder'];
        $now = (new DateTime())->format('Y-m-d H:i:s');

        $formBuilder
            ->add('date_upd_page', HiddenType::class, [
                    'data' => $now,
                    'empty_data' => $now,
                ]
            )
        ;

        foreach ($locales as $lang) {
            $cms_lang = new CMS($params['id'], $lang["id_lang"]);
            $params['data']['date_upd_page'][$lang["id_lang"]] = $cms_lang->date_upd_page;
        }

        $formBuilder->setData($params['data']);
    }

    public function hookActionAfterCreateCmsPageFormHandler(array $params)
    {
        $this->updateData($params['id'], $params['form_data']);
    }

    public function hookActionAfterUpdateCmsPageFormHandler(array $params)
    {
        $this->updateData($params['id'], $params['form_data']);
    }

    protected function updateData(int $id_cms, array $data)
    {
        $cms = new CMS($id_cms);

        if (!empty($data['date_upd_page'])) {
            $cms->date_upd_page = $data['date_upd_page'];
        }

        $cms->save();
    }

    protected function getCMSPagesByCmsCategory($id_cms_category, $id_shop = false, $id_lang = false)
    {
        $id_shop = ($id_shop !== false) ? (int) $id_shop : (int) Context::getContext()->shop->id;
        $id_lang = $id_lang ? (int) $id_lang : (int) Context::getContext()->language->id;

        $sql = 'SELECT c.`id_cms`, cl.`meta_title`, cl.`link_rewrite`, cl.`date_upd_page`, cl.`content`
			FROM `' . _DB_PREFIX_ . 'cms` c
			INNER JOIN `' . _DB_PREFIX_ . 'cms_shop` cs
			ON (c.`id_cms` = cs.`id_cms`)
			INNER JOIN `' . _DB_PREFIX_ . 'cms_lang` cl
			ON (c.`id_cms` = cl.`id_cms`)
			WHERE c.`id_cms_category` = ' . (int) $id_cms_category . '
			AND cs.`id_shop` = ' . (int) $id_shop . '
			AND cl.`id_lang` = ' . (int) $id_lang . '
			AND cl.`id_shop` = ' . (int) $id_shop . '
			AND c.`active` = 1
            ORDER BY cl.`date_upd_page` DESC
            LIMIT 3
        ';

        return Db::getInstance()->executeS($sql);
    }
}
