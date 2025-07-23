<?php

namespace App\Model;

use App\Database\Conexao;
use PDO;

class Categoria {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM categorias ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($nome) {
        $stmt = $this->pdo->prepare("INSERT INTO categorias (nome) VALUES (:nome)");
        $stmt->bindValue(':nome', $nome);
        return $stmt->execute();
    }

    public function buscarPorId($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $nome) {
        $stmt = $this->pdo->prepare("UPDATE categorias SET nome = :nome WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $nome);
        return $stmt->execute();
    }

    public function excluir($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categorias WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function contar() {
        $sql = "SELECT COUNT(*) as total FROM categorias";
        $stmt = $this->pdo->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }
    
}

?>