<?php

namespace Models;

require_once(dirname(__FILE__) . "/../config/config.php");

use Config\Config;

class Sensor
{
    private $nome;
    private $valor;
    private $dataDeAtualizacao;
    private $unidade;
    private $log;
    private $imagem;
    private static $sensores = [];

    public function __construct($nome, $valor, $unidade, $data, $log)
    {
        $this->nome = $nome;
        $this->valor = $valor;
        $this->dataDeAtualizacao = $data;
        $this->unidade = $unidade;
        $this->imagem = Config::get("relativePath") . "//img//" . strtolower(str_replace(' ', '_', trim($nome))) . ".png";
        $this->log = $log;

    }

    private function escreveFicheiro($caminho, $valor, $log =false)
    {
        if (file_exists($caminho) && !$log) {
            unlink($caminho);
        }
        $ficheiro = fopen($caminho, 'a');
        fwrite($ficheiro, $valor);
        fclose($ficheiro);
    }

    public static function getSensores()
    {
        return self::$sensores;
    }

    public function setValores($nome, $valor)
    {
       $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/valor.txt",$valor);
       $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/hora.txt", date("Y/m/d H:m:s"));
       $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/log.txt",  $valor ."," . date("Y/m/d H:m:s")."\n", true);
    }

    public function atualizaValores()
    {
        $caminho = Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome;
        $valor = file_get_contents($caminho . '/valor.txt');
        $data = file_get_contents($caminho . '/hora.txt');
        $log = file_get_contents($caminho . '/log.txt');
        $this->valor = $valor;
        $this->dataDeAtualizacao = $data;
        $this->log = $log;
    }

    public static function carregaSensorDaPasta($folderName)
    {
        $caminho = Config::get("rootPath") . Config::get("sensorPath") . '/' . $folderName;

        if (!is_dir($caminho)) {
            return null;
        }

        $nome = file_get_contents($caminho . '/nome.txt');
        $valor = file_get_contents($caminho . '/valor.txt');
        $data = file_get_contents($caminho . '/hora.txt');
        $log = file_get_contents($caminho . '/log.txt');
        $unidade = file_get_contents($caminho . '/unidade.txt');
        if (empty($nome) || (empty($valor) && $valor != 0) || empty($data)) {
            return null;
        }

        return new Sensor($nome, $valor, $unidade, $data,$log);
    }

    public static function carregarSensoresDosFicheiros()
    {
        self::$sensores = [];
        $diretorioSensores = Config::get("rootPath") . Config::get("sensorPath");

        if (is_dir($diretorioSensores)) {
            $pastas = scandir($diretorioSensores);

            foreach ($pastas as $pasta) {
                if ($pasta !== '.' && $pasta !== '..' && !str_contains($pasta, ".")) {
                    $sensor = self::carregaSensorDaPasta($pasta);
                    if ($sensor !== null) {
                        self::$sensores[] = $sensor;
                    }
                }
            }
        }
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getDataDeAtualizacao()
    {
        return $this->dataDeAtualizacao;
    }

    public function getUnidade()
    {
        return $this->unidade;
    }

    public function getImagem()
    {
        return $this->imagem;
    }

    public function getLogs()
    {
        return $this->log;
    }

    public function toArray()
    {
        return [
            'nome' => $this->nome,
            'valor' => $this->valor,
            'data_de_atualizacao' => $this->dataDeAtualizacao,
            'unidade' => $this->unidade,
            'imagem' => $this->imagem
        ];
    }

}

Sensor::carregarSensoresDosFicheiros();
