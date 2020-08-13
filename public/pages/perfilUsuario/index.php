<!-- LINKS INTERNOS -->
<link rel="stylesheet" href="pages/perfilUsuario/style.css">

<?php
require "pages/perfilUsuario/componenteTexto/index.php";
?>


<div class="justify-content-center text-center m-0">
    <div class="row imagemBackgroundPerfilWrapper">
        <div id="imageBackgroundPerfil">
             <span id="bemVindo">Bem vindo, <br/><span id="bVNome"><?php echo $_SESSION[SecoesEnum::NOME] ?></span></span>
             <wm-user-banner/>
        </div>
        <div id="cardsDadosProposta">
            <div class="cardsDPWrapper">
                <div class="cardDP">
                    <span class="iconePerfil dinheiro"></span>
                    <div class="textPerfilWrapper">
                        <div class="numeroCardPerfil">R$ 0,00</div>
                        <div class="textoCardPerfil">Seus ganhos</div>
                    </div>
                </div>
                <div class="cardDP">
                    <span class="iconePerfil olho"></span>
                    <div class="textPerfilWrapper">
                        <div class="numeroCardPerfil">0</div>
                        <div class="textoCardPerfil">Propostas enviadas</div>
                    </div>
                </div>
            </div>
            <div class="cardsDPWrapper">
                <div class="cardDP">
                    <span class="iconePerfil martelo"></span>
                    <div class="textPerfilWrapper">
                        <div class="numeroCardPerfil">0</div>
                        <div class="textoCardPerfil">Propostas Aceitas</div>
                    </div>
                </div>
                <div class="cardDP">
                    <span class="iconePerfil carimbo"></span>
                    <div class="textPerfilWrapper">
                        <div class="numeroCardPerfil">0</div>
                        <div class="textoCardPerfil">Propostas Concluídas</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center text-center">
        <div id="imgcontainer">
            <div id="maskEditImg" class="editimgbox ml-2" hidden>
                <i id="cameraIconPerfil" class="fas fa-camera" aria-hidden></i>
            </div>
            <wm-user-img :img="dataVue.Usuario.imagem" :width="200" :height="200" :margem_imagem="'mt-4'"/>
        </div>
    </div>

    <?php
    if($_SESSION[SecoesEnum::NIVEL_USUARIO] == 1) {
        echo "
            <div class='wrapperStarRating'>
                <star-rating 
                        v-model='dataVue.Rating'
                        :increment='0.5'
                        :star-size='dataVue.StarSize'
                        :fixed-points='1'
                        text-class='textoEstrelas'
                        :round-start-rating='false'
                        :read-only='true'
                        :padding='5'
                ></star-rating>
            </div>
        ";
    }

    // echo componenteTexto("Nome", $_SESSION[SecoesEnum::NOME]);

    // if ($_SESSION[SecoesEnum::NIVEL_USUARIO] != 2) { //Campos que só aparecem se for cliente ou funcionário
    //     // echo componenteTexto("Cpf", $_SESSION[SecoesEnum::CPF]);
    // }

    // if($_SESSION[SecoesEnum::NIVEL_USUARIO] == 1) { 
    //     echo componenteTexto("Currículo", $_SESSION[SecoesEnum::CURRICULO]); // Deixar Curriculo sem acento para não bugar. TODO: Consertar este problema
    //     echo componenteTexto("Telefone", $_SESSION[SecoesEnum::NUMERO_TELEFONE]);
    //     // echo componenteTexto("Avaliação Média", $_SESSION[SecoesEnum::AVALIACAO_MEDIA]);
    // }
    ?>
    <div class="row cardsPerfilSuperior">
        <div class="cardQuadrado" id="perfilCard">
            <div class="cardQuadradoHeader perfilCardHeader">
                <div class="cardQuadradoTitulo">
                    Perfil
                </div>
                <div class="botaoEditarPerfil">
                    <i class="fa fa-edit"></i>
                </div>
            </div>
            <div class="cardQuadradoBody">
                <div class="nomeEProfCP">
                    <div class="nomeCP">
                        FUNCIONARIO FULANO CICLANO
                    </div>
                    <div class="profCP">
                        <span class="profCPBolinha">•</span> Programador
                    </div>
                </div>
                <div id="tagsCPWrapper">
                    <div class="tagCP">SQL</div>
                    <div class="tagCP">Javascript</div>
                    <div class="tagCP">PHP</div>
                    <div class="tagCP">HTML</div>
                    <div class="tagCP">CSS</div>
                    <div class="tagCP">REACT</div>
                    <div class="tagCP">REACT NATIVE</div>
                    <div class="tagCP">VUE</div>
                    <div class="tagCP">C++</div>
                    <div class="tagCP">Java</div>
                </div>
                <div class="descricaoPerfil">
                Mussum Ipsum, cacilds vidis litro abertis. Mais vale um bebadis conhecidiss, que um alcoolatra anonimis. In elementis mé pra quem é amistosis quis leo. Posuere libero varius. Nullam a nisl ut ante blandit hendrerit. Aenean sit amet nisi. Si num tem leite então bota uma pinga aí cumpadi!
                </div>
            </div>
        </div>
        <div class="cardQuadrado" id="contatoCard">
            <div class="cardQuadradoHeader perfilCardHeader">
                <div class="cardQuadradoTitulo">
                    Contato
                </div>
                <div class="botaoEditarPerfil" href="#">
                    <i class="fa fa-edit"></i>
                </div>
            </div>
            <div class="cardQuadradoBody" id="contatoBody">
                <div class="tituloContato emailsPerfil"><i class="fa fa-envelope"></i> Emails:</div>
                <div class="tituloContato telefonesPerfil"><i class="fa fa-phone"></i> Telefones:</div>
            </div>
        </div>
        <div class="cardQuadrado" id="statusCard">
            <div class="cardQuadradoHeader">
                <div class="cardQuadradoTitulo">
                    Status da Conta
                </div>
            </div>
            <div class="cardQuadradoBody" id="statusBodyC">
                <div id="iconeSubstituto"></div>
                <div id="membroStatus">Membro Básico
                    <a class="linkPopover" tabindex="0" role="button" data-trigger="focus" data-toggle="popover" data-placement="top" data-content="Como Membro Básico, você tem uma taxa de 15% por serviço terminado, além de não possuir nenhum privilégio.">
                        <span class="iconeInterrogacaoWrapper">
                            <i class="fa fa-question-circle" class="iconeInterrogacao" aria-hidden="true"></i>
                        </span>
                    </a>
                </div>
                <div class="wrapperUpgradeButton">
                    <button id="upgrade">UPGRADE</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="EditImgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditImgModalLabel">Editar Foto de Perfil</h5>
                <button type="button" id="FechaModal" class="close"  aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div id="imgcontainerModal" class="d-flex justify-content-center align-items-center">

                            <div id="maskEditImgModal" class="editimgbox my-4 ml-2 ">
                                <i class="fas fa-file-upload" style="font-size: 50px;color:#fff" aria-hidden></i>
                            </div>
                            <div id="maskEditImg" class="my-4 ml-2" hidden>
                                <i id="cameraIconPerfil" class="fas fa-camera" aria-hidden></i>
                            </div>
                            <wm-user-img :img="dataVue.Usuario.imgTemp == null? dataVue.Usuario.imagem : dataVue.Usuario.imgTemp"/>
                        </div>
                    </div>
                    <div class="col-4 my-5" style="font-size: 10px">
                        <p>Para uma melhor experiencia busque imagens com as dimensões quadradas.</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="SalvarImg" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script type="" src="pages/perfilUsuario/script.js"></script>