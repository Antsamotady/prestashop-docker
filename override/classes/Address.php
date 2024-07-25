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
class Address extends AddressCore
{
    /**
     * @var string
     */
    /*
    * module: moduleoverrideaddress
    * date: 2024-07-24 23:48:43
    * version: 1.0.0
    */
    public $quartier;
    /*
    * module: moduleoverrideaddress
    * date: 2024-07-24 23:48:43
    * version: 1.0.0
    */
    public function __construct(
        $id = null,
        $idLang = null
    ) {
        self::$definition['fields']['quartier'] = ['type' => self::TYPE_STRING, 'size' => 128];
        parent::__construct($id, $idLang);
    }
}
