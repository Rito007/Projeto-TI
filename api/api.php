<?php

namespace Api;

require_once(dirname(__FILE__) . "/../models/sensor.php");
require_once(dirname(__FILE__) . "/../services/Logica.php");

use Models\Sensor;
use Services\Logica;
//Classe de api criada para receber e entregar valores
//Futuramente irá ser usada para integrar dispositivos reais
class Api
{
    //Gera valores aleatórios para os sensores independentes, por exemplo o botão de paragem
    //Os dependentes estão relacionados com outros sensores
    //Exemplo se a temperatura for mais que 22ºC o AC é ligado
    //Temperatura é independente e AC é dependente
    //Esta função faz de uso a classe logica que faz a lógica dos sensores dependetes
    private static function gerarValoresAleatorios()
    {
        $JaEntrada = false;
       
        foreach (Sensor::getSensores() as $sensor) {
            $valor = 0;
            $nomeSensor = $sensor->getNome();
            if($nomeSensor== "AC" || $nomeSensor == "Luz Autocarro Cheio" || $nomeSensor == "Luz de Paragem" || $nomeSensor=="Motor Abrir Portas")
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

        //Altera os valores dos sensores independentes
        Sensor::getSensorByName('AC')->setValores('AC', Logica::logicaTemperatura(Sensor::getSensorByName('Temperatura')->getValor()));
        Sensor::getSensorByName('Luz de Paragem')->setValores('Luz de Paragem', Logica::logicaBotaoStop(Sensor::getSensorByName('Botao de Paragem')->getValor()));
        Sensor::getSensorByName('Motor Abrir Portas')->setValores('Motor Abrir Portas',Sensor::getSensorByName('IF Saida')->getValor());
        Sensor::getSensorByName('Luz Autocarro Cheio')->setValores('Luz Autocarro Cheio', Logica::logicaLotacao());
    }   

    //retorna os valores dos sensores em JSON
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
    //retorna as logs dos sensores em JSON
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
    //faz retorno dos dados dos sensores
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




//Api routes que ainda podem ser melhoradas

//Retorna o JSON dos valores dos sensores
if (isset($_GET['valoresSensores'])) {
    header('Content-Type: application/json');
    echo Api::getSensoresDataJSON();
    exit;
}
//Retorna o JSON dos logs dos sensores
if(isset($_GET['valoresSensoresLog']))
{
    header('Content-Type: application/json');
    echo Api::getSensoresLogJSON();
    exit;
}
//Retorna o numero de pessoas no autocarro
if(isset($_GET['lotacaoBus']))
{
    header('Content-Type: application/json');
    echo json_encode(Logica::getLotacao());
    exit;
}