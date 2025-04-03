<?php 
namespace Services;
require_once (dirname(__FILE__) . "/User.php");
use Services\User;

class Auth {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();  
        }
    }

    public function checkLoginRedirect($where, $needLogin) {
        if((!$this->checkLogin() && $needLogin) || ($this->checkLogin() && !$needLogin)) {
            header("Location: " . $where);
            die();
        }
    }

    public function checkLogin() {
        return isset($_SESSION['utilizador']);
    }

  
    // Inicia a sessão do usuário
    public function login($username, $password) {
        User::loadUsersFromFile();
        $resultado = User::checkUser($username, $password); 
        if ($resultado->success) {
            $_SESSION['utilizador'] = $resultado->user->getNome();
        }
        return $resultado;
    }


    public function logout() {
        session_unset();
        session_destroy();
    }


    public function getUser() {
        return $_SESSION['utilizador'] ?? null;
    }
}

?>