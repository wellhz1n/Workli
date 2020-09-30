<link rel="stylesheet" href="pages/meusprojetos/style.css">
<transition name="fadefast" mode="out-in">
    <div :key="0" v-if="dataVue.carregando === undefined|| dataVue.carregando" style="height: 70vh;display: flex;justify-content: center;align-items: center;">
        <wm-loading />
    </div>
    <div :key="1" v-else class="CONTAINER_M ">
        <div class=" containerBox ">
            <!-- <wm-chart /> -->
            <div style="display: flex;align-items: start;    z-index: 1;">
                <div :style="{'font-size': dataVue.UsuarioContexto.Nome.length > 30 ? '30px' :'40px'}" class="textoHeaderModal">{{dataVue.UsuarioContexto.Nome}}</div>
            </div>
            <div class="row" style="justify-self: flex-end;">
                <div class="col-md-5">
                    <label style="font-size: 20px; color:white" for="#pesq">Procurar</label>
                    <input style="height: 33px;margin-top: 2px;" placeholder="Pequise um Projeto" id="pesq" type="text" class="form-control" @input="dataVue.meusprojetos.Q = $event.target.value" />
                </div>
                <wm-select class="col-md-3" v-bind="dataVue.seletorcategoria"></wm-select>
                <wm-select class="col-md-4" v-bind="dataVue.seletorsituacao"></wm-select>

            </div>
        </div>
        <transition name="fade" mode="out-in">
            <div v-if="dataVue.PageController.paginas > 1">
                <wm-paginacao :totaldepaginas="dataVue.PageController.paginas" :paginaatual="dataVue.PageController.pagina_Atual" v-on:changepagina="(a)=>{dataVue.PageController.pagina_Atual = a}" />
            </div>
        </transition>
        <transition name="fadefast" mode="out-in">
            <div :key="0" v-if="dataVue.ListaCarregando === undefined  ||dataVue.ListaCarregando" style="margin-top: 6%;width: 90%;justify-content: center;display: flex;">
                <wm-loading></wm-loading>
            </div>
            <div :key="1" v-else-if="dataVue.Lista !== undefined &&  dataVue.Lista.length > 0 && !dataVue.ListaCarregando" class=" feed ">
                <trasition-group name="fadefast" style="display: contents;">

                    <!-- <wm-chart /> -->
                    <wm-projeto-item v-for="item in dataVue.Lista" :situacao="item.situacao" :valor_proposta="item.valorproposta" :key="parseInt(item.id)" :mostra_botao="true" texto_botao="Ver Detalhes" :titulo="item.nome" :publicado="item.postado" :propostas="item.propostas" :categoria="item.categoria" :identidade="item.id" :id="'Item'+item.id" :tamanhodoprojeto="parseInt(item.nivel_projeto)" :nivelprofissional="parseInt(item.nivel_profissional)" :descricao="item.descricao" :nome="item.nome_usuario" :img="item.imagem_usuario" :valor="item.valor" :id_usuario="item.id_usuario" v-on:aberto-modal="v => dataVue.abremodal(v)"></wm-projeto-item>
                </trasition-group>
            </div>
            <div :key="2" v-else>
                <div style="width: 90%;display: flex;flex-direction: row-reverse;align-items: center;justify-content: center;margin-left: 8%;">
                    <img style="height: 40vh;" src="src/img/svg/undraw_new_ideas_jdea.svg" />
                    <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
                        <p>Não encontramos nenhum projeto, que tal criar um agora mesmo!</p>
                        <button class="botaoSalvarCardPlano" style="width: 43%;padding: 7px;" @click="(c)=>{
                            if( c.view.window.app.dataVue.meusprojetos.categoria != null)
                            c.view.window.RedirecionarComParametros('criarservico',[{chave:'C',valor: c.view.window.app.dataVue.meusprojetos.categoria}]);
                            else
                            c.view.window.Rediredionar('criarservico');
                        }">
                            Criar Projeto
                        </button>
                    </div>
                </div>
            </div>
        </transition>



    </div>
</transition>

