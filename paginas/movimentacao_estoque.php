<?php
session_start();

// Verifica se a sessão está ativa
if (!isset($_SESSION['email']) || !isset($_SESSION['senha']) || !isset($_SESSION['nome']) || !isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit();
}

// Inclua o arquivo de configuração para a conexão com o banco de dados
include_once('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estoque = $_POST['id_estoque'];
    $adicionar = isset($_POST['adicionar']) ? (int)$_POST['adicionar'] : 0;
    $retirar = isset($_POST['retirar']) ? (int)$_POST['retirar'] : 0;

    // Obter a quantidade atual do estoque
    $stmt = $conexao->prepare("SELECT quantidade FROM estoque WHERE id_estoque = :id");
    $stmt->bindValue(':id', $id_estoque, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        $quantidadeAtual = $produto['quantidade'];

        // Calcular a nova quantidade
        $novaQuantidade = $quantidadeAtual + $adicionar - $retirar;

        // Verificar se a nova quantidade é válida (não pode ser negativa)
        if ($novaQuantidade < 0) {
            // Redireciona de volta com uma mensagem de erro
            header('Location: estoque.php?message=Quantidade insuficiente para retirar.');
            exit();
        }

        // Atualizar a quantidade no banco de dados
        $stmt = $conexao->prepare("UPDATE estoque SET quantidade = :quantidade WHERE id_estoque = :id");
        $stmt->bindValue(':quantidade', $novaQuantidade, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id_estoque, PDO::PARAM_INT);
        $stmt->execute();

        // Redireciona de volta com uma mensagem de sucesso
        header('Location: estoque.php?message=Movimentação realizada com sucesso.');
        exit();
    } else {
        // Produto não encontrado
        header('Location: estoque.php?message=Produto não encontrado.');
        exit();
    }
} else {
    // Redireciona se não for uma requisição POST
    header('Location: estoque.php');
    exit();
}
