<?php
namespace Services;

require_once(dirname(__FILE__) ."./../config/config.php");


use Config\Config;

class User {
    public $username;
    private $password;
    private static $users = [];
    public static $localUserDB;

    public function __construct($username, $password, $needsHash = True) {
        $this->username = $username;
        if ($needsHash) {
            $this->password = password_hash($password, PASSWORD_DEFAULT); // Hash da senha
        } else {
            $this->password = $password;
        }
    }

    public function getPassword() {
        return $this->password;
    }

    public function getNome() {
        return $this->username;
    }

    // Verifica o usuário e retorna o resultado
    public static function checkUser($username, $password) {
        if (isset(self::$users[$username])) {
            $user = self::$users[$username];
            if (password_verify($password, $user->getPassword())) {
                return $user; // Retorna o objeto User em caso de sucesso
            }
        }
        return null; // Retorna null se não encontrar o usuário ou senha incorreta
    }

    public static function inicializar()
    {
        self::$localUserDB = Config::get("rootPath").Config::get("dbPath");
    }

    public static function loadUsersFromFile() {
       
        if (!file_exists(filename: self::$localUserDB) || !is_readable(self::$localUserDB)) {
            return;
        }

        // Abre o arquivo para leitura
        $file = fopen(self::$localUserDB, "r");

        // Lê linha por linha do arquivo CSV
        while (($data = fgetcsv($file)) !== false) {
            if (isset($data[0]) && isset($data[1])) {
                $username = $data[0];
                $password = $data[1];

                // Cria um novo objeto User e adiciona à lista
                self::addUser(new User($username, $password, false));
            }
        }

        // Fecha o arquivo
        fclose($file);
    }

    public static function addUserCsv($user) {
        $file = fopen(self::$localUserDB, "a");
        fputcsv($file, [$user->getNome(), $user->getPassword()]);
        fclose($file);
    }

    public static function addUser($user) {
        self::$users[$user->getNome()] = $user;
    }
}

//User::loadUsersFromFile();
//Contas criadas aqui
User::inicializar();
//User::addUserCsv(new User("admin","adminadmin"));
//echo "Utilizador Criado";
// User::addUserCsv(new User("default","defaultuser"));
?>