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

class CMS extends CMSCore
{
    /**
     * @var string
     */
    public $date_upd_page;

    public function __construct(
        $id = null,
        $idLang = null
    ) {
        self::$definition['fields']['date_upd_page'] = ['type' => self::TYPE_STRING, 'lang' => true, 'size' => 32];
        parent::__construct($id, $idLang);
    }
}
