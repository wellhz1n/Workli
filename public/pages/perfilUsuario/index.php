

<script type="" src="pages/perfilUsuario/script.js"></script>


<!-- LINKS INTERNOS -->
<link rel="stylesheet" href="pages/perfilUsuario/style.css">
<div class="justify-content-center text-center m-0">
    <div class="row imagemBackgroundPerfilWrapper">
        <div id="imageBackgroundPerfil">
            <span id="bemVindo"><div v-if="dataVue.editavel" class="d-contents">Bem vindo,</div><br/><span id="bVNome"></span></span>
            <wm-user-banner 
                @aberto-modal="v => dataVue.abremodal(v)" 
                @recebe-imagem="imgData => dataVue.mudaImagemToCrop(imgData)" 
                :imgcropada="dataVue.imagemCropadaBanner" 
                @configuracoes-crop="conf => dataVue.salvaConfiguracoes(conf)"
                height_banner="17.15vw"
                :editavel="dataVue.editavel"
                :id_usuario="dataVue.idGeral"
            />

   
        </div>
        <div id="cardsDadosProposta">
            <div class="cardsDPWrapper">
                <div class="cardDP pt-0 px-0" v-if="dataVue.editavel">
                    <div id="wrapperCarteira">
                        <span class="iconePerfil dinheiro"></span>
                        <div class="textPerfilWrapper">
                            <div class="numeroCardPerfil">
                                R$ {{dataVue.valorNaCarteira}}
                            </div>
                            <div class="textoCardPerfil">Minha Carteira</div>
                        </div>
                    </div>
                    <div 
                        id="carteiraIcon" 
                        @click="() => {dataVue.abremodalCarteira()}"
                    >
                        <i class="fas fa-plus"></i>
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
            <wm-user-img 
                :img="dataVue.Usuario.imagem" 
                :width="'14.2vw'" 
                :height="'14.2vw'" 
                :editavel="dataVue.editavel"
                @aberto-modal="v => dataVue.abremodal(v)" 
                @recebe-imagem="imgData => dataVue.mudaImagemToCrop(imgData)" 
                :imgcropada="dataVue.imagemCropadaUsuario"
                @configuracoes-crop="conf => dataVue.salvaConfiguracoes(conf)"
                class_icone="iconeUsuarioTamanho"
                :id_usuario="dataVue.idGeral"
            />
            </div>
        </div>
    <div v-if="dataVue.nivelUsuario == 1" class="d-contents">
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
    </div>
    <div class="row cardsPerfilSuperior">
        <div class="col-3 p-0 paddingCardInterno" v-if="dataVue.editavel">
            <div class="cardQuadrado cemXcem max-heighto d-flex flex-column">
                <div>
                    <div class="cardQuadradoHeader perfilCardHeader">
                        <div class="cardQuadradoTitulo">
                            Atalhos
                        </div>
                    </div>
                </div>
                <div class="cardQuadradoBody" id="atalhosWrapper">
                    
                    <a href="?page=buscaservicos" class="botaoAtalho mb-2"><i class="fas fa-search" aria-hidden="true"></i> Buscar Projetos</a>
                    <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 0) { ?>
                        <a href="?page=criarservico" class="botaoAtalho mb-2"><i class="fas fa-pencil-alt" aria-hidden="true"></i> Publicar um Projeto </a>
                        <a href="?page=meusprojetos" class="botaoAtalho mb-2"><i class="fas fa-newspaper"></i> Meus Projetos </a>
                    <?php } else if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 1) { ?>
                        <a @click="(event)=>{ event.view.window.RedirecionarComParametros('notificacoes',[{chave:'P',valor:true}])}" style="color: white !important; cursor: pointer !important;" class="botaoAtalho mb-2"><i class="fas fa-newspaper"></i> Minhas Propostas </a>
                    <?php } ?>
                    <a href="?page=chat" class="botaoAtalho mb-2"><i class="far fa-comment-dots"></i> Chat</a>
                </div>
            </div>
        </div>
        <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 1) { ?>
        <div class="col-5 p-0 paddingCardInterno">
        <?php } else {?>
        <div class="col-7 p-0 paddingCardInterno">
        <?php } ?>
            <div class="cardQuadrado cemXcem" id="perfilBox">
                <div class="cardQuadradoHeader perfilCardHeader">
                    <div class="cardQuadradoTitulo">
                        Perfil
                    </div>
                    <div v-if="dataVue.editavel" class="d-contents">
                        <div id="botaoEditarPerfil">
                            <i class="fa fa-edit"></i>
                        </div>
                    </div>
                </div>
                <div class="cardQuadradoBody">
                    <div class="nomeEProfCP">
                        <div id="nomeCP">
                        </div>
                        <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 1) { ?>
                            <div class="cemXcem profissaoWrapper" v-if="dataVue.nivelUsuario == '1' && dataVue.usuarioDados.profissao">
                                <span class="profCPBolinha">•</span>
                                <div id="profCP"> 
                                    {{dataVue.usuarioDados.profissao}}
                                </div>
                            </div>
                        <?php }; ?>
                    </div>
                    <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 1) { ?>
                        <div id="tagsCPWrapper">
                        </div>
                    <?php }; ?>
                    <div id="descricaoPerfil">
                    </div>
                </div>
            </div>
        </div>
        <div v-if="dataVue.nivelUsuario == 1" class="d-contents">
            <div class="col-3 p-0">
                <div class="cardQuadrado cemXcem max-heighto" id="statusCard">
                    <div class="cardQuadradoHeader">
                        <div class="cardQuadradoTitulo">
                            Status da Conta
                        </div>
                    </div>
                    <div class="cardQuadradoBody" id="statusBodyC">
                        <img id="iconeStatus" :src="dataVue.iconePlano"></img>
                        <div id="membroStatus">
                            <span id="tituloMembroPlano"></span>

                            <div v-if="dataVue.editavel" class="d-contents">
                                <a 
                                    id="popoverPlano"
                                    class="linkPopover" 
                                    tabindex="0" 
                                    role="button" 
                                    data-trigger="focus" 
                                    data-toggle="popover" 
                                    data-placement="top" 
                                    data-content="Como Membro Básico, você tem uma taxa de 15% por serviço terminado, além de não possuir nenhum privilégio."
                                >
                                    
                                    <span class="iconeInterrogacaoWrapper">
                                        <i class="fa fa-question-circle" class="iconeInterrogacao" aria-hidden="true"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div v-if="dataVue.editavel" class="d-contents">
                            <div class="wrapperUpgradeButton">
                                <button id="upgrade" @click="() => {dataVue.abremodalPlanos()}">UPGRADE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--#region Modal de Edit Perfil-->
