<link rel="stylesheet" href="../assets/css/navbar-layout.css">
<nav class="menu-lateral">
        
        <div class="btn-exp">
            <i class="bi bi-list" id="btn-expandir"></i>
        </div>
       
        <ul>
            <li class="item-perfil">
                <a href="#">
                    <span class="img-perfil"><i class="bi bi-person-circle"></i></span>
                    <span class="txt-perfil"><?php  echo $logado; ?></span>
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