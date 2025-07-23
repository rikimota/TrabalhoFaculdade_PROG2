<?php
namespace App\Model;

use App\Database\Conexao;
use PDO;

class Venda {
    private $pdo;

    public function __construct() {
        $this->pdo =Conexao::getConexao();
    }

    public function registrarVendaCompleta($id_usuario, $itens) {
        try {
            // Inicia uma transação no banco de dados (garante que tudo ocorra de forma segura)
            $this->pdo->beginTransaction();

            $totalVenda = 0; // Variável para somar o valor total da venda

            // Percorre cada item que o usuário quer comprar
            foreach ($itens as $i => $item) {
                $produtoId = $item['produto_id'];
                $quantidade = $item['quantidade'];

                // Busca o produto no banco de dados para verificar se ele existe e pegar preço e estoque atual
                $stmt = $this->pdo->prepare("SELECT nome, quantidade, preco FROM produtos WHERE id = ?");
                $stmt->execute([$produtoId]);
                $produto = $stmt->fetch(PDO::FETCH_ASSOC);

                // Se o produto não for encontrado, lança um erro
                if (!$produto) {
                    throw new \Exception("Produto ID $produtoId não encontrado.");
                }

                // Verifica se a quantidade é válida (maior que 0)
                if ($quantidade <= 0) {
                    throw new \Exception("A quantidade para o produto '{$produto['nome']}' deve ser maior que zero.");
                }

                // Verifica se há estoque suficiente para esse produto
                if ($quantidade > $produto['quantidade']) {
                    throw new \Exception("Estoque insuficiente para o produto '{$produto['nome']}'. Quantidade disponível: {$produto['quantidade']}.");
                }

                // Calcula o subtotal do item e soma ao total da venda
                $precoUnitario = $produto['preco'];
                $totalVenda += $precoUnitario * $quantidade;

                // Atualiza o item com o preço que estava no banco (para registrar corretamente)
                $itens[$i]['preco_unitario'] = $precoUnitario;
            }

            // Insere a venda na tabela `vendas` com o total calculado
            $stmt = $this->pdo->prepare("INSERT INTO vendas (id_usuario, data, total) VALUES (?, NOW(), ?)");
            $stmt->execute([$id_usuario, $totalVenda]);

            // Pega o ID gerado automaticamente para a venda recém-criada
            $idVenda = $this->pdo->lastInsertId();

            // Agora insere os itens da venda na tabela `itens_venda` e atualiza o estoque dos produtos
            foreach ($itens as $item) {
                // Registra o item vendido
                $stmt = $this->pdo->prepare("INSERT INTO itens_venda (id_venda, id_produto, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $idVenda,
                    $item['produto_id'],
                    $item['quantidade'],
                    $item['preco_unitario']
                ]);

                // Subtrai a quantidade vendida do estoque do produto
                $stmt = $this->pdo->prepare("UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?");
                $stmt->execute([$item['quantidade'], $item['produto_id']]);
            }

            // Finaliza (confirma) a transação — agora tudo é salvo no banco
            $this->pdo->commit();

            // Retorna o ID da venda registrada com sucesso
            return $idVenda;

        } catch (\Exception $e) {
            // Se algo deu errado, desfaz todas as alterações feitas até aqui
            $this->pdo->rollBack();
            throw $e; // Repassa o erro para quem chamou a função
        }
    }

    public function listar() {
        $sql = "SELECT v.*, u.nome AS nome_usuario
                FROM vendas v
                JOIN usuarios u ON v.id_usuario = u.id
                ORDER BY v.data DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarPorPeriodo($inicio, $fim) {
        $sql = "
            SELECT 
                v.id,
                v.data AS data_venda,
                u.nome AS nome_usuario,
                SUM(iv.quantidade) AS quantidade_itens,
                SUM(iv.preco_unitario * iv.quantidade) AS total
            FROM vendas v
            JOIN usuarios u ON v.id_usuario = u.id
            JOIN itens_venda iv ON v.id = iv.id_venda
            WHERE DATE(v.data) BETWEEN :inicio AND :fim
            GROUP BY v.id, v.data, u.nome
            ORDER BY v.data DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':inicio', $inicio);
        $stmt->bindValue(':fim', $fim);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT v.*, u.nome AS nome_usuario
                FROM vendas  v
                LEFT JOIN usuarios u ON v.id_usuario = u.id
                WHERE v.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarComResumo() {
        $sql = "
            SELECT 
                v.id,
                v.data AS data_venda,
                u.nome AS nome_usuario,
                SUM(iv.quantidade) AS quantidade_itens,
                SUM(iv.preco_unitario * iv.quantidade) AS total
            FROM vendas v
            JOIN usuarios u ON v.id_usuario = u.id
            JOIN itens_venda iv ON v.id = iv.id_venda
            GROUP BY v.id, v.data, u.nome
            ORDER BY v.data DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}