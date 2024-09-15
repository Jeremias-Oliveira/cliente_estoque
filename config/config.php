<?php

    $dbHost = 'localhost'; // Ajustado para o formato padrão
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'cliente_estoque';

    try {
        $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8";
        $conexao = new PDO($dsn, $dbUsername, $dbPassword);
        // Definindo o modo de erro do PDO para exceções
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Conectado ao banco de dados";
    } catch (PDOException $e) {
        // echo "Falha ao conectar ao banco de dados: " . $e->getMessage();
    }

?>
