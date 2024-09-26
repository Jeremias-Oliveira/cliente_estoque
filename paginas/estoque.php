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

// Consultar dados do estoque com base na pesquisa
$stmt = $conexao->prepare("SELECT id_estoque, nome_produto, categoria, descricao, quantidade, preco, fornecedor FROM estoque WHERE nome_produto LIKE :search OR categoria LIKE :search");
$stmt->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estoque</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../assets/css/home-layout.css">
</head>
<body>

<div class="container mt-4">
<?php if (isset($_GET['message'])): ?>
    <div class="alert alert-info">
        <?php echo htmlspecialchars($_GET['message']); header("Refresh: 3, estoque.php"); ?>
    </div>
<?php endif; ?>

    <div class="row mb-3">
        <div class="col-md-12">
            <form action="estoque.php" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control" placeholder="Pesquisar produto..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="btn btn-primary ms-2">Pesquisar</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Lista de Estoque
                    <a href="cadastro-estoque.php" class="btn btn-primary float-end">Adicionar Estoque</a>
                    </h4>
                </div>
            
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome Produto</th>
                                <th>Categoria</th>
                                <th>Descrição</th>
                                <th>Quantidade</th>
                                <th>Preço</th>
                                <th>Fornecedor</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clientes)): ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cliente['id_estoque']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['nome_produto']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['categoria']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['descricao']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['quantidade']); ?></td>
                                        <td>
                                            <?php
                                            echo "R$ " . number_format($cliente['preco'], 2, ',', '.');
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($cliente['fornecedor']); ?></td>
                                        <td>
                                            <a href="editar_estoque.php?id=<?php echo urlencode($cliente['id_estoque']); ?>" class="btn btn-success btn-sm">Editar</a>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#movimentacaoModal<?php echo $cliente['id_estoque']; ?>">Movimentação</button>
                                            <form action="delete_estoque.php" method="POST" class="d-inline">
                                                <input type="hidden" name="id_estoque" value="<?php echo htmlspecialchars($cliente['id_estoque']); ?>">
                                                <button type="submit" name="delete_estoque" class="btn btn-danger btn-sm">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="movimentacaoModal<?php echo $cliente['id_estoque']; ?>" tabindex="-1" aria-labelledby="movimentacaoModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="movimentacaoModalLabel">Movimentação do Estoque</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="movimentacao_estoque.php" method="POST">
                                                        <input type="hidden" name="id_estoque" value="<?php echo htmlspecialchars($cliente['id_estoque']); ?>">
                                                        <div class="mb-3">
                                                            <label for="adicionar" class="form-label">Adicionar Quantidade</label>
                                                            <input type="number" name="adicionar" class="form-control" min="0" placeholder="Quantidade a adicionar">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="retirar" class="form-label">Retirar Quantidade</label>
                                                            <input type="number" name="retirar" class="form-control" min="0" placeholder="Quantidade a retirar">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Nenhum produto encontrado</td>
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
