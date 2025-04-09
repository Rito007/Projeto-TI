<?php
namespace Services;
require_once __DIR__ ."./../config/config.php";
use Config\Config;

class Logica{


    public static function logicaTemperatura($valor)
    { 
        if((float)$valor >=22)
            return 1;
        else
            return 0;

    }

    public static function logicaBotaoStop($valor)
    {
        if($valor == 1)
            return 1;
        else
            return 0;
    }

    public static function cheio()
    {
        $valor = file_get_contents(Config::get("rootPath")."/".Config::get("lotacao"));
        if($valor >= Config::get('lotacaoMax'))
            return true;
        else
            return false;
    }

    public static function vazio()
    {
        $valor = file_get_contents(Config::get("rootPath")."/".Config::get("lotacao"));
        if($valor <= 0)
            return true;
        else
            return false;
    }

    public static function getLotacao()
    {
        $valor = file_get_contents(Config::get("rootPath")."/".Config::get("lotacao"));
        return $valor;
    }

    public static function adicionarEntrada()
    {
        if(!self::cheio())
        {
            $valor =file_get_contents(Config::get("rootPath")."/".Config::get("lotacao"));
            $valor +=1;
            file_put_contents(Config::get("rootPath")."/".Config::get("lotacao"), $valor);
            return true;
        }
        else
        {
            return false;
        }  
    }
    public static function adicionarSaida()
    {
        if(!self::vazio())
        {
            $valor =file_get_contents(Config::get("rootPath")."/".Config::get("lotacao"));
            $valor -=1;
            file_put_contents(Config::get("rootPath")."/".Config::get("lotacao"), $valor);
            return true;
        }
        else
        {
            return false;
        }  
    }

}

?>