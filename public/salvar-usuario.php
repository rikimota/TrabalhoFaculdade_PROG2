<?php
require_once '../src/Database/Conexao.php';
require_once '../src/Model/Usuario.php';
require_once './verifica-login.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel'] !== 'admin') {
    // Redireciona para a home ou exibe uma mensagem de erro
    header('Location: index.php'); // ou login.php
    exit;
}

use App\Model\Usuario;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? null;
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $nivel = $_POST['nivel'] ?? 'usuario';

    $erros = [];

    // Validações básicas
    if (empty($nome)) $erros[] = 'Nome e orbigatorio.';
    if (empty($email)) $erros[] = 'Email e obrigatorio.';
    if (empty($telefone)) $erros[] = 'Telefone e obrigatorio.';

    $usuario = new Usuario();
    // Verifica se é edição ou inserção
    if (!empty($id)) {
        $usuario->atualizar($id, $nome, $email, $telefone, $nivel);
    } else {
        $senha = $_POST['senha'] ?? '';
        $usuario->cadastrar($nome, $email, $telefone, $senha, $nivel);
    }

    header('Location: usuarios.php');
    exit;
}