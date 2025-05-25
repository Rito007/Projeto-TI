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
    private $ultimaMudancaTimestamp = null;
    private static $sensores = [];

    public function __construct($nome, $valor, $unidade, $data, $log)
    {
        $this->nome = $nome;
        $this->valor = $valor;
        $this->unidade = $unidade;
        $this->dataDeAtualizacao = $data;
        $this->log = $log;
        $this->imagem = "img/" . strtolower(str_replace(' ', '_', trim($nome))) . ".png";
        $this->ultimaMudancaTimestamp = time();
    }

    public static function getSensorByName($nome)
    {
        $nomeLimpo = str_replace(' ', '', trim($nome));

        foreach (self::$sensores as $sensor) {
            if (str_replace(' ', '', trim($sensor->getNome())) === $nomeLimpo) {
                return $sensor;
            }
        }

        return null;
    }

    private function escreveFicheiro($caminho, $conteudo, $log = false)
    {
        if (file_exists($caminho) && !$log) {
            unlink($caminho);
        }

        file_put_contents($caminho, $conteudo, FILE_APPEND);
    }

    public function setValores($valor, $hora)
    {
        if ($this->valor !== $valor) {
            $this->valor = $valor;
            $this->dataDeAtualizacao = $hora;
            $this->ultimaMudancaTimestamp = time();

            $basePath = Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome;
            $this->escreveFicheiro("$basePath/valor.txt", $valor);
            $this->escreveFicheiro("$basePath/hora.txt", $this->dataDeAtualizacao);
            $this->escreveFicheiro("$basePath/log.txt", "$valor,{$this->dataDeAtualizacao}\n", true);
        }
    }

    public static function limparLogs()
    {
        foreach (self::$sensores as $sensor) {
            $sensor->escreveFicheiro(
                Config::get("rootPath") . Config::get("sensorPath") . '/' . $sensor->nome . "/log.txt",
                " ",
                false
            );
        }
    }

    public function atualizaValores()
    {
        $basePath = Config::get("rootPath") . Config::get("sensorPath") . '/' . $this->nome;
        $this->valor = file_get_contents("$basePath/valor.txt");
        $this->dataDeAtualizacao = file_get_contents("$basePath/hora.txt");
        $this->log = file_get_contents("$basePath/log.txt");
    }

    public static function carregaSensorDaPasta($folderName)
    {
        $basePath = Config::get("rootPath") . Config::get("sensorPath") . '/' . $folderName;

        if (!is_dir($basePath)) return null;

        $nome = @file_get_contents("$basePath/nome.txt");
        $valor = @file_get_contents("$basePath/valor.txt");
        $data = @file_get_contents("$basePath/hora.txt");
        $log = @file_get_contents("$basePath/log.txt");
        $unidade = @file_get_contents("$basePath/unidade.txt");

        if (empty($nome) || ($valor === false || ($valor === '' && $valor !== '0')) || empty($data)) {
            return null;
        }

        return new Sensor($nome, $valor, $unidade, $data, $log);
    }

    public static function carregarSensoresDosFicheiros()
    {
        self::$sensores = [];

        $dir = Config::get("rootPath") . Config::get("sensorPath");

        if (!is_dir($dir)) return;

        foreach (scandir($dir) as $folder) {
            if ($folder === '.' || $folder === '..' || str_contains($folder, '.')) continue;

            $sensor = self::carregaSensorDaPasta($folder);
            if ($sensor !== null) {
                self::$sensores[] = $sensor;
            }
        }
    }

    // Getters
    public function getNome() { return $this->nome; }
    public function getValor() { return $this->valor; }
    public function getDataDeAtualizacao() { return $this->dataDeAtualizacao; }
    public function getUnidade() { return $this->unidade; }
    public function getImagem() { return $this->imagem; }
    public function getLogs() { return $this->log; }
    public function getUltimaMudancaTimestamp() { return $this->ultimaMudancaTimestamp; }

    public static function getSensores() { return self::$sensores; }

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

// Inicializar sensores automaticamente
Sensor::carregarSensoresDosFicheiros();

// Permitir apagar logs via GET
if (isset($_GET['limparLogs'])) {
    Sensor::limparLogs();
    echo "Logs apagadas";
}
