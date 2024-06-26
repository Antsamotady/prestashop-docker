<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * @param blockvaleur $module
 *
 * @return bool|string
 *
 * @throws PrestaShopDatabaseException
 * @throws PrestaShopException
 */
function upgrade_module_4_0_0($module)
{
    $tab = new Tab();
    $tab->active = true;
    $tab->class_name = 'AdminBlockvaleurListing';
    foreach (Language::getLanguages(true) as $lang) {
        $tab->name[$lang['id_lang']] = 'blockvaleur';
    }

    $tab->id_parent = -1;
    $tab->module = 'blockvaleur';
    $tab->add();

    /*
     ** Select the blockvaleur_lang table values
     ** ps_blockvaleur_lang => id_blockvaleur, id_lang, text
     */
    $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'blockvaleur_lang`';
    $blockvaleur_langs = Db::getInstance()->ExecuteS($sql);

    /*
     ** Select the blockvaleur table values
     ** ps_blockvaleur => id_blockvaleur, id_shop, filename
     */
    $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'blockvaleur`';
    $blockvaleurs = Db::getInstance()->ExecuteS($sql);

    $sql = [];

    $sql[] = ' CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'blockvaleur` (
            `id_blockvaleur` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `icon` varchar(255) NULL,
            `custom_icon` varchar(255) NULL,
            `status` int(10) unsigned NOT NULL,
            `position` int(10) unsigned NOT NULL,
            `id_shop` int(10) unsigned NOT NULL,
            `type_link` int(10) unsigned NULL,
            `id_cms` int(10) unsigned NULL,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NULL,
            PRIMARY KEY (`id_blockvaleur`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

    $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'blockvaleur_lang` (
        `id_blockvaleur` int(10) unsigned NOT NULL,
        `id_lang` int(10) unsigned NOT NULL,
        `id_shop` int(10) unsigned NOT NULL,
        `title` varchar(255) NOT NULL,
        `description` varchar(255) NOT NULL,
        `link` varchar(255) NOT NULL,
        PRIMARY KEY (`id_blockvaleur`,`id_shop`,`id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';

    /*
     ** First we make a verification that the new tables are empty
     */
    $sql[] = 'TRUNCATE TABLE `' . _DB_PREFIX_ . 'blockvaleur`';
    $sql[] = 'TRUNCATE TABLE `' . _DB_PREFIX_ . 'blockvaleur_lang`';

    /* This path : /modules/blockvaleur/img/".$blockvaleur['file_name']
     ** is used with the real path in the module
     **
     ** We do the INSERT INTO to get the old module values
     */
    if (!empty($blockvaleurs)) {
        foreach ($blockvaleurs as $blockvaleur) {
            $sql[] = 'INSERT INTO ' . _DB_PREFIX_ . 'blockvaleur (id_blockvaleur, icon, custom_icon, status, position, id_shop, type_link, id_cms, date_add)
                VALUES (' . $blockvaleur['id_blockvaleur'] . ", '" . $module->old_path_img . $blockvaleur['file_name'] . "', null, 1, " . $blockvaleur['id_blockvaleur'] . ', ' . $blockvaleur['id_shop'] . ', null, null, now())';
        }
    }

    if (!empty($blockvaleur_langs)) {
        foreach ($blockvaleur_langs as $blockvaleur_lang) {
            $sql[] = 'INSERT INTO ' . _DB_PREFIX_ . 'blockvaleur_lang (id_blockvaleur, id_lang, id_shop, title, description, link)
            VALUES (' . $blockvaleur_lang['id_blockvaleur'] . ', ' . $blockvaleur_lang['id_lang'] . ", 1, '" . $blockvaleur_lang['text'] . "', '', '')";
        }
    }

    /*
     ** Here we execute the SQL
     */
    foreach ($sql as $query) {
        if (Db::getInstance()->execute($query) == false) {
            return Db::getInstance()->getMsgError();
        }
    }

    /*
     ** Verification if the hooks are already registered
     */
    $result = true;
    foreach ([
                 'displayAfterBodyOpeningTag',
                 'displayNavFullWidth',
                 'displayFooterAfter',
                 'displayFooterBefore',
                 'displayBlockvaleur',
                 'actionFrontControllerSetMedia',
             ] as $hookName) {
        if (!$module->isRegisteredInHook($hookName)) {
            $result &= $module->registerHook($hookName);
        }
    }

    /*
    ** We set the new configuration values
    */
    Configuration::updateValue('BLV_HOOK_HEADER', '0');
    Configuration::updateValue('BLV_HOOK_FOOTER', '0');
    Configuration::updateValue('BLV_HOOK_PRODUCT', '1');
    Configuration::updateValue('BLV_HOOK_CHECKOUT', '1');
    Configuration::updateValue('BLV_ICON_COLOR', '#F19D76');
    Configuration::updateValue('BLV_TEXT_COLOR', '#000000');

    unset($module);

    return $result;
}
