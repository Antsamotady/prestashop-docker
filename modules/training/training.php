<?php
/**
 * by Tsilavina Sat May 4, 2024
 *
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

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
            !Configuration::updateValue('TRAINING_PARAMETER', 'Tsl value')) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('TRAINING_PARAMETER')) {
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
}

