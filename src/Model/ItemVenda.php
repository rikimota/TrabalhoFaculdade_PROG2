<?php
namespace App\Model;

use App\Database\Conexao;
use PDO;

class ItemVenda {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    public function inserir($id_venda, $id_produto, $quantidade, $preco_unitario) {
        $sql = "INSERT INTO itens_venda (id_venda, id_produto, quantidade, preco_unitario)
                VALUES (:id_venda, :id_produto, :quantidade, :preco_unitario)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_venda', $id_venda);
        $stmt->bindValue(':id_produto', $id_produto);
        $stmt->bindValue(':quantidade', $quantidade);
        $stmt->bindValue(':preco_unitario', $preco_unitario);
        return $stmt->execute();
    }

    public function listarPorVenda($id_venda) {
        $sql = "SELECT iv.*, p.nome AS nome_produto
                FROM itens_venda iv
                JOIN produtos p ON iv.id_produto = p.id
                WHERE iv.id_venda = :id_venda";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_venda', $id_venda);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}