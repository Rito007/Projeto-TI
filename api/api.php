<?php

namespace Api;

require_once(dirname(__FILE__) . "/../models/sensor.php");


use Models\Sensor;

class Api
{

    private static function gerarValoresAleatorios()
    {
        foreach (Sensor::getSensores() as $sensor) {
            
            if ($sensor->getNome() == 'Led') {
                $valor = (rand(0, 1) == 0) ? 'Desativado' : 'Ativo';
            } elseif ($sensor->getNome() == 'Temperatura') {
                $valor = rand(15, 50) . 'ºC';
            } elseif ($sensor->getNome() == 'Humidade') {
                $valor = rand(30, 90) . '%';
            } else {
                $valor = rand(0, 100);
            }
            $sensor->setValores($sensor->getNome(), $valor);
        }
    }

    public static function getSensoresDataJSON()
    {
        $data = [];
        foreach (Sensor::getSensores() as $sensor) {
            self::gerarValoresAleatorios();
            $sensor->atualizaValores();
            $data[] = $sensor->toArray();
        }
        return json_encode($data);
    }
    public static function getSensoresData()
    {
        $data = [];
        foreach (Sensor::getSensores() as $sensor) {
            $sensor->atualizaValores();
            $data[] = $sensor->toArray();
        }
        return $data;
    }
}






if (isset($_GET['valoresSensores'])) {
   // header('Content-Type: application/json');
    echo Api::getSensoresDataJSON();
    exit;
}
