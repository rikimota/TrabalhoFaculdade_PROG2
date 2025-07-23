<?php
namespace App\Model;

use App\Database\Conexao;
use PDO;

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConexao();
    }

    public function buscarPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listar($busca = '') {
        if (!empty($busca)) {
            $sql = "SELECT * FROM usuarios 
                    WHERE nome LIKE :busca OR email LIKE :busca
                    ORDER BY nome ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':busca', '%' . $busca . '%');
        } else {
            $sql = "SELECT * FROM usuarios ORDER BY nome ASC";
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrar($nome, $email, $telefone, $senha, $nivel = 'usuario') {
        $sql = "INSERT INTO usuarios (nome, email, telefone, senha, nivel)
                VALUES (:nome, :email, :telefone, :senha, :nivel)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telefone', $telefone);

        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
        $stmt->bindValue(':senha', $senhaCriptografada);

        $stmt->bindValue(':nivel', $nivel);

        return $stmt->execute();
    }

    public function atualizar($id, $nome, $email, $telefone, $nivel) {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone, nivel = :nivel WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':nivel', $nivel);

        return $stmt->execute();
    }

    public function excluir($id) {
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function contar($nivel = null) {
        if ($nivel) {
            $sql = "SELECT COUNT(*) as total FROM usuarios WHERE nivel = :nivel";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nivel', $nivel);
            $stmt->execute();
        } else {
            $sql = "SELECT COUNT(*) as total FROM usuarios";
            $stmt = $this->pdo->query($sql);
        }
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'];
    }    

}
?>