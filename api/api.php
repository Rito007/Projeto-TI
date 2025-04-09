<?php

namespace Api;

require_once(dirname(__FILE__) . "/../models/sensor.php");
require_once(dirname(__FILE__) . "/../services/Logica.php");

use Models\Sensor;
use Services\Logica;

class Api
{
    
    private static function gerarValoresAleatorios()
    {

        foreach (Sensor::getSensores() as $sensor) {
             if ($sensor->getNome() == 'Temperatura') {
                $valor = rand(10, 30);
            } elseif ($sensor->getUnidade() == "VF" && $sensor->getNome() != "AC") {
                $valor = rand(0, 1);
            }
            if($sensor->getNome() == "AC")
            {
                    $valor = Logica::logicaTemperatura( Sensor::getSensorByName('Temperatura')->getValor());

            }
            $sensor->setValores($sensor->getNome(), $valor);
        }
    }

    public static function getSensoresDataJSON()
    {
        $data = [];
        self::gerarValoresAleatorios();
        foreach (Sensor::getSensores() as $sensor) {
            $sensor->atualizaValores();
            $data[] = $sensor->toArray();
        }
        return json_encode($data);
    }

    public static function getSensoresLogJSON()
    {
        $data = [];
        foreach (Sensor::getSensores() as $sensor) {
            $sensor->atualizaValores();
            $data[] = [
                'nome' => $sensor->getNome(),
                'unidade' => $sensor->getUnidade(),
                'logs' => $sensor->getLogs(),
            ];
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
    header('Content-Type: application/json');
    echo Api::getSensoresDataJSON();
    exit;
}
if(isset($_GET['valoresSensoresLog']))
{
    header('Content-Type: application/json');
    echo Api::getSensoresLogJSON();
    exit;
}
