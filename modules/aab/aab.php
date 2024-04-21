<?php


if (!defined('_PS_VERSION_')) {
    exit;
}

class Aab extends Module
{
    public function __construct()
    {
        $this->name = 'aab';
        $this->version = '1.0.0';
        $this->author = 'Tsilavina';
        $this->secure_key = Tools::hash($this->name);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Tsl module test');
        $this->description = $this->l('Module test d\'apresÃs tuto');
    }
}
