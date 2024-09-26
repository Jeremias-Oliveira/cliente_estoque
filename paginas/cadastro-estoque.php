<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Estoque</title>
    <link rel="stylesheet" href="../assets/css/cadastro-layout.css">
</head>
<body>
<div class="container">

<?php
if (isset($_POST['submit'])) {
    include_once('../config/config.php');

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
        $sql = "INSERT INTO estoque (nome_produto, categoria, descricao, quantidade, data, movimentacao, preco, fornecedor) 
                VALUES (:nome, :categoria, :descricao, :quantidade, :data, :movimentacao, :preco, :fornecedor)";
        $stmt = $conexao->prepare($sql);

        // Bind parameters to avoid SQL injection
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':movimentacao', $movimentacao);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':fornecedor', $fornecedor);

        // Execute the statement
        $stmt->execute();

        echo '<div class="message success">Produto adicionado com sucesso!</div>';
        header("Refresh: 3, estoque.php");
    } catch (PDOException $e) {
        // Mensagem de erro estilizada
        echo '<div class="message error">Erro ao adicionar o produto '. '</div>';
    }
}
?>

	<h1>Cadastro | Estoque</h1>
	<form action="cadastro-estoque.php" method="POST">
        <label for="full_name">Nome do Produto:</label>
        <input type="text" id="full_name" name="full_name" placeholder="Nome do Produto" required><br><br>
        
        <label for="categ">Categoria:</label>
        <input type="text" id="categ" name="categ" placeholder="Categoria do Produto" required><br><br>
        
        <label for="desc">Descrição:</label>
        <textarea id="desc" name="desc" placeholder="Descrição do Produto" rows="4" cols="50" required></textarea><br><br>
        
        <label for="quant">Quantidade:</label>
        <input type="number" step="1" min="1" id="quant" name="quant" placeholder="Quantidade do Produto" required><br><br>
        
        <label for="preco">Preço:</label>
        <input type="number" step="0.01" min="0" id="preco" name="preco" placeholder="Preço do  Produto" required><br><br>
        
        <label for="fornec">Fornecedor:</label>
        <input type="text" id="fornec" name="fornec" placeholder="Fornecedor do Produto " required><br><br>
        
        <input type="submit" name="submit" value="Cadastrar">

        <div class="form-group">
                <a href="estoque.php">Voltar para a Lista de Estoque</a>
        </div>
    	</form>
</div>
</body>
</html>
