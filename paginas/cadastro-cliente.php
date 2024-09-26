<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes</title>
    <link rel="stylesheet" href="../assets/css/cadastro-layout.css">
</head>
<body>
<div class="container">

<?php
if (isset($_POST['submit'])) {
    include_once('../config/config.php');

    $nome = $_POST['full_name'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['gender'];
    $data_nasc = $_POST['date'];
    $endereco = $_POST['address'];
    $email = $_POST['email'];
    $telefone = $_POST['phone'];

    try {
        // Prepare a SQL statement
        $sql = "INSERT INTO clientes (nome_completo, cpf, sexo, idade, endereco, email, telefone) 
                VALUES (:nome, :cpf, :sexo, :data_nasc, :endereco, :email, :telefone)";
        $stmt = $conexao->prepare($sql);

        // Bind parameters to avoid SQL injection
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);

        // Execute the statement
        $stmt->execute();

        echo '<div class="message success">Cliente adicionado com sucesso!</div>';
        header("Refresh: 3, cliente.php");
    } catch (PDOException $e) {
        // Mensagem de erro estilizada
        echo '<div class="message error">Erro ao adicionar o cliente '. '</div>';
    }
}
?>

	<h1>Cadastro | Clientes</h1>
	<form action="cadastro-cliente.php" method="POST">
        <label for="full_name">Nome Completo:</label>
        <input type="text" id="full_name" name="full_name" placeholder="Nome Completo" required><br><br>
        
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" placeholder="123.456.789-00" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required><br><br>
        
        <label for="gender">Sexo:</label>
        <select id="gender" name="gender" required>
            <option value="" disabled selected>Selecione o Sexo</option>
            <option value="Masculino">Masculino</option>
            <option value="Feminino">Feminino</option>
            <option value="Outro">Outro</option>
        </select><br><br>
        
        <label for="date">Data de Nascimento:</label>
        <input type="date" id="date" name="date" placeholder="Data de Nascimento" required><br><br>
        
        <label for="address">Endereço:</label>
        <textarea id="address" name="address" placeholder="Endereço Completo" rows="3" cols="50" required></textarea><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="seuemail@example.com" required><br><br>
        
        <label for="phone">Telefone:</label>
        <input type="tel" id="phone" name="phone" placeholder="(11) 98765-4321" pattern="\(\d{2}\) \d{5}-\d{4}" required><br><br>
        
        <input type="submit" name="submit" value="Cadastrar">

        <div class="form-group">
                <a href="cliente.php">Voltar para a Lista de Clientes</a>
        </div>
    	</form>
</div>
</body>
</html>
