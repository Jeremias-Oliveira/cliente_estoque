<?php
session_start();

// Verifica se a sessão está ativa
if (!isset($_SESSION['email']) || !isset($_SESSION['senha']) || !isset($_SESSION['nome']) || !isset($_SESSION['id'])) {
    // Se não estiver ativo, destrói a sessão e redireciona para a página inicial
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    unset($_SESSION['nome']);
    unset($_SESSION['id']);
    unset($_SESSION['foto']);

    header('Location: ../index.php');
    exit(); // Certifique-se de encerrar a execução após o redirecionamento
}
$id = $_SESSION['id'];
$foto = $_SESSION['foto'];
$nome = $_SESSION['nome'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/home-layout.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #333;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: black;
            color: #fff;
            padding: 0;
            text-align: center;
            margin: 0;
        }
        header img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto; /* Centraliza a imagem horizontalmente */
        }
        h1 {
            margin-top: 20px;
            text-align: left;
            margin: 20px 0; /* Adiciona espaço acima e abaixo do título */
            font-size: 65px;
        }
        .main-content {
            padding: 30px;
            border-radius: 8px;
            margin-top: 20px;
            line-height: 1.6;
        }
        h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
        }
        p {
            margin-bottom: 20px;
            font-size: 35px;
            font-family: Arial;
        }
    </style>
</head>
<body>

<header>
    <img src="../assets/images/mercadoonline.png" alt="Logo Cliente-Estoque">
</header>
<div class="container main-content">
<h1>Seja Bem-Vindo ao nosso Site!</h1>
    <p>O projeto de gerenciamento de Clientes e Estoque, o <strong>‘’mercado online’’</strong>, tem como principal objetivo oferecer uma forma fácil e simples de gerenciar o estoque e os clientes, seja de um pequeno ou grande comércio.</p>

    <p>O projeto possui um sistema simples em CRUD (Create, Read, Update e Delete), onde rapidamente o usuário consegue cadastrar ou excluir seus contatos, e facilmente editar seus produtos.</p>
</div>

<?php include('navbar.php'); ?>
</body>
</html>
