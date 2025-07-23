<!-- Está sendo utilado para validação no form de login.php -->
<?php
session_start();
require_once '../src/Model/Usuario.php';
require_once '../src/Database/Conexao.php';

use App\Model\Usuario;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->buscarPorEmail($email);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario'] = $usuario;
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['erro_login'] = 'E-mail ou senha inválidos.';
        header('Location: login.php');
        exit;
    }
}
