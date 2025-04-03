<?php
require_once(dirname(__FILE__) ."/services/Auth.php");
require_once(dirname(__FILE__) ."/config/config.php");
use Services\Auth;
use Config\Config;
$Auth = new Auth();
$Auth->getUser();

if($Auth->checkLogin())
{
    $Auth->logout();
}
$relativePath = Config::get("relativePath");
echo $relativePath;
$Auth->checkLoginRedirect($relativePath,true);
?>