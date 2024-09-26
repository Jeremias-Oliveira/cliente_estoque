<?php
session_start();

// Verifica se a sessão está ativa
if (!isset($_SESSION['email']) || !isset($_SESSION['senha']) || !isset($_SESSION['nome']) || !isset($_SESSION['id'])) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    unset($_SESSION['nome']);
    unset($_SESSION['id']);
    unset($_SESSION['foto']);

    header('Location: ../index.php');
    exit();
}

$id = $_SESSION['id'];
$foto = $_SESSION['foto'];
$nome = $_SESSION['nome'];

// Inclua o arquivo de configuração para a conexão com o banco de dados
include_once('../config/config.php');

// Inicializa a variável de pesquisa
$searchTerm = '';
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Consultar dados dos clientes com base na pesquisa
$stmt = $conexao->prepare("
    SELECT id_clientes, nome_completo, cpf, sexo, idade, endereco, email, telefone 
    FROM clientes 
    WHERE nome_completo LIKE :search 
       OR cpf LIKE :search 
       OR email LIKE :search 
       OR sexo LIKE :search 
       OR idade LIKE :search 
       OR endereco LIKE :search 
       OR telefone LIKE :search
");
$stmt->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/home-layout.css">
</head>
<body>

<div class="container mt-4">
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-info">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <?php header("Refresh: 3, cliente.php"); ?>
        </div>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-md-12">
            <form action="cliente.php" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar cliente..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-primary ms-2">Pesquisar</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Lista de Clientes
                    <a href="cadastro-cliente.php" class="btn btn-primary float-end">Adicionar Cliente</a>
                    </h4>
                </div>
            
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Sexo</th>
                                <th>Data de Nascimento</th>
                                <th>Endereço</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clientes)): ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cliente['id_clientes']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['nome_completo']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['cpf']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['sexo']); ?></td>
                                        <td>
                                            <?php
                                            $dataNascimento = new DateTime($cliente['idade']);
                                            echo $dataNascimento->format('d/m/Y');
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($cliente['endereco']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
                                        <td>
                                            <a href="editar_cliente.php?id=<?php echo urlencode($cliente['id_clientes']); ?>" class="btn btn-success btn-sm">Editar</a>
                                            <form action="delete_cliente.php" method="POST" class="d-inline">
                                                <input type="hidden" name="id_clientes" value="<?php echo htmlspecialchars($cliente['id_clientes']); ?>">
                                                <button type="submit" name="delete_cliente" class="btn btn-danger btn-sm">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">Nenhum cliente encontrado</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('navbar.php'); ?>
</body>
</html>
