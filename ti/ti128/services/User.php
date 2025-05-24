<?php
namespace Services;

require_once(dirname(__FILE__) ."/../config/config.php");


use Config\Config;
//Clase utilizada para gerir os utilizadores criados / carregar e salvar em ficheiro SEM MOTORES de BASE DE DADOS
class User {
    public $username;
    private $password;
    private static $users = [];
    public static $localUserDB;
    //Cria um utilizador e se precisar de fazer hash da senha será feito
    public function __construct($username, $password, $needsHash = True) {
        $this->username = $username;
        if ($needsHash) {
            $this->password = password_hash($password, PASSWORD_DEFAULT); // Hash da senha
        } else {
            $this->password = $password;
        }
    }
    //Autoexplicativo
    public function getPassword() {
        return $this->password;
    }
    //Autoexplicativo
    public function getNome() {
        return $this->username;
    }

    // Verifica o utilizador e retorna o resultado
    //O resultado vem num objeto onde contem erro de nome em string, password e sucesso no login para aparecer na pagina de login
    public static function checkUser($username, $password) {
        if (isset(self::$users[$username])) {
            $user = self::$users[$username];
            if (password_verify($password, $user->getPassword())) {
                return (object)['user'=>$user,'success'=>true]; // Retorna o objeto User em caso de sucesso
            }
            else
                return (object)['unameErro'=>'', 'passwordErro'=>'Palavra passe incorreta.','success'=>false];
        }
        else
            return (object)['unameErro'=>'Utilizador não encontrado.', 'passwordErro'=>'','success'=>false];
    }
    //Inicializar o caminho da DB
    public static function inicializar()
    {
        self::$localUserDB = Config::get("rootPath").Config::get("dbPath");
    }
    //Carregar os utilizadores do ficheiro
    public static function loadUsersFromFile() {
        self::$users = [];
        if (!file_exists(self::$localUserDB) || !is_readable(self::$localUserDB)) {
            echo  "Arquivo path:".self::$localUserDB;
            echo'<br><br>'. Config::get("dbPath");
            echo "<br>" . (file_exists(self::$localUserDB) ? 'Arquivo existe' : 'Arquivo não existe');

            // Verifica se o arquivo é legível
            echo "<br>" . (is_readable(self::$localUserDB) ? 'Arquivo pode ser lido' : 'Arquivo não pode ser lido');
            trigger_error("Erro de configuraçao do ficheiro da Database", E_USER_WARNING);  
            return;
        }
        
        $file = fopen(self::$localUserDB, "r");

        while (($data = fgetcsv($file)) !== false) {
            if (isset($data[0]) && isset($data[1])) {
                $username = $data[0];
                $password = $data[1];

                // Cria um novo objeto User e adiciona à lista obs: A lista é estática
                self::addUser(new User($username, $password, false));
            }
        }

        // Fecha o arquivo
        fclose($file);
    }
    //Salva em csv com as passwords já em hash
    public static function addUserCsv($user) {
        $file = fopen(self::$localUserDB, "a");
        fputcsv($file, [$user->getNome(), $user->getPassword()]);
        fclose($file);
    }
    //Adiciona o utilizador à lista
    public static function addUser($user) {
        self::$users[$user->getNome()] = $user;
    }
}

//User::loadUsersFromFile();

User::inicializar();
//Contas criadas aqui
//Se for preciso adicionar utilizador apenas basta descomentar o codigo, alterar e executar este arquivo ex localhost/services/user.php
//User::addUserCsv(new User("admin","adminadmin"));
//echo "Utilizador Criado";
//User::addUserCsv(new User("default","defaultuser"));
?>