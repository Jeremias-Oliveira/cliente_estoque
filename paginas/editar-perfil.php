<?php
session_start();
include_once('../config/config.php');

// Supondo que a conexão com o banco de dados já esteja estabelecida e armazenada na variável $conexao
$id = $_SESSION['id'];

// Definir mensagem de sucesso ou erro
$mensagem = '';

// Buscar dados atuais do usuário
$sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $row = null;
    $mensagem = "Nenhum usuário encontrado.";
}

// Processar atualização dos dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
    $sobrenome = filter_var($_POST['sobrenome'], FILTER_SANITIZE_STRING);
    $cpf = filter_var($_POST['cpf'], FILTER_SANITIZE_STRING);
    $sexo = filter_var($_POST['sexo'], FILTER_SANITIZE_STRING);
    $idade = $_POST['idade'];
    $endereco = filter_var($_POST['endereco'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_STRING);

    // Atualizar dados no banco de dados
    $sql = "UPDATE usuarios SET nome = :nome, sobrenome = :sobrenome, cpf = :cpf, sexo = :sexo, idade = :idade, endereco = :endereco, email = :email, telefone = :telefone WHERE id_usuario = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':sobrenome', $sobrenome);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':sexo', $sexo);
    $stmt->bindParam(':idade', $idade);
    $stmt->bindParam(':endereco', $endereco);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $mensagem = "Dados atualizados com sucesso!";
    } else {
        $mensagem = "Erro ao atualizar os dados.";
    }

    // Atualizar a senha se fornecida
    if (!empty($_POST['nova_senha']) && !empty($_POST['confirmar_senha'])) {
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        if ($nova_senha === $confirmar_senha) {
            $hashedPassword = password_hash($nova_senha, PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios SET senha = :senha WHERE id_usuario = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':senha', $hashedPassword);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $mensagem = "Senha atualizada com sucesso!";
            } else {
                $mensagem = "Erro ao atualizar a senha.";
            }
        } else {
            $mensagem = "As senhas não correspondem.";
        }
    }

    // Processar upload da imagem de perfil
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../assets/images/img_user/";
        $fileName = basename($_FILES['foto']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Verificar se o arquivo é uma imagem
        $check = getimagesize($_FILES['foto']['tmp_name']);
        if ($check !== false) {
            // Validar o tamanho do arquivo
            if ($_FILES['foto']['size'] <= 2000000) { // 2MB máximo
                // Verificar o tipo de arquivo
                $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
                if (in_array($fileType, $allowedTypes)) {
                    // Mover o arquivo para o diretório de destino
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                        // Atualizar a imagem no banco de dados
                        $sql = "UPDATE usuarios SET foto = :foto WHERE id_usuario = :id";
                        $stmt = $conexao->prepare($sql);
                        $stmt->bindParam(':foto', $fileName);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                        if ($stmt->execute()) {
                            $mensagem = "Dados e imagem atualizados com sucesso!";
                        } else {
                            $mensagem = "Erro ao atualizar a imagem.";
                        }
                    } else {
                        $mensagem = "Erro ao mover o arquivo.";
                    }
                } else {
                    $mensagem = "Tipo de arquivo não permitido.";
                }
            } else {
                $mensagem = "O arquivo é muito grande.";
            }
        } else {
            $mensagem = "O arquivo não é uma imagem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            margin: 20px auto;
            width: 50%;
            overflow: hidden;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group input[type="file"] {
            padding: 0;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        .buttons a, .buttons button {
            text-decoration: none;
            padding: 10px 20px;
            background: #007BFF;
            color: #fff;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .buttons a:hover, .buttons button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Perfil</h1>
        <?php if ($mensagem): ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
        <?php if ($row): ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" id="sobrenome" name="sobrenome" value="<?php echo htmlspecialchars($row['sobrenome']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($row['cpf']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" required>
                        <option value="Masculino" <?php if ($row['sexo'] === 'Masculino') echo 'selected'; ?>>Masculino</option>
                        <option value="Feminino" <?php if ($row['sexo'] === 'Feminino') echo 'selected'; ?>>Feminino</option>
                        <option value="Outro" <?php if ($row['sexo'] === 'Outro') echo 'selected'; ?>>Outro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="idade">Data de Nascimento</label>
                    <input type="date" id="idade" name="idade" value="<?php echo htmlspecialchars($row['idade']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($row['endereco']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($row['telefone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha">
                </div>
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha">
                </div>
                <div class="form-group">
                    <label for="foto">Imagem de Perfil</label>
                    <input type="file" id="foto" name="foto">
                </div>
                <div class="buttons">
                    <button type="submit">Salvar Alterações</button>
                    <a href="perfil.php">Voltar para o Perfil</a>
                </div>
            </form>
        <?php else: ?>
            <p>Nenhum usuário encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
