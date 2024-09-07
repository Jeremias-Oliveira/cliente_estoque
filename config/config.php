<?php

    $dbHost = 'LocalHost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'cliente_estoque';

    $conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // if ($conexao->connect_errno) {
    //     echo "Falha ao conectar ao banco de dados: ";
    // }
    // else {
    //     echo "Conectado ao banco de dados";
    // }
?>