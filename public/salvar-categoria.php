<?php
require_once '../src/Model/Categoria.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Categoria;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
    $nome = trim($_POST['nome']);

    if (!empty($nome)) {
        $categoria = new Categoria();
        $categoria->salvar($nome);
    }
}

header('Location: categorias.php');
exit;
?>