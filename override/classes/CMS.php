<?php
declare(strict_types=1);
class CMS extends CMSCore
{
    /*
    * module: blockrecrutement
    * date: 2024-07-04 14:03:51
    * version: 1.0.0
    */
    public $code;
    
    
    
    
    
    
    /*
    * module: blockrecrutement
    * date: 2024-07-04 14:49:47
    * version: 1.0.0
    */
    public $date_upd_page;
    /*
    * module: blockrecrutement
    * date: 2024-07-04 14:49:47
    * version: 1.0.0
    */
    public function __construct(
        $id = null,
        $idLang = null
    ) {
        self::$definition['fields']['date_upd_page'] = ['type' => self::TYPE_STRING, 'lang' => true, 'size' => 32];
        parent::__construct($id, $idLang);
    }
}
