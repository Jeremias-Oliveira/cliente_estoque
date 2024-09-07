<?php

    if(isset($_POST['submit']))
    {
        include_once('config/config.php');

        $nome = $_POST['full_name'];
        $cpf = $_POST['cpf'];
        $sexo = $_POST['gender'];
        $data_nasc = $_POST['date'];
        $endereco = $_POST['address'];
        $email = $_POST['email'];
        $senha = $_POST['senha'];
        $telefone = $_POST['phone'];

        $result = mysqli_query($conexao, "INSERT INTO Usuarios(nome_completo,cpf,sexo,idade,endereco,email,senha,telefone) VALUES ('$nome','$cpf','$sexo','$data_nasc','$endereco','$email','$senha','$telefone')");
    }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/cadastro-layout.css">
</head>
<body>
<div class="container">
	<h1>Cadastro</h1>
	<form action="cadastro.php" method="POST">
        <label for="full_name">Nome Completo:</label>
        <input type="text" id="full_name" name="full_name" placeholder="Seu Nome Completo" required><br><br>
        
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" placeholder="123.456.789-00" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required><br><br>
        
        <label for="gender">Sexo:</label>
        <select id="gender" name="gender" required>
            <option value="" disabled selected>Selecione o Sexo</option>
            <option value="male">Masculino</option>
            <option value="female">Feminino</option>
            <option value="other">Outro</option>
        </select><br><br>
        
        <label for="date">Idade:</label>
        <input type="date" id="date" name="date" placeholder="Sua data de Nascimento" required><br><br>
        
        <label for="address">Endereço:</label>
        <textarea id="address" name="address" placeholder="Seu Endereço Completo" rows="3" cols="50" required></textarea><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="seuemail@example.com" required><br><br>
        
        <label for="password">Senha:</label>
        <input type="password" id="senha" name="senha" placeholder="Sua Senha" required><br><br>
        
        <label for="phone">Telefone:</label>
        <input type="tel" id="phone" name="phone" placeholder="(11) 98765-4321" pattern="\(\d{2}\) \d{5}-\d{4}" required><br><br>
        
        <input type="submit" name="submit" value="Cadastrar">
    	</form>
	<p>Já tem uma conta? <a href="index.php">Volte para a página de login</a>.</p>
</div>
</body>
</html>
