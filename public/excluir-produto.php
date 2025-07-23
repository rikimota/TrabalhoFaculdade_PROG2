<?php
require_once '../src/Model/Produto.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Produto;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $produto = new Produto();
    $produto->excluir($_POST['id']);
}

header('Location: produtos.php');
exit;
?>