<wm-modal 
    :visivel="dataVue.modalVisivelEditPerfil" 
    :callback="dataVue.callbackEP"
    id="modalEdit"
    height="fit-content"
    :tem_modal_confirmacao="true"
>
    <template v-slot:header>
        <div class="tituloModalEP">
            EDITAR DADOS DO PERFIL        
        </div>
    </template>
    <template v-slot:body>
        <div class="bodyModalEPWrapper">
            <div class="bodyModalEP">
                <wm-input 
                    entidade="usuarioDadosEdit" 
                    id="inputNomeP" 
                    campo="nome" 
                    titulo="Nome" 
                    class_pai_wrapper="inputPerfil"
                    :obrigatorio="true"
                ></wm-input>
                <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 1) { ?>
                    <wm-input 
                        entidade="usuarioDadosEdit" 
                        id="inputProfissaoP" 
                        campo="profissao" 
                        titulo="Profissão" 
                        class_pai_wrapper="inputPerfil"
                    ></wm-input>
                    <div class="inputPerfil" id="tagsInput">
                        <label>Adicionar competências</label>
                        <tags-input v-model="dataVue.usuarioDadosEdit.tags" label-style="success" delete-key="['46', '8']" placeholder="Competência">
                            <div class="tags-input"
                                slot-scope="{tag, removeTag, inputEventHandlers, inputBindings }"
                            >
                                <span 
                                    v-for="tag in dataVue.tags"
                                    class="tags-input-tag"
                                >
                                    <span>{{ tag }}</span>
                                    <button type="button" class="tags-input-remove"
                                        v-on:click="removeTag(tag)"
                                    >&times;
                                    </button>
                                </span>
                                <input
                                    class="tags-input-text"  
                                    placeholder="Adicionar Tag..."
                                    v-on="inputEventHandlers"
                                    v-bind="inputBindings"
                                >
                            </div>
                        </tags-input>
                    </div>
                <?php }; ?>
                <wm-textarea
                    entidade="usuarioDadosEdit" 
                    id="inputDescricaoP" 
                    campo="descricao" 
                    titulo="Descrição" 
                    class_pai_wrapper="inputPerfil"
                    :maxlength="2000"
                    :row="7"
                ></wm-textarea>
            </div>
            <!-- WMVerificaForm() -->
            <div id="botaoSalvarWrapperEP">
                <button id="botaoSalvarEP" @click="(salvar) => {dataVue.mandarDados(); dataVue.callbackEP(salvar);}">
                    Salvar <i class="fa fa-check" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </template>
    <template v-slot:footer>
        <div></div> <!-- Apenas para deixar o footer vazio.-->
    </template>
