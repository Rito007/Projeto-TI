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
        $JaEntrada = false;
       
        foreach (Sensor::getSensores() as $sensor) {
            $valor = 0;
            if($sensor->getNome()== "AC")
                continue;
            if ($sensor->getNome() == "IF Entrada" && !Logica::cheio()) 
            {
                $valor = 0;
                if(!$JaEntrada && rand(0, 1) == 1)
                {
                    Logica::adicionarEntrada();
                    $JaEntrada = true;      
                    $valor = 1;
                }
            }
                
            if ($sensor->getNome() == "IF Saida") 
            {
                $valor = 0;
                if(!$JaEntrada && rand(0, 1) == 1)
                {
                    Logica::adicionarSaida();
                    $JaEntrada = true;      
                    $valor = 1;
                }
            }  

            if ($sensor->getNome() == 'Temperatura') {
            $valor = rand(10, 30);  
            } 
            if ($sensor->getNome() == "Botao de Paragem") {
                $valor = rand(0, 1);
            }
            $sensor->setValores($sensor->getNome(), $valor);
        }
        Sensor::getSensorByName('AC')->setValores('AC', Logica::logicaTemperatura(Sensor::getSensorByName('Temperatura')->getValor()));
        Sensor::getSensorByName('Luz de Paragem')->setValores('Luz de Paragem', Logica::logicaBotaoStop(Sensor::getSensorByName('Botao de Paragem')->getValor()));
        Sensor::getSensorByName('Motor Abrir Portas')->setValores('Motor Abrir Portas',Sensor::getSensorByName('IF Saida')->getValor());
        Sensor::getSensorByName('Luz Autocarro Cheio')->setValores('Luz Autocarro Cheio', Logica::logicaLotacao());
    }   

    public static function getSensoresDataJSON()
    {
        $data = [];
        self::gerarValoresAleatorios();
        foreach (Sensor::getSensores() as $sensor) {
            //$sensor->atualizaValores();
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
if(isset($_GET['lotacaoBus']))
{
    header('Content-Type: application/json');
    echo json_encode(Logica::getLotacao());
    exit;
}