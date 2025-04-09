<?php
namespace Services;
require_once __DIR__ ."/../config/config.php";
use Config\Config;
//Classe de logica usada para fazer a lógica dos sensores
class Logica{

    //Se a temperatura for maior que 22 retorna 1
    public static function logicaTemperatura($valor)
    { 
        if((float)$valor >=22)
            return 1;
        else
            return 0;

    }
    //Se estiver na lotacao maxima retorna true
    public static function logicaLotacao()
    {
        if(self::getLotacao() == Config::get('lotacaoMax'))
        {
            return 1;
        }
        return 0;


    }
    //Logica do botao (Isto é desnecessário apenas foi feito para mater a coerência)
    public static function logicaBotaoStop($valor)
    {
        if($valor == 1)
            return 1;
        else
            return 0;
    }
    //verifica se o autocarro está cheio
    public static function cheio()
    {
        $valor = file_get_contents(Config::get("rootPath")."/".Config::get("lotacao"));
        if($valor >= Config::get('lotacaoMax'))
            return true;
        else
            return false;
    }

    //verifica se o autocarro está vazio
    public static function vazio()
    {
        $valor = file_get_contents(Config::get("rootPath")."/".Config::get("lotacao"));
        if($valor <= 0)
            return true;
        else
            return false;
    }
    //Retorna o valor de pessoas no autocarro
    public static function getLotacao()
    {
        $valor = file_get_contents(Config::get("rootPath")."/".Config::get("lotacao"));
        return $valor;
    }

    //Adiciona entrada de pessoas
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

    //Adiciona saida de pessoas
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

