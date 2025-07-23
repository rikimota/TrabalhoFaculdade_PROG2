<?php

namespace App\Model;

use PDO;
use App\Database\Conexao;

class Produto {
    private $pdo;

    public function __construct(){
        $this->pdo = Conexao::getConexao();
    }

    public function listarTodos(){
        $stmt = $this->pdo->query("SELECT * FROM produtos ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrar($nome, $descricao, $preco, $quantidade, $id_categoria){
        $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade, id_categoria)
                VALUES (:nome, :descricao, :preco, :quantidade, :id_categoria)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':preco', $preco);
        $stmt->bindValue(':quantidade', $quantidade);
        $stmt->bindValue(':id_categoria', $id_categoria);

        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM produtos WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome, $descricao, $preco, $quantidade, $id_categoria) {
        $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, quantidade = :quantidade, id_categoria = :id_categoria WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':descricao', $descricao);
        $stmt->bindValue(':preco', $preco);
        $stmt->bindValue(':quantidade', $quantidade);
        $stmt->bindValue(':id_categoria', $id_categoria);

        return $stmt->execute();
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listar($busca = '', $filtro = '') {
        $sql = "SELECT p.*, c.nome AS categoria_nome 
                FROM produtos p 
                LEFT JOIN categorias c ON p.id_categoria = c.id 
                WHERE 1=1";  // Adicionamos uma condição verdadeira para facilitar a construção dinâmica da consulta

        $params = [];

        // Se houver busca, aplicamos o filtro de nome
        if (!empty($busca)) {
            $sql .= " AND p.nome LIKE :busca";
            $params[':busca'] = '%' . $busca . '%';
        }

        // Se o filtro de baixo estoque estiver ativo, aplicamos a condição de quantidade < 5
        if ($filtro === 'baixo') {
            $sql .= " AND p.quantidade < 5";
        }

        // Ordenação pela ID dos produtos
        $sql .= " ORDER BY p.id DESC";

        // Prepara a consulta
        $stmt = $this->pdo->prepare($sql);
        // Executa a consulta com os parâmetros
        $stmt->execute($params);

        // Retorna os produtos encontrados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function contar() {
        $sql = "SELECT COUNT(*) as total FROM produtos";
        $stmt = $this->pdo->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }

    public function contarPorCategoria() {
        $sql = "SELECT c.nome AS categoria_nome, COUNT(p.id) AS total
                FROM categorias c
                LEFT JOIN produtos p ON p.id_categoria = c.id
                GROUP BY c.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorStatusEstoque() {
        $sql = "
            SELECT 
                SUM(CASE WHEN quantidade < 5 THEN 1 ELSE 0 END) AS baixo,
                SUM(CASE WHEN quantidade BETWEEN 5 AND 10 THEN 1 ELSE 0 END) AS medio,
                SUM(CASE WHEN quantidade > 10 THEN 1 ELSE 0 END) AS ok
            FROM produtos
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    

}

?>