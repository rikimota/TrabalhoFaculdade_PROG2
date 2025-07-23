<?php
require_once '../src/Model/Categoria.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Categoria;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    if ($id > 0) {
        $categoria = new Categoria();
        $categoria->excluir($id);
    }
}

header('Location: categorias.php');
exit;
?>