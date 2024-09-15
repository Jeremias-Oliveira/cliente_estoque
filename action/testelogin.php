<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) 
{
    include_once('../config/config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Prepare a SQL statement
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conexao->prepare($sql);

        // Bind parameters to avoid SQL injection
        $stmt->bindParam(':email', $email);

        // Execute the statement
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            $_SESSION['nome'] =  $user['nome'];
            $_SESSION['id'] = $user['id_usuario'];
            $_SESSION['foto'] =  $user['foto'];


            header('Location: ../paginas/home.php');
        } else {
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            unset($_SESSION['nome']);
            unset($_SESSION['id']);
            unset($_SESSION['foto']);
            header('Location: ../index.php');
        }
    } catch (PDOException $e) {
        // Handle potential exceptions
        echo "Erro: " . $e->getMessage();
        header('Location: ../index.php');
    }
} else {
    header('Location: ../index.php');
}
?>
