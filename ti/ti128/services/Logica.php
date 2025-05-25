<?php

namespace Services;

require_once __DIR__ . "/../config/config.php";

use Config\Config;

class Logica
{
    private static function getLotacaoFicheiroPath()
    {
        return Config::get("rootPath") . "/" . Config::get("lotacao");
    }

    private static function lerLotacao()
    {
        return (int) file_get_contents(self::getLotacaoFicheiroPath());
    }

    private static function escreverLotacao($valor)
    {
        file_put_contents(self::getLotacaoFicheiroPath(), $valor);
    }

    public static function logicaTemperatura($valor)
    {
        return (float) $valor >= 23 ? 1 : 0;
    }

    public static function logicaLotacao()
    {
        return self::lerLotacao() >= (int) Config::get('lotacaoMax') ? 1 : 0;
    }

    public static function logicaBotaoStop($valor)
    {
        return $valor == 1 ? 1 : 0;
    }

    public static function cheio()
    {
        return self::lerLotacao() >= (int) Config::get('lotacaoMax');
    }

    public static function vazio()
    {
        return self::lerLotacao() <= 0;
    }

    public static function getLotacao()
    {
        return self::lerLotacao();
    }

    public static function adicionarEntrada()
    {
        if (self::cheio()) return false;

        $novaLotacao = self::lerLotacao() + 1;
        self::escreverLotacao($novaLotacao);
        return true;
    }

    public static function adicionarSaida()
    {
        if (self::vazio()) return false;

        $novaLotacao = self::lerLotacao() - 1;
        self::escreverLotacao($novaLotacao);
        return true;
    }
}
