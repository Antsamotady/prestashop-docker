<?php
/**
 * by Tsilavina Sat May 4, 2024
 *
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Training extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'training';
        $this->author = 'Tsilavina';
        $this->version = '1.0.0';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l("Tsilavina training module");
        $this->description = $this->l("This is a training modern module");
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install() ||
            !$this->registerHook('displayLeftColumn') ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('actionGeneralPageForm') ||
            !$this->registerHook('actionGeneralPageSave') ||
            !Configuration::updateValue('TRAINING_SHOP_TSILA', 'Tsl shop training') ||
            !Configuration::updateValue('TRAINING_PARAMETER', 'Tsl value')) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('TRAINING_PARAMETER') ||
            !Configuration::deleteByName('TRAINING_SHOP_TSILA')) {
            return false;
        }

        return true;
    }

    public function renderWidget($hookName, array $params)
    {
       $this->smarty->assign( 
           $this->getWidgetVariables($hookName, $params)
       );

       return $this->fetch(
           'module:training/views/templates/widget/widget.tpl'
       );
    }

    public function getWidgetVariables($hookName, array $params)
    {
        return [
            'TrainingParameter' => Configuration::get('TRAINING_PARAMETER')
        ];
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->registerStylesheet(
            'modules-training',
            'modules/'.$this->name.'/views/assets/css/training.css',
            ['media' => 'all', 'priority' => 150]
        );

        $this->context->controller->registerJavascript(
            'modules-training',
            'modules/'.$this->name.'/views/assets/js/training.js',
            ['position' => 'bottom', 'priority' => 150]
        );
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submit'.$this->name)) {
            $moduleParameter = Tools::getValue('TRAINING_PARAMETER');

            if (!$moduleParameter ||
                empty($moduleParameter) ||
                !Validate::isGenericName($moduleParameter)) {
                $output .= $this->displayError(
                    $this->l('Invalid TRAINING_PARAMETER value')
                );
            } else {
                Configuration::updateValue('TRAINING_PARAMETER', $moduleParameter);
                $output .= $this->displayConfirmation(
                    $this->l('It\'s allright, TRAINING_PARAMETER is updated')
                );
            }
        }

        return $output.$this->displayForm();
    }

    public function displayForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $fields_form = [];
        $fields_form[0]['form'] = [
            'legend' => [
                'title' => $this->l('Training setting')
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Configuration value'),
                    'name' => 'TRAINING_PARAMETER',
                    'size' => 20,
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => $this->l('Training setting')
            ]
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex
            .'&configure='
            .$this->name;
        $helper->default_form_language = $default_lang; 
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submit'.$this->name;
        $helper->fields_value['TRAINING_PARAMETER'] = Configuration::get('TRAINING_PARAMETER');

        return $helper->generateForm($fields_form);
    }

    public function hookActionGeneralPageForm(array $hookParams)
    {
        $formBuilder = $hookParams['form_builder'];
        
        $formBuilder->add('shop_tsila', TextType::class, [
                'label' => 'Tsila shop',
                'required' => false,
                'data' => $this->get('prestashop.adapter.legacy.configuration')
                               ->get('TRAINING_SHOP_TSILA')
            ]
        );
    }

    public function hookActionGeneralPageSave(array $hookParams)
    {
        $tsila = $hookParams['form_data']['shop_tsila'];

        $this->get('prestashop.adapter.legacy.configuration')
             ->set('TRAINING_SHOP_TSILA', $tsila);
    }

}

