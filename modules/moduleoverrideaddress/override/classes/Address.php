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
    public $quartier;

    public function __construct(
        $id = null,
        $idLang = null
    ) {
        self::$definition['fields']['quartier'] = ['type' => self::TYPE_STRING, 'size' => 128];
        parent::__construct($id, $idLang);
    }
}
