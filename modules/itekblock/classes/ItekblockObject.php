<?php
class ItekblockObject extends ObjectModel
{
    public const TYPE_LINK_NONE = 0;
    public const TYPE_LINK_CMS_PAGE = 1;
    public const TYPE_LINK_URL = 2;
    public const DATE_ISO_FORMAT = 'Y-m-d H:i:s';


    public $id;
    public $icon;
    public $custom_icon;
    public $title;
    public $description;
    public $status;
    public $position;
    public $id_shop;
    public $type_link;
    public $link;
    public $id_cms;
    public $hook;
    public $is_btn;
    public $txt_btn;    
    public $date_add;
    public $date_upd;

    /**
     * Constructor method
     */
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);
    }

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'itekblock',
        'primary' => 'id_itekblock',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'icon' => ['type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isCleanHtml', 'size' => 255],
            'custom_icon' => ['type' => self::TYPE_STRING, 'shop' => true, 'validate' => 'isCleanHtml', 'size' => 255],
            'title'     => ['type' => self::TYPE_HTML, 'shop' => true, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255],
            'description' => ['type' => self::TYPE_HTML, 'shop' => true, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 2000],
            'status'    => ['type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool', 'required' => true],
            'position'  => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isunsignedInt', 'required' => false],
            'type_link' => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isunsignedInt', 'required' => false],
            'id_cms'    => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isunsignedInt', 'required' => false],
            'link'      => ['type' => self::TYPE_STRING, 'shop' => true, 'lang' => true, 'validate' => 'isUrl', 'required' => false, 'size' => 255],
            'hook'      => ['type' => self::TYPE_INT, 'shop' => true, 'validate' => 'isunsignedInt', 'required' => false],
            'is_btn'    => ['type' => self::TYPE_BOOL, 'shop' => true, 'validate' => 'isBool', 'required' => false],
            'txt_btn'   => ['type' => self::TYPE_STRING, 'shop' => true, 'lang' => true, 'validate' => 'isCleanHtml', 'size' => 255],
            'date_add'  => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate', 'format' => self::DATE_ISO_FORMAT],
            'date_upd'  => ['type' => self::TYPE_DATE, 'shop' => true, 'validate' => 'isDate', 'format' => self::DATE_ISO_FORMAT],
        ],
    ];

    /**
     * @param int $id_lang
     * @param int $id_shop
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public static function getAllBlockByLang($id_lang = 1, $id_shop = 1)
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'itekblock` pr
            LEFT JOIN ' . _DB_PREFIX_ . 'itekblock_lang prl ON (pr.id_itekblock = prl.id_itekblock)
            WHERE prl.id_lang = "' . (int) $id_lang . '" AND prl.id_shop = "' . (int) $id_shop . '"
            ORDER BY pr.position';

            $result = Db::getInstance()->executeS($sql, ['id_lang' => (int)$id_lang, 'id_shop' => (int)$id_shop]);

            return $result;
    }

/**
 * @param array $itkb_languages
 * @param int $type_link
 * @param int $id_cms
 */
public function handleBlockValues($itkb_languages, $type_link, $id_cms)
{
    $languages = Language::getLanguages();
    $newValues = [];

    foreach ($itkb_languages as $key => $value) {
        $newValues[$key] = [
            'title'         => isset($value->title) ? $value->title : '',
            'description'   => isset($value->description) ? $value->description : '',
            'url'           => isset($value->url) ? $value->url : '',
            'txt_btn'       => isset($value->txt_btn) ? $value->txt_btn : ''
        ];
    }

    foreach ($languages as $language) {
        if (false === array_key_exists($language['id_lang'], $newValues)) {
            continue;
        }

        $this->title[$language['id_lang']]  = $newValues[$language['id_lang']]['title'];
        $this->description[$language['id_lang']] = $newValues[$language['id_lang']]['description'];
        $this->txt_btn[$language['id_lang']] = $newValues[$language['id_lang']]['txt_btn'];
        $this->link[$language['id_lang']] = '';
     
        if ($type_link === self::TYPE_LINK_URL) {
            $this->link[$language['id_lang']] = $newValues[$language['id_lang']]['url'];
        }
    }

    if (!empty($id_cms) && $type_link === self::TYPE_LINK_CMS_PAGE) {
        $this->id_cms = $id_cms;
        $link = Context::getContext()->link;

        foreach ($languages as $language) {
            $this->link[$language['id_lang']] = $link->getCMSLink(
                $id_cms,
                null,
                null,
                $language['id_lang']
            );
        }
    }

    if ($type_link == 'undefined') {
        $type_link = self::TYPE_LINK_NONE;
    }

    $this->type_link = $type_link;
}


    /**
     * @param int $id_shop
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public static function getAllBlockByShop($id_shop = 1)
    {
        $result = [];

        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'itekblock` pr
            LEFT JOIN ' . _DB_PREFIX_ . 'itekblock_lang prl ON (pr.id_itekblock = prl.id_itekblock)
            WHERE prl.id_shop = "' . (int) $id_shop . '"
            GROUP BY prl.id_lang, pr.id_itekblock
            ORDER BY pr.position';

        $dbResult = Db::getInstance()->executeS($sql);

        foreach ($dbResult as $key => $value) {
            $result[$value['id_lang']][$value['id_itekblock']]['title'] = $value['title'];
            $result[$value['id_lang']][$value['id_itekblock']]['description'] = $value['description'];
            $result[$value['id_lang']][$value['id_itekblock']]['url'] = $value['link'];
            $result[$value['id_lang']][$value['id_itekblock']]['txt_btn'] = $value['txt_btn'];
        }

        return $result;
    }

    /**
     * @param int $id_lang
     * @param int $id_shop
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public static function getAllBlockByStatus($id_lang = 1, $id_shop = 1, $hook = 0)
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'itekblock` pr
            LEFT JOIN ' . _DB_PREFIX_ . 'itekblock_lang prl ON (pr.id_itekblock = prl.id_itekblock)
            WHERE prl.id_lang = "' . (int) $id_lang . '"
                AND prl.id_shop = "' . (int) $id_shop . '"
                AND pr.status = 1
                AND pr.hook = '.(int)$hook.'
            ORDER BY pr.position';

        $result = Db::getInstance()->executeS($sql);
        return $result;
    }
}
