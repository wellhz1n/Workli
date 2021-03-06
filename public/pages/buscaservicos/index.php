<div class="col-12 p-0 m-0">
    <div class="row p-0 mx-5">
        <div class="col-2 m-0 p-0" style="height: 100vh; margin-top: 40px !important;">
        <?php
        // AQUI VAI UMA GUAMBIARRA POR PREGUIÇA MSM
        if(BuscaSecaoValor(SecoesEnum::NIVEL_USUARIO) == 0){ ?>
            <div class="categories_box h-25 d-flex flex-column justify-content-center align-items-center ">
                <h5 class="m-0" style="font-family: Poppins-Bold;">Crie Seu Projeto</h5>
                <p class="p-3 m-0" style="font-size: 14px; font-family: Poppins-Regular">Deseja contratar alguem para resolver seu Problema?</p>
                <div class="m-0">
                    <button style="font-size: 14px" onclick='window.location.href = location.origin + location.pathname + "?page=criarservico"' class=" btn btn-success text-white" href="">Criar Projeto</button>
                </div>
            </div>
        <?php } ?>
            <div class="categories_box d-flex flex-column  justify-content-start  align-items-center " :style="[{'margin-top':dataVue.UsuarioContexto.NIVEL_USUARIO != 0 ? '4%' : '10px' }]">
                <h5 class="m-0 py-2" style="font-family: Poppins-Bold;">Categorias</h5>
                <template>
                    <a @click="dataVue.Categorias.Click(item.id)" :style="[{cursor:'pointer'},item.checado?{'font-weight':'bold'}:'',{color:item.checado?'rgb(31,169,52)':'#6c757d'},item.checado?{'text-shadow': '0px 0px 20px rgb(26, 105, 43)'}:null]" v-for="item in dataVue.Categorias.categoria">{{item.nome}}</a>
                </template>
            </div>
        </div>
        <div style="height: fit-content;margin-bottom: 2%;" class="col-10">
            <div id="buscaProjetosWrapper">
                <div class="buscaProjetos">
                    <div class="iconSearch">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </div>
                    <input 
                        id="inputBuscaProjeto" 
                        type="text" 
                        placeholder="Pequise um Projeto" 
                        @input="dataVue.FiltroProjeto.Q = $event.target.value" 
                    />
                </div>
                <div class="paginacaoProjetos">
                    <wm-paginacao 
                        :totaldepaginas="JSON.parse(dataVue.Projetos.pagina)" 
                        :paginaatual="JSON.parse(dataVue.FiltroProjeto.P)" 
                        @changepagina="(a)=>{dataVue.FiltroProjeto.P = a;}" 
                    />
                </div>
            </div>








            <div class="col-12 mx-2 justify-content-center">
                <wm-loading v-if="dataVue.Carregando" style="margin-top: 15%;"></wm-loading>
                <div v-else>
                    <div v-if="dataVue.Projetos.lista.length < 1 ">
                        <wm-error mensagem="Nenhum projeto encontrado" />
                    </div>
                    <wm-projeto-item 
                        v-else
                        :texto_botao="dataVue.UsuarioContexto.NIVEL_USUARIO == 0 ? 'Ver Detalhes' : item.propostaFuncionario == 0 ?'Fazer Proposta':'Ver Detalhes'" 
                        :titulo="item.titulo" 
                        :publicado="item.postado" 
                        :propostas="JSON.parse(item.propostas)"
                        :categoria="item.categoria" 
                        :identidade="item.id" 
                        :id="'item'+item.id" 
                        :tamanhodoprojeto="item.nivel_projeto" 
                        :nivelprofissional="item.nivel_profissional" 
                        :descricao="item.descricao" 
                        :nome="item.usuario" 
                        :img="item.img" 
                        :valor="item.valor" 
                        :id_usuario="item.id_usuario"
                        v-for="item in dataVue.Projetos.lista"
                        v-on:aberto-modal="v => dataVue.abremodal(v)"
                    ></wm-projeto-item>
                </div>
            </div>
        </div>
    </div>
</div>

