<?php
// @session_start();
?>
<nav class="navbar navbar-expand-lg NavbarGreen">
  <a class="navbar-brand" style="cursor: pointer;" :href="'?page=perfilUsuario&id=' + dataVue.UsuarioContexto.id">
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
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" style="cursor: pointer;" id='home' :href="'?page=perfilUsuario&id=' + dataVue.UsuarioContexto.id">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" id="tiposervicolist" href="?page=buscaservicos">Buscar Serviços</a>
          </li>
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" href="?page=buscausuarios">Buscar Usuários</a>
          </li>
        <?php
          break;
          // user FUNCIONARIO
          #region Funcionario 
        case "1":
        ?>
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" style="cursor: pointer;" id="home" :href="'?page=perfilUsuario&id=' + dataVue.UsuarioContexto.id">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" id="tiposervicolist" href="?page=buscaservicos">Buscar Serviços</a>
          </li>
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" href="?page=buscausuarios">Buscar Usuários</a>
          </li>
        <?php
          break;
          #endregion
          //  USER ADM 
          #region ADM
        case "2":
        ?>
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" id='home' style="cursor: pointer;" :href="'?page=perfilUsuario&id=' + dataVue.UsuarioContexto.id">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" id='admpaineldecontrole' href="?page=admpaineldecontrole">Painel De Controle</a>
          </li>
          <li class="nav-item tituloHeaderLogado">
            <a class="nav-link" id='admcadastros' href="?page=admcadastros">Cadastros</a>
          </li>

          <li class="nav-item  tituloHeaderLogado">
            <a class="nav-link" href="#">Analise</a>
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
      <li :class="['nav-item','dropdown']" id="DropC">
        <a class="nav-link " style="display: flex;align-items: center;" href="#" role="button" id="dropNotificacoes" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php
          $n = BuscaSecaoValor(SecoesEnum::NUMNOTIFICACOES);
          if ($n != null && $n > 0) {
          ?>
            <span class="notifyredBall"><?php echo $n > 9 ? '9<sup>+</sup>' : $n ?></span>
          <?php } else { ?>
            <span class="notifyredBall" hidden></span> <?php } ?>
          <i class="fas fa-bell" style="font-size: larger;"></i>
        </a>
        <div style="width: 28vw;min-width:350px; height: 300px;" class="dropdown-menu DropMenuCelular dropdown-menu-right dropdown-info" id="navbarDropdownNotify" aria-labelledby="dropNotificacoes">
          <div style="height: 300px;" class=" linkCor">
            <div class="col-12 " style="display: flex;width: 100%;justify-content: space-between;">
              <h6>Notificações</h6>
              <a @click="(a)=>{a.view.Rediredionar('notificacoes')}"><i class="fas fa-external-link-alt"></i></a>
            </div>
            <div class="dropdown-divider" style="margin-bottom: 0px;"></div>
            <div class="row" style="height: 250px;width: 100%;margin: 0px;">
              <div class="col-12 notificacoesScrool">
                <div v-if="dataVue.DropCarregando" style="height: 100%;display: flex;align-items: center;">
                  <wm-loading />
                </div>
                <div v-else-if="dataVue.DropLista.length != 0">
                  <div v-for="item in dataVue.DropLista">
                    <div v-if="item.tipo == -1" class="dataChatDiv"><span class="dataChatDivTexto">{{item.titulo}}</span></div>
                    <div style="cursor: pointer;" v-else @click="dataVue.ClickFuncao(this,item)">
                      <wm-notify :tipo="JSON.parse(item.tipo)" :hora="item.hora" :titulo="item.titulo" :descricao="item.descricao" :subtitulo="{titulo:item.subtitulo,descricao:item.subdescricao}"></wm-notify>
                    </div>
                  </div>
                </div>
                <div v-else style="height: 100%; width: 100%; display: flex;align-items: center;
                justify-content: center;
                font-size: 15px;">
                  <wm-error tamanhoicon="100" style="margin-top: 0px !important;" mensagem="Nenhuma Notificação encontrada" />
                </div>

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
                <a style="font-size: 14px" class="dropdown-item" id="MenuPerfil" style="cursor: pointer;" :href="'?page=perfilUsuario&id=' + dataVue.UsuarioContexto.id">
                  Meu perfil
                </a>
                <?php
                if (BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == "1") {
                ?>
                  <a style="font-size: 14px" class="dropdown-item" @click="(event)=>{ event.view.window.RedirecionarComParametros('notificacoes',[{chave:'P',valor:true}])}" style="cursor: pointer">
                    Minhas Propostas
                  </a>
                <?php } ?>
              </div>
              <div class="col-5 text-wrap">
                <?php
                if (BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == "1") {
                ?>
                  <a style="font-size: 14px; cursor: pointer; width: fit-content;" class="dropdown-item" :href="'?page=perfilUsuario&id=' + dataVue.UsuarioContexto.id + '&edit=1'">
                    Minhas competências
                  </a>
                <?php } ?>
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
                    <a style="font-size: 14px" class="dropdown-item " href="?page=chat"><i class="far fa-comments"></i> Chat</a>
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
  </div>
</nav>
<?php
if (Logado()[1] == '2')
  require('admSideBar.php');
