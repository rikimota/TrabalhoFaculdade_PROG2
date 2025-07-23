<?php
session_start();

require_once '../src/Model/Produto.php';
require_once '../src/Model/Categoria.php';
require_once '../src/Database/Conexao.php';
require_once './verifica-login.php';

use App\Model\Produto;
use App\Model\Categoria;

// Verifica se foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recebe os dados
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $quantidade = $_POST['quantidade'] ?? 0;
    $id_categoria = $_POST['id_categoria'] ?? null;

    $erros = [];

    //          Validacoes          //
    if (empty($nome)) $erros[] = 'Nome do produto e obrigatorio.';
    if (empty($descricao)) $erros[] = 'Descricao e obrigatoria.';
    if ($preco <= 0) $erros[] = 'Preco deve ser maior que zero.';
    if ($quantidade < 0) $erros[] = 'Quantidade nao pode ser negativa.';

    //Verifica se a categoria existe
    $categoria = new Categoria();
    $categorias = $categoria->listar();
    $categoriasIds = array_column($categorias, 'id');

    if (!in_array($id_categoria, $categoriasIds)) $erros[] = 'Categoria Invalida.';

    if (!empty($erros)) {
        $_SESSION['erros'] = $erros;
        $_SESSION['dados'] = $_POST;
        if (!empty($id)) {
            header('Location: editar-produto.php?id='.$id);
        } else {
            header('Location: novo-produto.php');
        }
        exit;
    }
    //          FIM Validacoes          //

    $produto = new Produto();
    // Verifica se é edição ou inserção
    if (!empty($id)) {
        $produto->atualizar($id, $nome, $descricao, $preco, $quantidade, $id_categoria);
    } else {
        //$produto = new Produto();
        $produto->cadastrar($nome, $descricao, $preco, $quantidade, $id_categoria);
    }

    // Redireciona de volta para a lista
    header('Location: produtos.php');
    exit;
}