<wm-modal id="ModalProjetos" :visivel="dataVue.modalVisivelController" :callback="dataVue.callback">
    <template v-slot:header>
        <div class="headerInterno">
            <div class="imgHeaderModal">
                <img class="imgHeaderModal" :src=" dataVue.selecionadoController.FotoPrincipal != undefined ? 'data:image/png;base64,'+ dataVue.selecionadoController.FotoPrincipal :'src/img/background/background.png'" />
            </div>
            <div class="degradeHeaderModal"></div>
            <div class="blocoNome">
                <div :style="{'font-size': dataVue.selecionadoController.nome.length > 30 ? '30px' :'40px' }" class="textoHeaderModal">{{dataVue.selecionadoController.nome}}</div>
                <wm-user-img :img="dataVue.selecionadoController.imagem" class="imagemUsuario" class_icone="iconeImagemNull" class_imagem="imagemTamanhoUser"></wm-user-img>
                <div :style="{'font-size': dataVue.selecionadoController.titulo.length > 80 ? '20px' : '40px' }" class="textoHeaderModal tituloHM">{{dataVue.selecionadoController.titulo}}</div>
            </div>
        </div>
    </template>
    <template v-slot:body>
        <div class="bodyInterno">
            <div class="bodyBody">
                <div class="d-flex">
                    <div class="cardQuadrado bodyDetalhes">
                        <div class="cardQuadradoHeader">
                            <div class="cardQuadradoTitulo BHDetalhes">
                                Detalhes do Projeto
                            </div>
                            <div class="wrapperBH2">
                                <div class="BHPreco">{{dataVue.selecionadoController.valor}}</div>
                                <div class="BHPublicado"><i class="fas fa-clock reloginhoBH"></i> {{dataVue.selecionadoController.publicado}}</div>
                            </div>
                        </div>
                        <div class="p-1 ml-2">
                            <div class="m-2 p-1">
                                <p class="m-0 font_Poopins_M tituloImagemViewer">Descrição do Projeto</p>
                                <hr class="separadorTituloViewer">
                            </div>
                            <div class="cardQuadradoBody BDescricao" v-html="dataVue.selecionadoController.descricao">
                            </div>
                            <wm-image-viewer style="z-index: 3;" :imgs="dataVue.selecionadoController.Fotos"></wm-image-viewer>
                            <div>
                                <div class="m-2 p1">
                                    <p class="m-0 font_Poopins_M tituloImagemViewer">Propriedades do Projeto</p>
                                    <hr class="separadorTituloViewer">
                                </div>
                                <div style="padding: 10px 3.5%;margin-bottom: 3%;">
                                    <p class="m-0 font_Poopins">Categoria: <strong>{{dataVue.selecionadoController.categoria}}</strong></p>
                                    <p class="m-0 font_Poopins">Tamanho do Projeto: <strong>{{dataVue.selecionadoController.tamanho}}</strong></p>
                                    <p class="m-0 font_Poopins">Nível de Profissional Desejado: <strong>{{dataVue.selecionadoController.profissional}}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 1" class="cardQuadrado bodyChat align-items-center">
                        <div style="display: flex;
                                    align-items: baseline;
                                    height: 30px;
                                    justify-content: center;
                                    padding-right: 7px;
                                    width: 100%;
                                    flex-direction: row;
                                    ">
                            <p :style="dataVue.selecionadoController.msg.length > 0 && dataVue.UsuarioContexto.NIVEL_USUARIO == 1?'margin-left: auto;':''" class="font_Poopins_SB mt-1 p-1 mb-0" style="color: #1A692B;">Chat</p>
                            <a onclick="window.open('?page=chat&id_chat=' + dataVue.selecionadoController.id_chat ,'_blank')" class="aicon" v-if="dataVue.selecionadoController.msg.length > 0 && dataVue.UsuarioContexto.NIVEL_USUARIO == 1" style="margin-left: auto;
                            cursor: pointer;"><i class="fas fa-external-link-alt"></i></a>
                        </div>
                        <wm-chat 
                            heigth="385px" 
                            style="display: flex; flex-direction: column; flex: 1;" 
                            :userpropostaimage="dataVue.selecionadoController.imagem" 
                            :mensagens="dataVue.selecionadoController.msg" 
                            :idusuariodestinatario="JSON.parse(dataVue.selecionadoController.id_usuario)" 
                            v-on:novamensagem="M=> dataVue.NovaMensagem(M)"
                            @montado="dataVue.igualaTamanhoChat"
                        ></wm-chat>
                    </div>
                </div>
                <div class="d-flex" v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 1">
                    <div v-if="dataVue.selecionadoController.propostaFuncionario == 0" class="d-contents">
                        <div class="cardQuadrado bodyProposta">
                            <div class="cardQuadradoHeader" id="paddingDetalhesProposta">
                                <div class="cardQuadradoTitulo BHDetalhes">
                                    Detalhes da Proposta
                                </div>
                            </div>
                            <div class="d-flex" style="margin-bottom: 10px;">
                                <div class="propostaPrimeiraParte">
                                    <div class="wrapperOfWrapperSlider">
                                        <div class="wrapperValorDoSlider">
                                            <div class="textoVS">Sua Oferta &nbsp;</div>
                                        </div>
                                        <div class="wrapperSlider">
                                            <div id="precoMin" class="precoSlider">$200</div>
                                            <input type="range" id="rangeSlider" class="inputProposta" />
                                            <div id="precoMax" class="precoSlider">$1000</div>
                                        </div>
                                        <div class="wrapperValorDoSlider">
                                            <div class="valorDoSlider">
                                                <span id="valorAtualSlider"></span>
                                                <a id="linkPopover" tabindex="0" role="button" data-trigger="focus" data-toggle="popover" data-content="Você receberá: R$ 500,00 - R$ 50,00 = R$ 450,00">
                                                    <span id="valorDetalhe">
                                                        <i class="fa fa-question-circle" id="valorDetalheInterrogacao" class="animacaoInterrogacao" aria-hidden="true"></i>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="descricaoDaProposta">
                                        <div class="textoVS textoDP">Descreva sua proposta</div>
                                        <textarea id="descricaoDaPropostaInput" name="descricaoDaPropostaInput" rows="5" cols="33" maxlength="5000" placeholder="Escreva aqui os detalhes da sua proposta..." class="inputProposta">
                                        </textarea>
                                    </div>
                                </div>
                                <div class="propostaSegundaParte">
                                    <div class="innerWrapperSegundaParte">
                                        <div class="cardProposta">Taxa relativa ao <b id="taxaModal">Plano Padrão: 20%</b></div>
                                        <div class="cardProposta">Valor médio das propostas: <b id='propostaMedia'>R$ 0,00</b></div>
                                        <div class="dataChatDiv"><span class="dataChatDivTexto">Upgrades</span></div>
                                        <label class="upgradeCard" for="upgradeCardInput1">
                                            <div class="upgradeCardHeader row">
                                                <div class="d-flex flex-column justify-content-center align-items-center col-sm-2">
                                                    <input class="inputUpgrade inputProposta" type="checkbox" id="upgradeCardInput1" @change="dataVue.Proposta.Upgrades.upgrade1 = $event.target.checked">
                                                    <label class="labelInputUpgrade" for="upgradeCardInput1"></label>
                                                </div>
                                                <span class="tituloUpgradeHeader col-sm-6" id="patrocinado">PATROCINADO</span>
                                                <span id="patrocinadoValor" class="valorUpgradeHeader col-sm-4">R$ 5,00</span>
                                            </div>
                                            <div class="textoUpgradeCard">
                                                Destaque-se dos outros funcionários sendo fixado no topo da tela de propostas do cliente.
                                            </div>
                                        </label>
                                        <label class="upgradeCard" for="upgradeCardInput2" id="upgradeCardBaixo">
                                            <div class="upgradeCardHeader row">
                                                <div class="d-flex flex-column justify-content-center align-items-center col-sm-2">
                                                    <input class="inputUpgrade inputProposta" type="checkbox" id="upgradeCardInput2" @change="dataVue.Proposta.Upgrades.upgrade2 = $event.target.checked">
                                                    <label class="labelInputUpgrade" for="upgradeCardInput2"></label>
                                                </div>
                                                <span class="tituloUpgradeHeader col-sm-6" id="destacado">DESTACADO</span>
                                                <span class="valorUpgradeHeader col-sm-4">R$ 1,00</span>
                                            </div>
                                            <div class="textoUpgradeCard">
                                                Faça a sua proposta ser destacada em amarelo para o cliente, aumentando as chances de ser escolhida.
                                            </div>
                                        </label>

                                    </div>

                                </div>
                            </div>
                            <div class="terceiraParteProposta" style="padding: 1%; width: 100%; display: flex; justify-content: center;">
                                <div v-if="dataVue.PropostaController.carregando" style="width: 100%; display: flex; justify-content: center;">
                                    <button class="btn botaoProposta menor w-100 d-flex text-center justify-content-center btn-success text-light" style="cursor: pointer">
                                        <div style="border-top-color: rgb(57 193 51) !important;" class="activity_in"></div>
                                    </button>
                                </div>
                                <div v-else-if="!dataVue.PropostaController.carregando && !dataVue.PropostaController.mandou" style="width: 100%; display: flex; justify-content: center;">
                                    <button :disabled="dataVue.Proposta.Descricao.length == 0" class="btn botaoProposta w-100 d-flex text-center justify-content-center btn-success text-light" @click="dataVue.enviaproposta" style="cursor: pointer">Mandar Proposta</button>
                                </div>
                                <div v-else style="width: 100%; display: flex; justify-content: center;">
                                    <button :disabled="true" class="btn botaoProposta w-100 d-flex text-center justify-content-center btn-success text-light" style="cursor: pointer; background-color:rgb(57 193 51) !important;border-color:rgb(57 193 51) !important;"><span><i class="fas fa-check text-light"></i></span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="d-contents">
                        <div class="visualizarProposta">
                            <button @click="()=>{RedirectComParan('notificacoes',[{chave:'P',valor:true}])}" class="btn botaoProposta menor w-100 d-flex text-center justify-content-center btn-success text-light" style="cursor: pointer">
                                Visualizar Proposta
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </template>
    <template v-slot:footer>
        <div></div> <!-- Apenas para deixar o footer vazio.-->
    </template>
    <!-- Ainda vou melhorar o estilo desse modal -->
</wm-modal>

<link rel="stylesheet" type="text/css" href="pages/buscaservicos/estilo.css" />
<script src="pages/buscaservicos/script.js"></script>