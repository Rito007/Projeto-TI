<?php 
namespace Services;
require_once (__DIR__ . "/User.php");
use Services\User;
//Classe de autenticação para gerir o login do utilizador
class Auth {
    //Inicia a sessão php
    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();  
        }
    }
    //Redireciona para um lugar se precisar ou não de login ex usado no dashboard e no index.php(página de login)
    public function checkLoginRedirect($where, $needLogin) {
        if((!$this->checkLogin() && $needLogin) || ($this->checkLogin() && !$needLogin)) {
            header("Location: " . $where);
            exit();
        }
    }

    //verifica se está autenticado
    public function checkLogin() {
        return isset($_SESSION['utilizador']);
    }

  
    // Inicia a sessão do utilizador
    public function login($username, $password) {
        User::loadUsersFromFile();
        $resultado = User::checkUser($username, $password); 
        if ($resultado->success) {
            $_SESSION['utilizador'] = $resultado->user->getNome();
        }
        return $resultado;
    }

    //Autoexplicativo
    public function logout() {
        session_unset();
        session_destroy();
    }

    //Autoexplicativo
    public function getUser() {
        return $_SESSION['utilizador'] ?? null;
    }
}

?>