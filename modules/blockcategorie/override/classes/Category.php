<?php

declare(strict_types=1);

class Category extends CategoryCore
{
    /**
     * @var string
     */
    public $hp_description;

    public function __construct(
        $id = null,
        $idLang = null
    ) {
        self::$definition['fields']['hp_description'] = ['type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'];
        parent::__construct($id, $idLang);
    }
}
