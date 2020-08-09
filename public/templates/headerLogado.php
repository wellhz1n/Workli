<?php
// @session_start();
?>
<nav class="navbar navbar-expand-lg NavbarGreen">
  <a class="navbar-brand" href="">
    <img style="height: 90px;width: 130px;" src="../Logo/Logo1.png" />

  </a>
  <button class="navbar-toggler text-black " type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon">
      <i class="fas fa-bars" style="color:#218838; font-size:28px;"></i>
    </span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto" id="menuHeader">

      <?php
      switch (Logado()[1]) {
          // USER Cliente
          #region Cliente
        case "0":
      ?>
          <li class="nav-item ">
            <a class="nav-link" id='home' href="?page=home">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tiposervicolist" href="?page=tiposervicolist">Serviços</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Perguntas Frequentes</a>
          </li>
        <?php
          break;
          // user FUNCIONARIO
          #region Funcionario
        case "1":
        ?>
          <li class="nav-item ">
            <a class="nav-link" id="home" href="?page=funchome">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tiposervicolist" href="?page=tiposervicolist">Serviços</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Linha do Tempo</a>
          </li>
        <?php
          break;
          #endregion
          //  USER ADM 
          #region ADM
        case "2":
        ?>
          <li class="nav-item ">
            <a class="nav-link" id='home' href="?page=admhome">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id='admpaineldecontrole' href="?page=admpaineldecontrole">Painel De Controle</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id='admcadastros' href="?page=admcadastros">Cadastros</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="#">Analize</a>
          </li>
        <?php
          break;
          #endregion
          //  USER DESENVOLVEDOR
          #region DeV
        case "3":
        ?>
          <li class="nav-item ">
            <a class="nav-link" id="home" href="?page=proghome">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Sql</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="">Acessos</a>
          </li>
      <?php
          break;
          #endregion
      }
      ?>


    </ul>
    <!-- NOTIFICAÇÔES -->
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link " style="display: flex;align-items: center;" href="#" role="button" id="dropNotificacoes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="notifyredBall">9<sup>+</sup></span>
          <i class="fas fa-bell" style="font-size: larger;"></i>
        </a>
        <div style="width: 28vw;min-width:350px; height: 300px;" class="dropdown-menu DropMenuCelular dropdown-menu-right dropdown-info" id="navbarDropdownNotify" aria-labelledby="dropNotificacoes">
          <div style="height: 300px;" class=" linkCor">
            <div class="col-12 " style="display: flex;width: 100%;justify-content: space-between;">
              <h6>Notificações</h6>
              <a style=""><i class="fas fa-external-link-alt"></i></a>
            </div>
            <div class="dropdown-divider" style="margin-bottom: 0px;"></div>
            <div class="row" style="height: 250px;width: 100%;margin: 0px;">
              <div class="col-12 notificacoesScrool">

                <div class="dataChatDiv"><span class="dataChatDivTexto">Hoje</span></div>

                <wm-notify :tipo="3" titulo="Teste" descricao="Agora Vai" :subtitulo="{titulo:'',descricao:''}"></wm-notify>
                <wm-notify :tipo="2" titulo="Teste2" descricao="Agora Vai2" :subtitulo="{titulo:'Vai Funcionar:',descricao:'Mais é claro'}"></wm-notify>
                <wm-notify :tipo="1" titulo="Teste2" descricao="Agora Vai2" :subtitulo="{titulo:'Vai Funcionar:',descricao:'Mais é claro'}"></wm-notify>
                <wm-notify  titulo="Teste2" descricao="Agora Vai2" :subtitulo="{titulo:'Vai Funcionar:',descricao:'Mais é claro'}"></wm-notify>
                <wm-notify :tipo="4" titulo="Teste2" descricao="Agora Vai2" :subtitulo="{titulo:'Vai Funcionar:',descricao:'Mais é claro'}"></wm-notify>
                <wm-notify :tipo="5" titulo="Teste2" descricao="Agora Vai2" :subtitulo="{titulo:'Vai Funcionar:',descricao:'Mais é claro'}"></wm-notify>
 
              </div>
            </div>
          </div>
        </div>
      </li>

    </ul>
    <!-- Fim NOTIFICACOES-->
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link " style="display: flex;align-items: center;" href="#" role="button" id="dropmenulogado" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="d-flex UserContainer flex-row align-items-center ">
            <?php if (BuscaSecaoValor(SecoesEnum::FOTO_USUARIO) != "") { ?>
              <img style="height: 40px;width: 40px;border-radius: 100%;object-fit: cover;" class="mx-2 border border-success" src="data:image/png;base64,<?php echo $_SESSION[SecoesEnum::FOTO_USUARIO]; ?>" />
            <?php } else { ?>
              <i style="color: #343a40; background: #fff; border-radius:100%; border: solid #fff 4px; font-size: 40px !important;" class="mx-2 fas fa-user-circle" aria-hidden></i>
            <?php } ?>
            <p class="m-0 p-0 UserTXTCell"><?php echo BuscaSecaoValor(SecoesEnum::NOME) ?></p>
          </div>
        </a>
        <div style="width: 28vw;min-width:350px" class="dropdown-menu DropMenuCelular dropdown-menu-right dropdown-info" id="navbarDropdown">
          <div class="container linkCor">
            <div class="col-12">
              <h6><?php echo $_SESSION[SecoesEnum::NOME] ?></h6>
            </div>
            <div class="dropdown-divider"></div>
            <div class="row">
              <div class="col-5 mr-3  ">
                <a style="font-size: 14px" class="dropdown-item" id="MenuPerfil" href="?page=perfilUsuario">Meu perfil</a>
              </div>
              <div class="col-5 text-wrap">
                <?php
                if (BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == "1") {
                ?>
                  <a style="font-size: 14px" class="dropdown-item" href="">Minhas copetências</a>
                <?php } ?>
                <a style="font-size: 14px" class="dropdown-item" href="">Configurações</a>
              </div>
            </div>
            <?php
            if (BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == "0") {
            ?>
              <div class="dropdown-divider"></div>
              <div class="col-12">
                <h6 class=" ">Contratar</h6>
                <div class="row">
                  <div class="col-5 m-0  mr-3 p-0">
                    <a style="font-size: 14px" class="dropdown-item " href="">Procurar Profissional</a>
                  </div>
                  <div class="col-5 m-0  mr-3 p-0" style=" margin-left: 20px !important;">
                    <a style="font-size: 14px" class="dropdown-item " href="?page=chat"><i class="far fa-comments"></i>Chat</a>
                  </div>
                  <div class="col-5 m-0  mr-3 p-0">
                    <a style="font-size: 14px" class="dropdown-item " href="?page=meusprojetos">Meus Projetos</a>
                  </div>
                  <div class="col-5 mx-3">
                    <button style="font-size: 14px" onclick='window.location.href = location.origin + location.pathname + "?page=criarservico"' class=" btn btn-success text-white" href="">Criar Projeto</button>
                  </div>
                </div>
              </div>
            <?php
            }
            ?>
            <div class="dropdown-divider"></div>
            <div class="col-12">
              <h6 class=" ">Ajuda</h6>
              <div class="row">
                <div class="col-5 m-0 mr-3 p-0">
                  <a style="font-size: 14px" class="dropdown-item " href="">Contato</a>
                  <a style="font-size: 14px" class="dropdown-item " href="">Como funciona</a>
                </div>
                <div class="col-5 mx-3">
                  <a style="font-size: 14px" class="dropdown-item " href="">Guia</a>

                </div>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <div class="col-12 ">
              <div class="row  ">
                <div class="col-10">
                </div>
                <div class="col-2  m-0 p-0 ">
                  <a style="font-size: 14px" class="dropdown-item" id="MenuSair">Sair</a>
                </div>
              </div>
            </div>
          </div>

        </div>
      </li>

    </ul>
    <!-- <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dropdown link
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </li> -->
  </div>
</nav>
<?php
if (Logado()[1] == '2')
  require('admSideBar.php');
?>
<script>
  $("#navbarDropdownNotify").click(function(e) {
    e.stopPropagation();
  })
</script>