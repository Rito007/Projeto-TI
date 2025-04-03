<?php 
namespace Services;
require_once (dirname(__FILE__) . "/User.php");
use Services\User;

class Auth {
    // Inicia a sessão
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();  // Garante que a sessão esteja iniciada
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
        $user = User::checkUser($username, $password); // Verifica o usuário com a classe User
        if ($user) {
            $_SESSION['utilizador'] = $user->getNome(); // Guarda o nome do usuário na sessão
            return true;
        }
        return false;
    }

    // Desloga o usuário
    public function logout() {
        session_unset();
        session_destroy();
    }

    // Retorna o nome do usuário logado (se houver)
    public function getUser() {
        return $_SESSION['utilizador'] ?? null;
    }
}

?>