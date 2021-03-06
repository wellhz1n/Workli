<script type="" src="pages/perfilUsuario/script.js"></script>


<!-- LINKS INTERNOS -->
<link rel="stylesheet" href="pages/perfilUsuario/style.css">
<div class="justify-content-center text-center col p-0 m-0" >
    <div class="row imagemBackgroundPerfilWrapper">
        <div id="imageBackgroundPerfil">
            <span id="bemVindo"><div v-if="dataVue.editavel" class="d-contents">Bem vindo,</div><br/><span id="bVNome">{{dataVue.usuarioDados.nome}}</span></span>
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
        <div id="cardsDadosProposta" v-if="dataVue.nivelUsuario != 2">
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
                <div class="cardDP" v-if="!dataVue.editavel">
                    <div class="wrapperCopiarLink" @click="dataVue.copiarLink(v)">
                        <span class="iconePerfilLink"><i class="fas fa-link"></i></span>
                        <div class="linkIconeTexto">Copiar link do perfil</div>
                    </div>
                </div>
                <div class="cardDP" >
                    <span class="iconePerfil olho"></span>
                    <div class="textPerfilWrapper">
                    <div v-if="dataVue.dadosDeCimaLoad" class="spinner-border text-success" role="status" style="height: 20px;width: 20px;">
                        <span class="sr-only">Loading...</span>
                     </div>
                        <div class="numeroCardPerfil" v-if="dataVue.dadosDeCima && !dataVue.dadosDeCimaLoad">
                            
                        {{dataVue.dadosDeCima.card0}}
                        </div>
                        <div class="textoCardPerfil">{{ dataVue.nivelUsuario == 0?'Projetos Criados':'Propostas enviadas'}}</div>
                    </div>
                </div>
            </div>
            <div class="cardsDPWrapper">
                <div class="cardDP">
                    <span class="iconePerfil martelo"></span>
                    <div class="textPerfilWrapper">
                    <div v-if="dataVue.dadosDeCimaLoad" class="spinner-border text-success" role="status" style="height: 20px;width: 20px;">
                        <span class="sr-only">Loading...</span>
                     </div>
                        <div class="numeroCardPerfil" v-if="dataVue.dadosDeCima && !dataVue.dadosDeCimaLoad">
                            
                        {{dataVue.dadosDeCima.card1}}
                        </div>
                        <div class="textoCardPerfil">{{ dataVue.nivelUsuario == 0?'Projetos Cancelados':'Propostas Aceitas'}}</div>
                    </div>
                </div>
                <div class="cardDP">
                    <span class="iconePerfil carimbo"></span>
                    <div class="textPerfilWrapper">
                    <div v-if="dataVue.dadosDeCimaLoad" class="spinner-border text-success" role="status" style="height: 20px;width: 20px;">
                        <span class="sr-only">Loading...</span>
                     </div>
                        <div class="numeroCardPerfil" v-if="dataVue.dadosDeCima && !dataVue.dadosDeCimaLoad">
                            
                        {{dataVue.dadosDeCima.card2}}
                        </div>
                        <div class="textoCardPerfil">{{ dataVue.nivelUsuario == 0?'Projetos Concluídas':'Propostas Concluídas'}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center text-center">
        <div id="imgcontainer">
            <div v-if="dataVue.Usuario" class="d-contents">
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
                    :carregando ="dataVue.ipload"
                />
            </div>
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
                    <a href="?page=buscausuarios" class="botaoAtalho mb-2"><i class="fas fa-search" aria-hidden="true"></i> Buscar Usuários</a>
                    <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 0) { ?>
                        <a href="?page=criarservico" class="botaoAtalho mb-2"><i class="fas fa-pencil-alt" aria-hidden="true"></i> Publicar um Projeto </a>
                        <a href="?page=meusprojetos" class="botaoAtalho mb-2"><i class="fas fa-newspaper"></i> Meus Projetos </a>
                    <?php }  if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 1) { ?>
                        <a @click="(event)=>{ event.view.window.RedirecionarComParametros('notificacoes',[{chave:'P',valor:true}])}" style="color: white !important; cursor: pointer !important;" class="botaoAtalho mb-2"><i class="fas fa-newspaper"></i> Minhas Propostas </a>
                    <?php }  if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) != 2) { ?>
                        <a href="?page=chat" class="botaoAtalho mb-2"><i class="far fa-comment-dots"></i> Chat</a>
                    <?php } ?>
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
                        {{dataVue.usuarioDados.nome}}
                        </div>
                        <div class="cemXcem profissaoWrapper" v-if="dataVue.nivelUsuario == '1' && dataVue.usuarioDados != undefined && dataVue.usuarioDados.profissao != '' && dataVue.usuarioDados.profissao != undefined">
                            <span class="profCPBolinha">•</span>
                            <div id="profCP"> 
                                {{dataVue.usuarioDados.profissao}}
                            </div>
                        </div>
                    </div>
                    <div class="d-contents" v-if="dataVue.nivelUsuario == '1'  && dataVue.usuarioDados != undefined && dataVue.usuarioDados.profissao != '' && dataVue.usuarioDados.tags != undefined">
                        <div id="tagsCPWrapper">
                        </div>
                    </div>
                    <div id="descricaoPerfil">
                    </div>
                </div>
            </div>
        </div>
        <div v-if="dataVue.nivelUsuario == 1" class="d-contents">
            <div class="col-3 p-0 paddingCardInterno">
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
                                <button class="botaoContratar mb-2" @click="() => {dataVue.abremodalPlanos()}"><i class="fas fa-pencil-alt"></i> Upgrade</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 0) { ?>
        <div v-if="dataVue.nivelUsuario == 1 && !dataVue.editavel" class="d-contents">
            <div class="col-2 p-0">
                <div class="cardQuadrado cemXcem max-heighto cardContratar">
                    <div class="cardQuadradoHeader">
                        <div class="cardQuadradoTitulo">
                            Contratar
                        </div>
                    </div>
                    <div class="cardQuadradoBody cardContratarBody">
                        <div class="tituloContratar">Mande um projeto para este funcionário analisar.</div>
                        <button class="botaoContratar mb-2" @click="() => {dataVue.abremodalContratar()}"><i class="fas fa-pencil-alt"></i> Contratar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php }; ?>
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
                        <span class="font-weight-bold">10%</span> de taxa de intermediação
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
                        <span class="font-weight-bold">5%</span> de taxa de intermediação
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

