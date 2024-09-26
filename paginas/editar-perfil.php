<?php
session_start();
include_once('../config/config.php');

try {
    // Verifica se a conexão com o banco de dados está estabelecida
    if (!isset($conexao)) {
        throw new Exception('Conexão com o banco de dados não estabelecida.');
    }

    $id = $_SESSION['id'];

    // Define mensagem para sucesso ou erro
    $mensagem = '';

    // Busca os dados do usuário atual
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

    // Processa a submissão do formulário
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
        $sobrenome = filter_var($_POST['sobrenome'], FILTER_SANITIZE_STRING);
        $cpf = filter_var($_POST['cpf'], FILTER_SANITIZE_STRING);
        $sexo = filter_var($_POST['sexo'], FILTER_SANITIZE_STRING);
        $idade = $_POST['idade'];
        $endereco = filter_var($_POST['endereco'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $telefone = filter_var($_POST['telefone'], FILTER_SANITIZE_STRING);

        // Atualiza os dados do usuário
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

        // Atualiza a senha se fornecida
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

        // Processa o upload da imagem de perfil
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "../assets/images/img_user/";
            $fileName = basename($_FILES['foto']['name']);
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Gera um novo nome de arquivo único
            $newFileName = uniqid('img_') . '.' . $fileType;
            $targetFilePath = $targetDir . $newFileName;

            // Verifica se o arquivo é uma imagem
            $check = getimagesize($_FILES['foto']['tmp_name']);
            if ($check !== false) {
                // Valida o tamanho do arquivo
                if ($_FILES['foto']['size'] <= 2000000) { // 2MB max
                    // Valida o tipo de arquivo
                    $allowedTypes = array('jpg', 'jpeg', 'png');
                    if (in_array($fileType, $allowedTypes)) {
                        // Remove a imagem antiga se existir
                        if ($row['foto'] && $row['foto'] !== 'avatar-padrao.png') {
                            $oldFilePath = $targetDir . $row['foto'];
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }

                        // Move o arquivo para o diretório de destino
                        if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFilePath)) {
                            // Atualiza a imagem no banco de dados
                            $sql = "UPDATE usuarios SET foto = :foto WHERE id_usuario = :id";
                            $stmt = $conexao->prepare($sql);
                            $stmt->bindParam(':foto', $newFileName);
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                            if ($stmt->execute()) {
                                $mensagem = "Dados e imagem atualizados com sucesso! Relogue para Atualizar.";
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
} catch (Exception $e) {
    $mensagem = "Erro: " . $e->getMessage();
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
            background-color: #f4f4f9;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 130%;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 20px;
            margin: 30px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Ajuste a largura máxima conforme necessário */
            width: 100%;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
            text-align: left; /* Alinha os labels e campos à esquerda */
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="date"],
        input[type="tel"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        textarea {
            resize: none; /* Permite redimensionar a textarea verticalmente */
        }

        input[type="submit"] {
            background-color: #333333;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 10px;
            width: 100%;
            font-weight: bold;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #111111;
            transition: 0.5s;
            border: 1px black solid;
        }

        p {
            margin-top: 0;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Estilo das mensagens */
        .message {
            padding: 20px;
            border-radius: 5px;
            width: 558px;
            text-align: center;
            font-size: 18px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Editar Perfil</h1>
    <?php if (!empty($mensagem)): ?>
        <div class="message <?= strpos($mensagem, 'Erro') !== false ? 'error' : 'success' ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>
    <?php endif; ?>

    <form action="editar-perfil.php" method="POST" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" required><br><br>

        <label for="sobrenome">Sobrenome:</label>
        <input type="text" id="sobrenome" name="sobrenome" value="<?= htmlspecialchars($row['sobrenome']) ?>" required><br><br>
        
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($row['cpf']) ?>" required><br><br>
        
        <label for="sexo">Sexo:</label>
        <select id="sexo" name="sexo" required>
            <option value="Masculino" <?= $row['sexo'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="Feminino" <?= $row['sexo'] === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
            <option value="Outro" <?= $row['sexo'] === 'Outro' ? 'selected' : '' ?>>Outro</option>
        </select><br><br>
        
        <label for="idade">Data de Nascimento:</label>
        <input type="date" id="idade" name="idade" value="<?= htmlspecialchars($row['idade']) ?>" required><br><br>

        <label for="endereco">Endereço:</label>
        <textarea id="endereco" name="endereco" rows="3" cols="50" required><?= htmlspecialchars($row['endereco']) ?></textarea><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required><br><br>
        
        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($row['telefone']) ?>" required><br><br>
        
        <label for="nova_senha">Nova Senha:</label>
        <input type="password" id="nova_senha" name="nova_senha" placeholder="Nova Senha"><br><br>
        
        <label for="confirmar_senha">Confirmar Senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirmar Senha"><br><br>
        
        <label for="foto">Imagem de Perfil:</label>
        <input type="file" id="foto" name="foto" accept=".png, .jpg, .jpeg"><br><br>

        <input type="submit" value="Atualizar">
    </form>

    <p><a href="perfil.php">Voltar para o Perfil</a></p>
</div>
</body>
</html>
