<?php
namespace Config;

//Classe config é uma classe de configuração da aplicação web

class Config {
    private static $config = [
        'dbPath' => 'db/users.csv',
        'rootPath' => null,
        'relativePath' => null,
        'sensorPath' => '/api/sensores',
        'lotacao' => 'db/lotacaoAutocarro.txt',
        'lotacaoMax' => 20,
    ];


    //Inicialização de alguns valores impossiveis de iniciar na static config
    public static function inicializar() {
        if (self::$config['rootPath'] === null) {
            self::$config['rootPath'] = realpath($_SERVER['DOCUMENT_ROOT'] . '/PROJETO-TI');
            self::$config['relativePath'] = str_replace(
                realpath($_SERVER['DOCUMENT_ROOT']),
                '',
                self::$config['rootPath']
            );
        }
    }

    //Função para retornar um valor de config a partir de uma $key; ex de uso: Config::get('dbPath');
    public static function get($key) {
        if (!isset(self::$config[$key])) {
            trigger_error("Configuração '$key' não encontrada.", E_USER_WARNING);
            return null;
        }
        
        return self::$config[$key];
    }
}

Config::inicializar();
?>