<!-- Modal de Contratar Funcionário ------------------------------------------------------ -->

<wm-modal 
    id="modalContratar"
    :visivel="dataVue.modalVisivelContratar" 
    :callback="dataVue.callbackContratar"
    height="30%"
    width="50%"
>
    <template v-slot:header>
        <div class="tituloModalContratar">
            CONTRATAR FUNCIONÁRIO
        </div>
    </template>
    <template v-slot:body>
        <div class="contratarModalBody">
            <button class="botaoContratar botaoContratarModal mb-4" @click="() => {dataVue.abremodalAtribuirP()}"><i class="fas fa-arrow-up"></i> Atribuir Projeto Existente</button>
            <a class="botaoContratar botaoContratarModal mb-4" :href="'?page=criarservico&funcDestinatario=' + dataVue.idGeral"><i class="fas fa-plus"></i> Criar Novo Projeto</a>
        </div>
    </template>
    <template v-slot:footer>
        <div></div>
    </template>
</wm-modal>

<!-- ------------------------------------------------------------------------------------- -->


<!-- Modal de Atribuir Projeto --------------------------------------------------------- -->

<wm-modal 
    id="modalAtribuirProjeto"
    :visivel="dataVue.modalVisivelAtribuirP" 
    :callback="dataVue.callbackAtribuirP"
    height="60%"
    width="60%"
>
    <template v-slot:header>
        <div class="tituloModalAtribuirP">
            SELECIONE O PROJETO PARA MANDAR
        </div>
    </template>
    <template v-slot:body>
        <div class="modalAtribuirBody">
            <div v-if="dataVue.meusProjetos == {} && dataVue.meusProjetos == undefined" class="d-contents">
                <wm-error mensagem="Nenhum projeto encontrado. Crie um na aba de criar projetos." /> 
            </div> 
            <div v-else class="d-contents">
                <wm-card-atribuir-projeto 
                    :dados_usuario="item" 
                    v-for="item in dataVue.meusProjetos"
                    @card-selecionado="(info) => {dataVue.mandarPropostaUsuario(info[0], info[1])}"
                ></wm-card-atribuir-projeto>
            </div>
        </div>
    </template>
    <template v-slot:footer>
        <div></div>
    </template>
</wm-modal>

<!-- ------------------------------------------------------------------------------------- -->





<!-- Modal de Confirmação -->
<wm-modal-botoes-generico
    id="modalConfirmacao"
    :visivel="dataVue.modalVisivelControllerConfirmacao" 
    @fechar-modal="(confirmacao) => {dataVue.fechaModalConfirmacao(confirmacao)}"
    :text_botao_salvar="dataVue.botaoSalvarConfirmacao"
>
    <template v-if="dataVue.atribuirProjetoConfirmacao != ''" v-slot:titulo>
        {{dataVue.tituloModalConfirmacao}}
    </template>
    <template v-if="dataVue.atribuirProjetoConfirmacao != ''" v-slot:descricao>
        {{dataVue.textoModalConfirmacao}}
    </template>
</wm-modal-botoes-generico>

<!-- Modal de Crop -->
<wm-crop-modal 
    :img="dataVue.imagemToCrop" 
    :visivel="dataVue.modalVisivelController" 
    @imagem-cropada="img => {dataVue.passaImagemCropada(img);}"
    @fechar-modal="() => {dataVue.fechamodal()}"
    :configs="dataVue.configuracoesCrop"
/>



