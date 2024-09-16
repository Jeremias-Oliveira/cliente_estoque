<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="assets/css/cadastro-layout.css">
</head>
<body>

<div class="container">

<?php
if (isset($_POST['submit'])) {
    include_once('config/config.php');

    // Coletando dados do formulário
    $nome = $_POST['name'];
    $sobrenome = $_POST['surname'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['gender'];
    $data_nasc = $_POST['date'];
    $endereco = $_POST['address'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $telefone = $_POST['phone'];

    // Criptografando a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Diretório onde a imagem será salva
    $target_dir = "assets/images/img_user/";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));

    // Verifica se um arquivo foi enviado
    if ($_FILES["profile_picture"]["error"] == UPLOAD_ERR_NO_FILE) {
        $target_file = $target_dir . "avatar-padrao.png";
        $target = "avatar-padrao.png";
        $uploadOk = 1;
    } else {
        // Gera um nome único para a imagem
        $new_file_name = uniqid('img_') . '.' . $imageFileType;
        $target_file = $target_dir . $new_file_name;
        $target = $new_file_name;

        // Verifica se o arquivo é uma imagem real
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<div class="message error">O arquivo não é uma imagem.</div>';
            $uploadOk = 0;
        }

        // Verifica se o arquivo já existe
        if (file_exists($target_file)) {
            echo '<div class="message error">Desculpe, o arquivo já existe.</div>';
            $uploadOk = 0;
        }

        // Verifica o tamanho do arquivo (limite de 5MB)
        if ($_FILES["profile_picture"]["size"] > 5000000) {
            echo '<div class="message error">Desculpe, o arquivo é muito grande.</div>';
            $uploadOk = 0;
        }

        // Permite apenas certos formatos de arquivo
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo '<div class="message error">Desculpe, apenas arquivos JPG, JPEG e PNG são permitidos.</div>';
            $uploadOk = 0;
        }

        // Verifica se $uploadOk está definido como 0 por algum erro
        if ($uploadOk == 0) {
            echo '<div class="message error">Desculpe, seu arquivo não foi enviado.</div>';
        // Se tudo estiver ok, tenta fazer o upload do arquivo
        } else {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                echo '<div class="message success">O arquivo ' . htmlspecialchars(basename($_FILES["profile_picture"]["name"])) . ' foi enviado com sucesso.</div>';
            } else {
                echo '<div class="message error">Desculpe, houve um erro ao enviar seu arquivo.</div>';
            }
        }
    }
    try {
        // Preparar a instrução SQL
        $sql = "INSERT INTO usuarios (nome, sobrenome, cpf, sexo, idade, endereco, email, senha, telefone, foto) 
                VALUES (:nome, :sobrenome, :cpf, :sexo, :data_nasc, :endereco, :email, :senha, :telefone, :imagem_perfil)";
        $stmt = $conexao->prepare($sql);

        // Vincular parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':imagem_perfil', $target);

        // Executar a consulta
        $stmt->execute();
        // Mensagem de sucesso estilizada
        echo '<div class="message success">Cadastro realizado com sucesso!</div>';
        header("Refresh: 3, index.php");
    } catch (PDOException $e) {
        // Mensagem de erro estilizada
        echo '<div class="message error">Erro: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>

    <h1>Cadastro | Usuário</h1>
    <form action="cadastro.php" method="POST" enctype="multipart/form-data">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" placeholder="Seu Nome" required><br><br>

        <label for="surname">Sobrenome:</label>
        <input type="text" id="surname" name="surname" placeholder="Seu Sobrenome" required><br><br>
        
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" placeholder="123.456.789-00" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required><br><br>
        
        <label for="gender">Sexo:</label>
        <select id="gender" name="gender" required>
            <option value="" disabled selected>Selecione o Sexo</option>
            <option value="male">Masculino</option>
            <option value="female">Feminino</option>
            <option value="other">Outro</option>
        </select><br><br>
        
        <label for="date">Data de Nascimento:</label>
        <input type="date" id="date" name="date" placeholder="Sua data de Nascimento" required><br><br>
        
        <label for="address">Endereço:</label>
        <textarea id="address" name="address" placeholder="Seu Endereço Completo" rows="3" cols="50" required></textarea><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="seuemail@example.com" required><br><br>
        
        <label for="password">Senha:</label>
        <input type="password" id="senha" name="senha" placeholder="Sua Senha" required><br><br>
        
        <label for="phone">Telefone:</label>
        <input type="tel" id="phone" name="phone" placeholder="(11) 98765-4321" pattern="\(\d{2}\) \d{5}-\d{4}" required><br><br>
        
        <label for="profile_picture">Imagem de Perfil:</label>
        <input type="file" id="profile_picture" name="profile_picture" accept=".png, .jpg, .jpeg"><br><br>
        
        <input type="submit" name="submit" value="Cadastrar">
    </form>
    <p>Já tem uma conta? <a href="index.php">Volte para a página de login</a>.</p>
</div>
</body>
</html>