</wm-modal>
<!--#endregion -->

<!-- #region Modal de Planos -->
<wm-modal 
    :visivel="dataVue.modalVisivelPlanos" 
    :callback="dataVue.callbackPlanos"
    id="modalPlano"
    width="90%"
    height="fit-content"
>
    <template v-slot:header>
        <div class="tituloModalPlanos">
            PLANOS DISPONÍVEIS      
        </div>
    </template>
    <template v-slot:body>
        <div class="bodyModalPlanoWrapper">
            <wm-card-plano
                titulo="Membro Padrão"
                icone="planoPadrao.svg"
                :botao_situacao="dataVue.situacaoBotao[0]"
                :plano_number="0"
                @botao-clickado="(nivel) => {dataVue.darUpgradePlano(nivel)}"
                preco="Gratuito"
            >
                <template v-slot:descricao>
                    <span>
                        <span class="font-weight-bold">20%</span> de taxa de intermediação
                    </span>
                </template>
            </wm-card-plano>
            <wm-card-plano
                titulo="Membro Plus"
                icone="planoPlus.svg"
                preco="25,00"
                :plano_number="1"
                @botao-clickado="(nivel) => {dataVue.darUpgradePlano(nivel)}"
                :botao_situacao="dataVue.situacaoBotao[1]"
            >
                <template v-slot:descricao>
                    <span>
                        <span class="font-weight-bold">15%</span> de taxa de intermediação
                    </span>
                    <span class="font-weight-bold">Ícone Plus</span>
                </template>
            </wm-card-plano>
            <wm-card-plano
                titulo="Membro Prime"
                icone="planoPrime.svg"
                preco="50,00"
                :plano_number="2"
                @botao-clickado="(nivel) => {dataVue.darUpgradePlano(nivel)}"
                :botao_situacao="dataVue.situacaoBotao[2]"
            >
                <template v-slot:descricao>
                    <span>
                        <span class="font-weight-bold">12%</span> de taxa de intermediação
                    </span>
                    <span>
                        <span class="font-weight-bold">10</span> vales de patrocínio
                    </span>
                    <span class="font-weight-bold">Ícone Pro</span>
                </template>
            </wm-card-plano>
            <wm-card-plano
                titulo="Membro Master"
                icone="planoMaster.svg"
                preco="80,00"
                :plano_number="3"
                @botao-clickado="(nivel) => {dataVue.darUpgradePlano(nivel)}"
                :botao_situacao="dataVue.situacaoBotao[3]"
            >
                <template v-slot:descricao>
                    <span>
                        <span class="font-weight-bold">8%</span> de taxa de intermediação
                    </span>
                    <span>
                        <span class="font-weight-bold">20</span> vales de patrocínio
                    </span>
                    <span class="font-weight-bold">Propostas patrocinadas independente do limite</span>
                    <span class="font-weight-bold">Ícone Master</span>
                </template>
            </wm-card-plano>
        </div>
    </template>
    <template v-slot:footer>
        <div></div> <!-- Apenas para deixar o footer vazio.-->
    </template>
</wm-modal>



<!-- ---------------------------- Modal de pagamento ---------------------------- -->

<wm-modal 
    id="modalCarteira"
    :visivel="dataVue.modalVisivelCarteira" 
    :callback="dataVue.callbackCarteira"
    height="40%"
    width="80%"
