<?php
session_start();
//print_r($_SESSION);

    if ((!isset($_SESSION['email']) == true ) and (!isset($_SESSION['senha']) == true)) {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: ../index.php');
    }
    $logado = $_SESSION['email'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/home-layout.css">
</head>
<body>
    <nav class="menu-lateral">
        
        <div class="btn-exp">
            <i class="bi bi-list" id="btn-expandir"></i>
        </div>
        <ul>
            <li class="item-menu ativo">
                <a href="#">
                    <span class="icon"><i class="bi bi-people"></i></span>
                    <span class="txt-link">Cliente</span>
                </a>
            </li>
            <li class="item-menu">
                <a href="#">
                    <span class="icon"><i class="bi bi-box-seam"></i></span>
                    <span class="txt-link">Estoque</span>
                </a>
            </li>
            <div class="sair">
                <hr>
                <li class="item-sair">
                    <a href="../action/sair.php">
                        <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                        <span class="txt-link">sair</span>
                    </a>
                </li>
            </div>
        </ul>
    </nav>
    <script src="../assets/js/menu-lateral.js"></script>
</body>
</html>