<?php

use Symfony\Component\HttpFoundation\Response;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Aaa extends Module
{
    public function __construct()
    {
        $this->name = 'aaa';
        $this->version = '1.0.0';
        $this->author = 'Tsilavina';
        $this->secure_key = Tools::hash($this->name);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Tsl module test');
        $this->description = $this->l('Module test d\'apresÃs tuto');

        //$response = new Response();
        //$response->setContent('<html><body>Hello stuff!</body></html>');
        //$response->setStatusCode(Response::HTTP_OK);
        //$response->headers->set('Content-Type', 'text/html');

        //$response->send();
    }
}
