<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) 
{
    include_once('../config/config.php');
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Prepare a SQL statement
        $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
        $stmt = $conexao->prepare($sql);

        // Bind parameters to avoid SQL injection
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);

        // Execute the statement
        $stmt->execute();

        // Fetch the number of rows returned
        if ($stmt->rowCount() < 1) {
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            header('Location: ../index.php');
        } else {
            $_SESSION['email'] = $email;
            $_SESSION['senha'] = $senha;
            header('Location: ../paginas/home.php');
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
