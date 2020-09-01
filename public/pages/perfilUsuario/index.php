<!-- LINKS INTERNOS -->
<link rel="stylesheet" href="pages/perfilUsuario/style.css">


<!-- Modal de confirmação -->

<div class="justify-content-center text-center m-0">
    <div class="row imagemBackgroundPerfilWrapper">
        <div id="imageBackgroundPerfil">
             <span id="bemVindo">Bem vindo, <br/><span id="bVNome"></span></span>
             <wm-user-banner 
                @aberto-modal="v => dataVue.abremodal(v)" 
                @recebe-imagem="imgData => dataVue.mudaImagemToCrop(imgData)" 
                :imgcropada="dataVue.imagemCropadaBanner" 
                @configuracoes-crop="conf => dataVue.salvaConfiguracoes(conf)"
                height_banner="17.15vw"
            />

             
        </div>
        <div id="cardsDadosProposta">
            <div class="cardsDPWrapper">
                <div class="cardDP">
                    <span class="iconePerfil dinheiro"></span>
                    <div class="textPerfilWrapper">
                        <div class="numeroCardPerfil">R$ 0,00</div>
                        <div class="textoCardPerfil">Minha Carteira</div>
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
                :editavel="true"
                @aberto-modal="v => dataVue.abremodal(v)" 
                @recebe-imagem="imgData => dataVue.mudaImagemToCrop(imgData)" 
                :imgcropada="dataVue.imagemCropadaUsuario"
                @configuracoes-crop="conf => dataVue.salvaConfiguracoes(conf)"
                class_icone="iconeUsuarioTamanho"
            />
        </div>
    </div>

    <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 1) { ?>
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
    <?php }; ?>
    <div class="row cardsPerfilSuperior">
        <div class="col-6 p-0 paddingCardInterno">
            <div class="cardQuadrado cemXcem">
                <div class="cardQuadradoHeader perfilCardHeader">
                    <div class="cardQuadradoTitulo">
                        Perfil
                    </div>
                    <div id="botaoEditarPerfil">
                        <i class="fa fa-edit"></i>
                    </div>
                </div>
                <div class="cardQuadradoBody">
                    <div class="nomeEProfCP">
                        <div id="nomeCP">
                        </div>
                        <?php if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 1) { ?>
                            <div class="cemXcem profissaoWrapper">
                                <span class="profCPBolinha">•</span>
                                <div id="profCP"> 
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
        <div class="col-4 p-0">
            <div class="cardQuadrado cemXcem" id="statusCard">
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
</div>

<!--#region Modal de Edit Perfil-->
<wm-modal 
        :visivel="dataVue.modalVisivelEditPerfil" 
        :callback="dataVue.callbackEP"
        id="modalEdit"
        height="80%"
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
                        <tags-input v-model="dataVue.usuarioDadosEdit.tags" label-style="success" delete-key="['46', '8']">
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

<!-- #region Modal de Edit Perfil -->
<!-- <wm-modal 
        :visivel="dataVue.modalVisivelEditPerfil" 
        :callback="dataVue.callbackEP"
        id="modalEdit"
        height="80%"
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
                        <tags-input v-model="dataVue.usuarioDadosEdit.tags" label-style="success" delete-key="['46', '8']">
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
                <?php }; ?> -->
                <!-- <wm-textarea
                    entidade="usuarioDadosEdit" 
                    id="inputDescricaoP" 
                    campo="descricao" 
                    titulo="Descrição" 
                    class_pai_wrapper="inputPerfil"
                    :maxlength="2000"
                    :row="7"
                ></wm-textarea>
            </div> -->
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


<script type="" src="pages/perfilUsuario/script.js"></script>