<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Cliente</title>
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

// Verifica se o ID do cliente foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: cliente.php');
    exit();
}

$id = $_GET['id'];

// Buscar os dados do cliente
$stmt = $conexao->prepare("SELECT * FROM clientes WHERE id_clientes = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    header('Location: cliente.php');
    exit();
}

if (isset($_POST['submit'])) {
    $nome = $_POST['full_name'];
    $cpf = $_POST['cpf'];
    $sexo = $_POST['gender'];
    $data_nasc = $_POST['date'];
    $endereco = $_POST['address'];
    $email = $_POST['email'];
    $telefone = $_POST['phone'];

    try {
        // Prepare a SQL statement
        $sql = "UPDATE clientes 
                SET nome_completo = :nome, cpf = :cpf, sexo = :sexo, idade = :data_nasc, endereco = :endereco, email = :email, telefone = :telefone 
                WHERE id_clientes = :id";
        $stmt = $conexao->prepare($sql);

        // Bind parameters to avoid SQL injection
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':id', $id);

        // Execute the statement
        $stmt->execute();

        echo '<div class="message success">Cliente atualizado com sucesso!</div>';
        header("Refresh: 3; url=cliente.php");
    } catch (PDOException $e) {
        echo '<div class="message error">Erro ao atualizar o cliente: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}
?>
    <h1>Editar Cliente</h1>
    <form action="editar_cliente.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
        <label for="full_name">Nome Completo:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($cliente['nome_completo']); ?>" required><br><br>
        
        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cliente['cpf']); ?>" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required><br><br>
        
        <label for="gender">Sexo:</label>
        <select id="gender" name="gender" required>
            <option value="" disabled>Selecione o Sexo</option>
            <option value="Masculino" <?php echo $cliente['sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
            <option value="Feminino" <?php echo $cliente['sexo'] == 'Feminino' ? 'selected' : ''; ?>>Feminino</option>
            <option value="Outro" <?php echo $cliente['sexo'] == 'Outro' ? 'selected' : ''; ?>>Outro</option>
        </select><br><br>
        
        <label for="date">Data de Nascimento:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($cliente['idade']); ?>" required><br><br>
        
        <label for="address">Endereço:</label>
        <textarea id="address" name="address" rows="3" cols="50" required><?php echo htmlspecialchars($cliente['endereco']); ?></textarea><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required><br><br>
        
        <label for="phone">Telefone:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" pattern="\(\d{2}\) \d{5}-\d{4}" required><br><br>
        
        <input type="submit" name="submit" value="Atualizar">
        
        <div class="form-group">
            <a href="cliente.php">Voltar para a Lista de Clientes</a>
        </div>
    </form>
</div>
</body>
</html>
