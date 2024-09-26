<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Estoque</title>
    <link rel="stylesheet" href="../assets/css/cadastro-layout.css">
</head>
<body>
<div class="container">
<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['senha']) || !isset($_SESSION['nome']) || !isset($_SESSION['id'])) {
    // Se não estiver ativo, destrói a sessão e redireciona para a página inicial
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    unset($_SESSION['nome']);
    unset($_SESSION['id']);
    unset($_SESSION['foto']);
    header('Location: ../index.php');
    exit();
}

include_once('../config/config.php');

// Verifica se o ID do produto foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: estoque.php');
    exit();
}

$id = $_GET['id'];

// Buscar os dados do produto
$stmt = $conexao->prepare("SELECT * FROM estoque WHERE id_estoque = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    header('Location: estoque.php');
    exit();
}

if (isset($_POST['submit'])) {
    $nome = $_POST['full_name'];
    $categoria = $_POST['categ'];
    $descricao = $_POST['desc'];
    $quantidade = $_POST['quant'];
    $preco = $_POST['preco'];
    $movimentacao = $_POST['moviment'];
    $data = $_POST['date'];
    $fornecedor = $_POST['fornec'];

    try {
        // Prepare a SQL statement
        $sql = "UPDATE estoque 
                SET nome_produto = :nome, categoria = :categoria, descricao = :descricao, quantidade = :quantidade, 
                    movimentacao = :movimentacao, preco = :preco, data = :data, fornecedor = :fornecedor 
                WHERE id_estoque = :id";
        $stmt = $conexao->prepare($sql);

        // Bind parameters to avoid SQL injection
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':movimentacao', $movimentacao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':fornecedor', $fornecedor);
        $stmt->bindParam(':id', $id);

        // Execute the statement
        $stmt->execute();

        echo '<div class="message success">Produto atualizado com sucesso!</div>';
        header("Refresh: 3; url=estoque.php");
    } catch (PDOException $e) {
        echo '<div class="message error">Erro ao atualizar o produto: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>
    <h1>Editar Produto no Estoque</h1>
    <form action="editar_estoque.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
        <label for="full_name">Nome do Produto:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" required><br><br>
        
        <label for="categ">Categoria:</label>
        <input type="text" id="categ" name="categ" value="<?php echo htmlspecialchars($produto['categoria']); ?>" required><br><br>
        
        <label for="desc">Descrição:</label>
        <textarea id="desc" name="desc" rows="4" cols="50" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea><br><br>
        
        <label for="quant">Quantidade:</label>
        <input type="number" step="1" min="1" id="quant" name="quant" value="<?php echo htmlspecialchars($produto['quantidade']); ?>" required><br><br>
        
        <label for="preco">Preço:</label>
        <input type="number" step="0.01" min="0" id="preco" name="preco" value="<?php echo htmlspecialchars($produto['preco']); ?>" required><br><br>
        
        <label for="moviment">Movimentação:</label>
        <select id="moviment" name="moviment" required>
            <option value="" disabled>Selecione a Movimentação</option>
            <option value="Entrada" <?php echo $produto['movimentacao'] == 'Entrada' ? 'selected' : ''; ?>>Entrada</option>
            <option value="Saída" <?php echo $produto['movimentacao'] == 'Saída' ? 'selected' : ''; ?>>Saída</option>
        </select><br><br>
        
        <label for="date">Data da Movimentação:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($produto['data']); ?>" required><br><br>
        
        <label for="fornec">Fornecedor:</label>
        <input type="text" id="fornec" name="fornec" value="<?php echo htmlspecialchars($produto['fornecedor']); ?>" required><br><br>
        
        <input type="submit" name="submit" value="Atualizar">

        <div class="form-group">
            <a href="estoque.php">Voltar para a Lista de Estoque</a>
        </div>
    </form>
</div>
</body>
</html>
