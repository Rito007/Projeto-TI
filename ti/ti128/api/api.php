<?php

namespace Api;

require_once(dirname(__FILE__) . "/../models/sensor.php");
require_once(dirname(__FILE__) . "/../services/Logica.php");

use Models\Sensor;
use Services\Logica;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Classe de api criada para receber e entregar valores
//Futuramente irá ser usada para integrar dispositivos reais
class Api
{




    //retorna os valores dos sensores em JSON
    public static function getSensoresDataJSON()
    {
        $data = [];
        //self::gerarValoresAleatorios();
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

    public static function getSensor($name)
    {
        $sensor = Sensor::getSensorByName($name);
        if (isset($sensor)) {
            return $sensor->getValor();
        } else {
            return null;
        }
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

if(isset($_GET['sensor']))
{
    $valor = Api::getSensor($_GET['sensor']);
   
    
    if(isset($valor))
    {
        http_response_code(200);
        echo $valor;
        exit;
    }
    else
    {
        http_response_code(404);
        exit;
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = isset($_POST['nome']) && !empty($_POST['nome']) ? $_POST['nome'] : null;
    $valor = isset($_POST['valor']) ? $_POST['valor'] : null;
    $hora = isset($_POST['hora']) && !empty($_POST['hora']) ? $_POST['hora'] : null;

    if ($nome && $valor !== null && $hora) {
        $sensor = Sensor::getSensorByName($nome);

        if ($sensor) {
            // Atualiza o sensor recebido via POST
            $sensor->setValores($valor, $hora);

            // Mapa dos sensores pais para os filhos que dependem deles
            $mapaPaiFilho = [
                'Temperatura' => ['AC'],
                'Botao de Paragem' => ['LuzdeParagem'],
                'IF Entrada' => ['Entrada', 'LuzAutocarroCheio'],
                'IF Saida' => ['Saida', 'LuzAutocarroCheio', 'MotorAbrirPortas'], // Aqui adicionamos o filho extra
            ];

            // Se o sensor atualizado é um pai, processa a lógica para atualizar os filhos
            if (array_key_exists($nome, $mapaPaiFilho)) {
                foreach ($mapaPaiFilho[$nome] as $filho) {
                    switch ($filho) {
                        case 'AC':
                            Sensor::getSensorByName('AC')->setValores(
                                Logica::logicaTemperatura(Sensor::getSensorByName('Temperatura')->getValor()),
                                $hora
                            );
                            break;

                        case 'LuzdeParagem':
                            Sensor::getSensorByName('LuzdeParagem')->setValores(
                                Logica::logicaBotaoStop(Sensor::getSensorByName('BotaodeParagem')->getValor()),
                                $hora
                            );
                            break;

                        case 'LuzAutocarroCheio':
                            Sensor::getSensorByName('LuzAutocarroCheio')->setValores(
                                Logica::logicaLotacao(),
                                $hora
                            );
                            break;
                        case 'Entrada':
                            if($valor == 1)
                                Logica::adicionarEntrada();
                            break;
                        case 'Saida':
                            if($valor == 1)
                                Logica::adicionarSaida();
                            break;

                        case 'MotorAbrirPortas':
                            Sensor::getSensorByName('MotorAbrirPortas')->setValores(
                                Sensor::getSensorByName('IFSaida')->getValor(),
                                $hora
                            );
                            break;
                    }
                }
            }

            http_response_code(200);
            echo "Sensor atualizado com sucesso. Nome: $nome, Valor: $valor, Hora: $hora";
        } else {
            http_response_code(404);
            echo "Erro: Sensor não encontrado com o nome: $nome";
        }
    } else {
        http_response_code(400);
        echo "Erro: Todos os campos (nome, valor, hora) são obrigatórios.";
    }
}

