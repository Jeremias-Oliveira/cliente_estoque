<?php
session_start();
//print_r($_SESSION);

    if ((!isset($_SESSION['email']) == true ) and (!isset($_SESSION['senha']) == true)) {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        header('Location: ../index.php');
    }
    $logado = $_SESSION['email'];

    // E-mail a ser verificado
$email = '$logado';

// Prepara a consulta SQL para buscar o nome do banco de dados
$sql = "SELECT cliente_estoque FROM usuarios.email WHERE email = '$email'";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("s", '$email');

// Executa a consulta
$stmt->execute();
$stmt->store_result();

// Verifica se o banco de dados existe
if ($stmt->num_rows > 0) {
    echo "O e-mail corresponde ao nome de um banco de dados.";
} else {
    echo "O e-mail não corresponde ao nome de nenhum banco de dados.";
}

// Fecha a conexão
$stmt->close();
$conn->close();

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
            <li class="item-perfil">
                <a href="#">
                    <span class="img-perfil"><i class="bi bi-person-circle"></i></span>
                    <span class="txt-perfil"><?php  echo $logado; ?></span>
                </a>
            </li>
            <a href="cadastro-cliente.php"><span class="cadastrar"><i class="bi bi-plus-square"></i></span></a>
            <li class="item-menu ativo">
                <a href="#">
                    <span class="icon"><i class="bi bi-people"></i></span>
                    <span class="txt-link">Cliente</span>
                </a>
            </li>
            <a href="cadastro-estoque.php"><span class="cadastrar"><i class="bi bi-plus-square"></i></span></a>
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

    <!-- Conteúdo -->
    <!--<table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">CPF</th>
                <th scope="col">Sexo</th>
                <th scope="col">Data de Nascimento</th>
                <th scope="col">Endereço</th>
                <th scope="col">Email</th>
                <th scope="col">Telefone</th>
                <th scope="col">Telefone</th>
                <th scope="col">...</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
</table>-->
    <script src="../assets/js/menu-lateral.js"></script>
</body>
</html>