>
    <template v-slot:header>
        <div class="tituloModalCarteira">
            ADICIONAR FUNDOS     
        </div>
    </template>
    <template v-slot:body>
        <div class="bodyCarteiraModal">
            <img src="src/img/icons/perfil/cartao.svg" id='cartaoSvgModal'/>
            <div class="wrapperInputsPagamento">
                <span id="tituloPagamentos">Digite os dados do seu cartão de crédito:</span>
                <wm-input 
                    entidade="usuarioDadosPagamento" 
                    id="inputNumeroCartaoP" 
                    campo="numeroCartao" 
                    class_pai_wrapper="inputPagamento"
                    :obrigatorio="true"
                    placeholder="Número do Cartão"
                ></wm-input>
                <wm-input 
                    entidade="usuarioDadosPagamento" 
                    id="inputNomeCartaoP" 
                    campo="nome" 
                    class_pai_wrapper="inputPagamento"
                    :obrigatorio="true"
                    placeholder="Seu Nome"
                ></wm-input>
                <div class="d-flex" style="width: 100%;">
                    <wm-input 
                        entidade="usuarioDadosPagamento" 
                        id="inputDataCartaoP" 
                        campo="expiracao" 
                        class_pai_wrapper="inputPagamento mr-2"
                        :obrigatorio="true"
                        placeholder="MM/AA"
                    ></wm-input>
                    <wm-input 
                        entidade="usuarioDadosPagamento" 
                        id="inputCVCCartaoP" 
                        campo="CVC" 
                        class_pai_wrapper="inputPagamento"
                        :obrigatorio="true"
                        placeholder="CVC"
                    ></wm-input>
                </div>
                <div class="wrapperCartoes">
                    <div class="inputRadioCartaoWrapper marginCartoes">
                        <label for="visa">
                            <img  class="cartaoImg" src="src/img/perfil/visa.png"/>
                        </label>
                        <input class="inputCartao" id="visa" type="radio" name="cartao"/>
                    </div>
                    <div class="inputRadioCartaoWrapper marginCartoes">
                        <label for="mastercard">
                            <img class="cartaoImg" src="src/img/perfil/mastercard.png"/>
                        </label>
                        <input class="inputCartao" id="mastercard" type="radio" name="cartao"/>
                    </div>
                    <div class="inputRadioCartaoWrapper marginCartoes">
                        <label for="cartao1">
                            <img class="cartaoImg" src="src/img/perfil/cartao1.png"/>
                        </label>
                        <input class="inputCartao" id="cartao1" type="radio" name="cartao"/>
                    </div>
                    <div class="inputRadioCartaoWrapper marginCartoes">
                        <label for="cartao2">
                            <img class="cartaoImg" src="src/img/perfil/cartao2.png"/>
                        </label>
                        <input class="inputCartao" id="cartao2" type="radio" name="cartao"/>
                    </div>
                    <div class="inputRadioCartaoWrapper">
                        <label for="cartao3">
                            <img class="cartaoImg" src="src/img/perfil/cartao3.png"/>
                        </label>
                        <input class="inputCartao" id="cartao3" type="radio" name="cartao"/>
                    </div>
                </div>
                <div class="wrapperInputDinheiro">
                    <label id="textoDinheiro" for="inputDinheiro">
                    R$    
                    </label>
                    <input 
                        id="inputDinheiro" 
                        type="number" 
                        min="0" 
                        max="10000" 
                        step="any" 
                        value="00.00"/>
                </div>
                <button id="botaoAdicionarFundos" @click="dataVue.adicionarFundos(dataVue.usuarioDadosPagamento.valor)">ADICIONAR FUNDOS <i class="fas fa-wallet"></i></button>
            </div>
        </div>
    </template>
    <template v-slot:footer>
        <div></div>
    </template>
</wm-modal>

<!-- ---------------------------------------------------------------------------- -->



<!-- Modal de Confirmação -->
<wm-modal-confirmacao
    id="modalConfirmacao"
    :visivel="dataVue.modalVisivelControllerConfirmacao" 
    @fechar-modal="(confirmacao) => {dataVue.fechaModalConfirmacao(confirmacao)}"
></wm-modal-confirmacao>

<!-- Modal de Crop -->
<wm-crop-modal 
    :img="dataVue.imagemToCrop" 
    :visivel="dataVue.modalVisivelController" 
    @imagem-cropada="img => {dataVue.passaImagemCropada(img);}"
    @fechar-modal="() => {dataVue.fechamodal()}"
    :configs="dataVue.configuracoesCrop"
/>