?>
<script type="application/javascript">
  $(document).ready(async () => {
    var NotificacaoInterval = null
    //#region Vue
    app.$set(dataVue, 'DropOpen', false);
    app.$set(dataVue, 'DropCarregando', false);
    app.$set(dataVue, 'DropLista', []);
    app.$set(dataVue, 'NotificacaoNumero', 0);
    app.$set(dataVue, "ClickFuncao", (e, i = item) => {
      if (i.tipo == 2) {
        if (app.dataVue.UsuarioContexto.id_funcionario != "")
          RedirecionarComParametros('chat', [{
            chave: 'id_chat',
            valor: i.id_chat
          }]);
        else {
          RedirecionarComParametros('chat', [{
            chave: 'id_chat',
            valor: i.id_chat
          }, {
            chave: 'id',
            valor: i.id_usuario_criacao
          }])
        }

      } else if (i.tipo == "6") {

        let parametrosSeparados = {};

        for (const key of i.parametros.split(";")) {
          let chaveSeparada = key.split("=");
          parametrosSeparados[key.split("=")[0]] = chaveSeparada[1];
        }

        RedirecionarComParametros(parametrosSeparados["page"], [{
          chave: 'id_projeto',
          valor: parametrosSeparados.idProjeto
        }]);
      } else if (i.tipo == "7") {
        let listaParametros = [];
        i.parametros.split(';').map(a => {
          var list = a.split("=");
          let obj = {};
          Object.defineProperty(obj, list[0], {
            value: list[1]
          });
          listaParametros.push(obj);

        });
        let paramFormatado = listaParametros.filter(a => {
          return Object.getOwnPropertyNames(a)[0] != "page"
        }).map(p => {
          obj = {
            chave: null,
            valor: null
          }
          obj.chave = Object.getOwnPropertyNames(p)[0];
          obj.valor = p[Object.getOwnPropertyNames(p)[0]];
          return obj
        });
        RedirecionarComParametros(listaParametros.filter(a => {
          return Object.getOwnPropertyNames(a)[0] == "page"
        })[0].page, paramFormatado);
      } else if (i.tipo == "1") {
        RedirecionarComParametros("notificacoes", [{
          chave: "P",
          valor: true
        }]);
      } else {
        if (i.parametros !== undefined && i.parametros != "" && i.parametros != 0) {

          let listaParametros = [];
          i.parametros.split(';').map(a => {
            var list = a.split("=");
            let obj = {};
            Object.defineProperty(obj, list[0], {
              value: list[1]
            });
            listaParametros.push(obj);

          });
          let paramFormatado = listaParametros.filter(a => {
            return Object.getOwnPropertyNames(a)[0] != "page"
          }).map(p => {
            obj = {
              chave: null,
              valor: null
            }
            obj.chave = Object.getOwnPropertyNames(p)[0];
            obj.valor = p[Object.getOwnPropertyNames(p)[0]];
            return obj
          });
          RedirecionarComParametros(listaParametros.filter(a => {
            return Object.getOwnPropertyNames(a)[0] == "page"
          })[0].page, paramFormatado);

        } else {
          if (GetPageName("page") != "notificacoes") {
            Rediredionar("notificacoes");
          }
        }

      }

      //   let parametrosSeparados = {};

      //   for (const key of i.parametros.split(";")) {
      //     let chaveSeparada = key.split("=");
      //     parametrosSeparados[key.split("=")[0]] = chaveSeparada[1];
      //   }

      //   RedirecionarComParametros(parametrosSeparados["page"], [{
      //     chave: 'id_projeto',
      //     valor: parametrosSeparados.idProjeto
      //   }]);
      // }

    })
    //#endregion
    $('#DropC').on('show.bs.dropdown', async function() {
      app.dataVue.DropCarregando = true;
      // var CacheNoti = await GetSessaoPHP(SESSOESPHP.NOTIFICACOES);
      // if (CacheNoti != "") {
      //   CacheNoti = JSON.parse(CacheNoti);
      //   app.dataVue.DropLista = CacheNoti;
      //   app.dataVue.DropCarregando = false;

      // }
      app.dataVue.DropOpen = true;
      WMExecutaAjax("NotificacoesBO", "BuscaNotificacoesFormatado", {}, true, true).then(Resultado => {
        if (Resultado.error == undefined) {
          app.dataVue.DropLista = Resultado;
        }
        app.dataVue.DropCarregando = false;

        NotificacaoInterval = setInterval(async () => {
          WMExecutaAjax("NotificacoesBO", "BuscaNotificacoesFormatado", {}, true, true).then(Resultado => {
            if (Resultado.error == undefined) {
              app.dataVue.DropLista = Resultado;

            }
          }).catch(Err => {
            MostraMensagem("Algo Deu Errado", ToastType.ERROR);
          })
        }, 5000)
      }).catch(Err => {
        MostraMensagem("Algo Deu Errado", ToastType.ERROR);
        app.dataVue.DropCarregando = false;
      })
    });
    $('#DropC').on('hide.bs.dropdown', function() {
      app.dataVue.DropOpen = false;
      app.dataVue.DropLista = [];
      clearInterval(NotificacaoInterval);

    });
    await WMExecutaAjax("NotificacoesBO", "GetNumeroNotificacoes").then(num => {
      if (num != 0) {
        $($(".notifyredBall")[0]).html(num > 9 ? `9<sup>+</sup>` : num);
        $($(".notifyredBall")[0]).removeAttr('hidden');
        app.dataVue.NotificacaoNumero = num;

      } else
        $($(".notifyredBall")[0]).attr('hidden', 'hidden');

    });
    var valorAnterior = 0;
    setInterval(async () => {
      await WMExecutaAjax("NotificacoesBO", "GetNumeroNotificacoes").then(num => {
        if (num != 0) {
          $($(".notifyredBall")[0]).html(num > 9 ? `9<sup>+</sup>` : num);
          $($(".notifyredBall")[0]).removeAttr('hidden');
          if (num != valorAnterior) {
            var Evento = new CustomEvent("BuscaNotificacao", {});
            document.dispatchEvent(Evento);
            app.dataVue.NotificacaoNumero = num;
            valorAnterior = num;
          }

        } else
          $($(".notifyredBall")[0]).attr('hidden', 'hidden');

      });
    }, 5000);






  });
</script>