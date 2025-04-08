<?php
namespace Config;
class Config {

    private static $config = [
        'dbPath' => '/db/users.csv',
        'rootPath' => null,
        'relativePath' => null,
        'sensorPath' => '/api/sensores',
    ];

    public static function inicializar() {
        if (self::$config['rootPath'] === null) {
            self::$config['rootPath'] = $_SERVER['DOCUMENT_ROOT'] . "/Projeto-TI";
            self::$config["relativePath"] = str_replace('C:/xampp/htdocs', '', self::$config["rootPath"]);
        }
    }

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
