<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login-layout.css">
</head>
<body>
<div class="container">
	<h1>Login</h1>
	<form action="action/testelogin.php" method="post">
		<input type="email" id="email" name="email" placeholder="Digite seu Email" required>
		<br>
		<input type="password" id="senha" name="senha" placeholder="Digite sua Senha" required>
		<br>
		<input type="submit" name="submit" value="Acessar">
	</form>
	<p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a>.</p>
</div>
<div class="img">
	<img src="assets/images/eco-shopping-animate.svg">
</div>
</body>
</html>
