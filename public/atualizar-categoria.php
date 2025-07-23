<?php
require_once '../src/Model/Categoria.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Categoria;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['nome'])) {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);

    if ($id > 0 && !empty($nome)) {
        $categoria = new Categoria();
        $categoria->atualizar($id, $nome);
    }
}

header('Location: categorias.php');
exit;
?>