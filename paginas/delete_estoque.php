<?php
session_start();

// Verifica se a sessão está ativa
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    header('Location: ../index.php');
    exit();
}

// Inclua o arquivo de configuração para a conexão com o banco de dados
include_once('../config/config.php');

// Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o ID do cliente a ser excluído
    $id_estoque = $_POST['id_estoque'];

    // Prepara a consulta para excluir o cliente
    $stmt = $conexao->prepare("DELETE FROM estoque WHERE id_estoque = :id");
    $stmt->bindParam(':id', $id_estoque, PDO::PARAM_INT);

    // Executa a consulta e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        // Redireciona para a página de clientes com uma mensagem de sucesso
        header('Location: estoque.php?message=Estoque excluído com sucesso');
        exit();
    } else {
        // Caso ocorra um erro, redireciona com uma mensagem de erro
        header('Location: estoque.php?message=Erro ao excluir o estoque');
        exit();
    }
} else {
    // Redireciona se a requisição não for POST
    header('Location: estoque.php');
    exit();
}
?>
