<?php
declare(strict_types=1);
class Category extends CategoryCore
{
    /**
     * @var string
     */
    /*
    * module: blockcategorie
    * date: 2024-06-30 10:23:14
    * version: 1.0.0
    */
    public $hp_description;
    /*
    * module: blockcategorie
    * date: 2024-06-30 10:23:14
    * version: 1.0.0
    */
    public function __construct(
        $id = null,
        $idLang = null
    ) {
        self::$definition['fields']['hp_description'] = ['type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'];
        parent::__construct($id, $idLang);
    }
}
