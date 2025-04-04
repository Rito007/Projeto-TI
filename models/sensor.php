<?php

namespace Models;

require_once(dirname(__FILE__) . "/../config/config.php");

use Config\Config;

class Sensor
{
    private $nome;
    private $valor;
    private $dataDeAtualizacao;
    private $estado;
    private $imagem;
    private static $sensores = [];

    public function __construct($nome, $valor, $estado, $data)
    {
        $this->nome = $nome;
        $this->valor = $valor;
        $this->dataDeAtualizacao = $data;
        $this->estado = $estado;
        $this->imagem = Config::get("relativePath") . "/img/" . strtolower(str_replace(' ', '_', trim($nome))) . ".png";
    }

    private function escreveFicheiro($caminho, $valor, $log =false)
    {
        if (file_exists($caminho) && !$log) {
            unlink($caminho);
        }
        file_put_contents($caminho, $valor);
    }

    public static function getSensores()
    {
        return self::$sensores;
    }

    public function setValores($nome, $valor)
    {
       $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/valor.txt",$valor);
       $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/hora.txt", date("Y/m/d H:m:s"));
       $this->escreveFicheiro(Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/log.txt", "Alterado de " . $this->valor ." -> ". $valor ." Data: " . date("Y/m/d H:m:s"), true);
    }

    public function atualizaValores()
    {
        $caminho = Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome . "/valor.txt";
        $this->valor = file_get_contents($caminho);
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

        if (empty($nome) || empty($valor) || empty($data)) {
            return null;
        }

        return new Sensor($nome, $valor, 'Normal', $data);
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

    public function getEstado()
    {
        return $this->estado;
    }

    public function getImagem()
    {
        return $this->imagem;
    }

    public function toArray()
    {
        return [
            'nome' => $this->nome,
            'valor' => $this->valor,
            'data_de_atualizacao' => $this->dataDeAtualizacao,
            'estado' => $this->estado,
            'imagem' => $this->imagem
        ];
    }
}

Sensor::carregarSensoresDosFicheiros();