<wm-modal id="ModalProjetos" :visivel="dataVue.modalVisivelController" :callback="dataVue.callback">

    <template v-slot:header>
        <div class="headerInterno">
            <div class="imgHeaderModal">
                <img class="imgHeaderModal" :src=" dataVue.selecionadoController.FotoPrincipal !== undefined ? 'data:image/png;base64,'+ dataVue.selecionadoController.FotoPrincipal :'src/img/background/background.png'" />
            </div>
            <div class="degradeHeaderModal"></div>
            <div class="blocoNome">
                <div :style="{'font-size': dataVue.selecionadoController.nome.length > 30 ? '30px' :'40px' }" class="textoHeaderModal">{{dataVue.selecionadoController.nome}}</div>
                <wm-user-img :img="dataVue.selecionadoController.imagem" class="imagemUsuario" class_icone="iconeImagemNull" class_imagem="imagemTamanhoUser"></wm-user-img>
                <div :style="[{'font-size': dataVue.selecionadoController.titulo.length > 80 ? '20px' : '40px'},{'margin-top':'3%'}]" class="textoHeaderModal tituloHM">{{dataVue.selecionadoController.titulo}}</div>
                <div class="BHPublicado" style="width: 100%;"><i class="fas fa-clock reloginhoBH"></i> {{dataVue.selecionadoController.publicado}}</div>
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
                                <div style="display: contents;" v-show="dataVue.selecionadoController.situacao == 0">
                                    <span data-toggle="tooltip" title="Novo" class="fa fa-project-diagram mx-2"></span>
                                </div>
                                <div style="display: contents;" v-show="dataVue.selecionadoController.situacao == 1">
                                    <span data-toggle="tooltip" title="Aguardando Funcionário Iniciar" class="fa fa-clock mx-2"></span>
                                </div>
                                <div style="display: contents;" v-show="dataVue.selecionadoController.situacao == 2">
                                    <span data-toggle="tooltip" title="Em Andamento" class="fa fa-tasks mx-2"></span>
                                </div>
                                <div style="display: contents;" v-show="dataVue.selecionadoController.situacao == 3">
                                    <span data-toggle="tooltip" title="Cancelado" class="fa fa-times mx-2"></span>
                                </div>
                                <div style="display: contents;" v-show="dataVue.selecionadoController.situacao == 4">
                                    <span data-toggle="tooltip" title="Concluido" class="fa fa-check-double mx-2"></span>
                                </div>
                                Detalhes do Projeto
                            </div>
                            <div class="wrapperBH2">
                                <div class="BHPreco">{{dataVue.selecionadoController.valorproposta !== null?'R$'+ dataVue.selecionadoController.valorproposta :dataVue.selecionadoController.valor}}</div>
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
                                <div style="padding: 10px 3.5%;">
                                    <p class="m-0 font_Poopins">Categoria: <strong>{{dataVue.selecionadoController.categoria}}</strong></p>
                                    <p class="m-0 font_Poopins">Tamanho do Projeto: <strong>{{dataVue.selecionadoController.tamanho}}</strong></p>
                                    <p class="m-0 font_Poopins">Nível de Profissional Desejado: <strong>{{dataVue.selecionadoController.profissional}}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cardQuadrado" style="flex: 4;">
                        <div class="cardQuadradoHeader" style="justify-content: center !important;">
                            <div class="cardQuadradoTitulo ">
                                Ações Disponíveis
                            </div>
                        </div>
                        <div class="m-1 p-1">

                            <div class="d-flex w-100 justify-content-center">
                                <div v-if="dataVue.selecionadoController.situacao != 3" class="d-contents" style="display:flex;flex-direction: column; width: 90%;justify-content: center;align-items: center;margin-top: 7px;">

                                    <a v-if="dataVue.selecionadoController.situacao  == 0 && dataVue.selecionadoController.proposta > 0 " @click="(event)=>{ event.view.window.RedirecionarComParametros('notificacoes',[{chave:'P',valor:true}])}" class="botaoAtalho mb-2" style="color: white !important;cursor: pointer !important;"><i class="fas fa-eye" aria-hidden="true"></i> Visualizar Propostas</a>
                                    <a v-if="dataVue.selecionadoController.situacao  == 4 && dataVue.selecionadoController.avaliou == 0" @click="(event)=>{dataVue.AvaliacaoModalController = true}" class="botaoAtalho mb-2" style="color: white !important;cursor: pointer !important;"><i class="fas fa-star" aria-hidden="true"></i> Avaliar Funcionário</a>
                                    <a v-if=" dataVue.selecionadoController.situacao  != 3 " @click="(event)=>{dataVue.BTClick(event,'CHAT')}" class="botaoAtalho mb-2" style="color: white !important;cursor: pointer !important;"><i class="fas fa-comment-dots" aria-hidden="true"></i> Abrir Chat</a>
                                    <a v-if="dataVue.selecionadoController.situacao == 0 && dataVue.selecionadoController.proposta == 0" @click="(event)=>{dataVue.BTClick(event,'CANCELA')}" style="color: white !important;cursor: pointer !important;" class="botaoAtalho mb-2"><i class="fas fa-times" aria-hidden="true"></i> Cancelar Projeto</a>

                                </div>
                                <div v-else-if="dataVue.selecionadoController.situacao  == 3 " style="width: 90%;display: flex;flex-direction: column;align-items: center;justify-content: center;margin-left: 8%;padding: 1px;margin-top: 30%;">
                                    <img style="height: 16vh;" src="src/img/svg/undraw_empty_xct9.svg" />
                                    <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
                                        <p>Projeto Cancelado, sem ações disponíveis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<!-- MODAL AVALIACAO -->
<wm-modal-botoes-generico text_botao_salvar="Avaliar" :visivel="dataVue.AvaliacaoModalController" @fechar-modal="()=>{dataVue.AvaliacaoModalController = false}">
    <template v-slot:titulo>
        Avaliação do Projeto
    </template>
    <template v-slot:descricao>
        <div>
            <div class="d-flex flex-column justify-content-center align-items-center">
                <wm-user-img :img=" dataVue.AvaliacaoController.funcionarioEntidade.imagem" :width="'10vw'" :height="'10vw'" class_icone="iconeUsuarioTamanho" :id_usuario=" dataVue.AvaliacaoController.funcionarioEntidade.id"></wm-user-img>
                <p style="font-weight: bolder;font-size: 34px;" class="font_Poopins_B">{{ dataVue.AvaliacaoController.funcionarioEntidade.nome}}</p>
            </div>

            <div class="d-flex flex-row-reverse">
                <star-rating v-model="dataVue.AvaliacaoController.avaliacao" :glow="4" :star-size="55" glow-color="#ff000000aa" :clearable="true" :increment='0.5' :star-size='25' :fixed-points='1' :round-start-rating='true' :padding='5' text-class="texto"></star-rating>
                <button type="button" class="btn btn-secondary botaoC" style=" margin-top: 10px;
                                margin-right: 18px;
                                padding: 0px 16px;
                                line-height: 1px;" @click="() => {dataVue.AvaliacaoController.avaliacao = 0}"><i class="fas fa-times"></i></button>
            </div>
        </div>
    </template>
</wm-modal-botoes-generico>

<!-- FIM MODAL -->
<script src="pages/meusprojetos/script.js"></script>