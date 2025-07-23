<?php
require_once '../src/Model/Usuario.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel'] !== 'admin') {
    header('Location: produtos.php');
    exit;
}

use App\Model\Usuario;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $usuario = new Usuario();
    $usuario->excluir($_POST['id']);
}

header('Location: usuarios.php');
exit;
?>