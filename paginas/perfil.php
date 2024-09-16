<?php
session_start();
include_once('../config/config.php');

// Supondo que a conexão com o banco de dados já esteja estabelecida e armazenada na variável $conexao
$id = $_SESSION['id'];
$foto = $_SESSION['foto'];
// Defina o ID do usuário que você deseja buscar

$sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
$stmt = $conexao->prepare($sql);

// Bind parameters to avoid SQL injection
$stmt->bindParam(':id', $id, PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Dados do usuário
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    $row = null;
    echo "Nenhum usuário encontrado.";
}

// Formatando a data de nascimento
if (isset($row['idade']) && !empty($row['idade'])) {
    $data_nascimento = new DateTime($row['idade']);
    $data_nascimento_formatada = $data_nascimento->format('d/m/Y');
} else {
    $data_nascimento_formatada = 'Data não disponível';
}

// Traduzindo o sexo para português
$sexos = [
    'Masculino' => 'Masculino',
    'Feminino' => 'Feminino',
    'Outro' => 'Outro'
];

$sexo_formatado = isset($sexos[$row['sexo']]) ? $sexos[$row['sexo']] : 'Sexo não disponível';

if ($foto) {
    $imagem_perfil = $foto;
    // Verifica se a imagem existe na pasta
    if (file_exists("../assets/images/img_user/" . $imagem_perfil)) {
        $imagem_exibir = "../assets/images/img_user/" . $imagem_perfil;
    } else {
        // Define uma imagem padrão caso a imagem não seja encontrada na pasta
        $imagem_exibir = "../assets/images/avatar-padrao.png";
    }
} else {
    // Define uma imagem padrão caso não encontre a imagem do usuário no banco de dados
    $imagem_exibir = "../assets/images/avatar-padrao.png";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
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
        .profile-img {
            text-align: center;
        }
        .profile-img img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
        }
        .profile-info {
            margin: 20px 0;
        }
        .profile-info p {
            font-size: 18px;
            margin: 10px 0;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        .buttons a {
            text-decoration: none;
            padding: 10px 20px;
            background: #007BFF;
            color: #fff;
            border-radius: 5px;
        }
        .buttons a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($row): ?>
            <div class="profile-img">
                <img src="<?php echo htmlspecialchars($imagem_exibir); ?>" alt="Imagem de Perfil">
            </div>
            <div class="profile-info">
                <p><strong>Nome:</strong> <?php echo htmlspecialchars($row['nome']); ?></p>
                <p><strong>Sobrenome:</strong> <?php echo htmlspecialchars($row['sobrenome']); ?></p>
                <p><strong>CPF:</strong> <?php echo htmlspecialchars($row['cpf']); ?></p>
                <p><strong>Sexo:</strong> <?php echo htmlspecialchars($sexo_formatado); ?></p>
                <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($data_nascimento_formatada); ?></p>
                <p><strong>Endereço:</strong> <?php echo htmlspecialchars($row['endereco']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                <p><strong>Senha:</strong> ********</p>
                <p><strong>Telefone:</strong> <?php echo htmlspecialchars($row['telefone']); ?></p>
            </div>
        <?php else: ?>
            <p>Nenhum usuário encontrado.</p>
        <?php endif; ?>
        <div class="buttons">
            <a href="home.php">Voltar para o Início</a>
            <a href="editar-perfil.php">Alterar Dados do Perfil</a>
        </div>
    </div>
</body>
</html>
