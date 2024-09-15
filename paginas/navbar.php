<?php
// Supondo que você já tenha uma conexão com o banco de dados
include_once('../config/config.php');

// Verifica se a imagem de perfil foi encontrada
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

<link rel="stylesheet" href="../assets/css/navbar-layout.css">
<nav class="menu-lateral">
        
        <div class="btn-exp">
            <i class="bi bi-list" id="btn-expandir"></i>
        </div>
       
        <ul>
            <li class="item-perfil">
                <a href="#">
                    <span class="img-perfil"><img src="<?php echo $imagem_exibir; ?>" alt="Imagem de Perfil"></span>
                    <span class="txt-perfil"><?php  echo $nome; ?></span>
                </a>
            </li>
            <a href="cadastro-cliente.php"><span class="cadastrar"><i class="bi bi-plus-square"></i></span></a>
            <li class="item-menu ativo">
                <a href="#">
                    <span class="icon"><i class="bi bi-people"></i></span>
                    <span class="txt-link">Cliente</span>
                </a>
            </li>
            <a href="cadastro-estoque.php"><span class="cadastrar"><i class="bi bi-plus-square"></i></span></a>
            <li class="item-menu">
                <a href="#">
                    <span class="icon"><i class="bi bi-box-seam"></i></span>
                    <span class="txt-link">Estoque</span>
                </a>
            </li>
            
            <div class="sair">
                <hr>
                <li class="item-sair">
                    <a href="../action/sair.php">
                        <span class="icon"><i class="bi bi-box-arrow-right"></i></span>
                        <span class="txt-link">sair</span>
                    </a>
                </li>
            </div>
        </ul>
</nav>