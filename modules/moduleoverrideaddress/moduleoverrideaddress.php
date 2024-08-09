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

use PrestaShop\Module\ModuleOverrideAddress\Install\Installer;
use Symfony\Component\Form\Extension\Core\Type\TextType;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__.'/vendor/autoload.php';

class ModuleOverrideAddress extends Module
{
    public function __construct()
    {
        $this->name = 'moduleoverrideaddress';
        $this->author = 'Softibox';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '1.7.7.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Override object model Adresse');
        $this->description = $this->l('Override object model Address and add custom field \'quartier\' to database table');
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

    public function hookActionCustomerAddressFormBuilderModifier(array $params)
    {
        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $params['form_builder'];

        $formBuilder
            ->add('quartier', TextType::class, [
                    'label' => 'Quartier',
                    'required' => false,
                ]
            )
        ;

        $address = new Address($params['id']);
        $params['data']['quartier'] = $address->quartier;

        $formBuilder->setData($params['data']);
    }

    public function hookActionAfterCreateCustomerAddressFormHandler(array $params)
    {
        $this->updateData($params['id'],$params['form_data']);
    }

    public function hookActionAfterUpdateCustomerAddressFormHandler(array $params)
    {
        $this->updateData($params['id'],$params['form_data']);
    }

    /**
     * Fonction qui va effectuer la mise à jour
     * @param array $data
     */
    protected function updateData(int $id_address, array $data)
    {
        $entity = new Address($id_address);

        if (!empty($data['quartier'])) {
            $entity->quartier = $data['quartier'];
        }

        $entity->save();
    }
}
