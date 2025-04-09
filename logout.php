<?php
require_once(dirname(__FILE__) ."/services/Auth.php");
require_once(dirname(__FILE__) ."/config/config.php");
use Services\Auth;
use Config\Config;
//Inicia sessão php
$Auth = new Auth();
$Auth->getUser();
//Verifica login
if($Auth->checkLogin())
{
    //Faz logout
    $Auth->logout();
}
$relativePath = Config::get("relativePath");
//Redireciona para a página de login
$Auth->checkLoginRedirect($relativePath,true);
?>