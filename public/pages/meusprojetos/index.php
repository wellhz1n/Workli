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
                    <wm-projeto-item v-for="item in dataVue.Lista" :valor_proposta="item.valorproposta" :key="parseInt(item.id)" :mostra_botao="true" texto_botao="Ver Detalhes" :titulo="item.nome" :publicado="item.postado" :propostas="item.propostas" :categoria="item.categoria" :identidade="item.id" :id="'Item'+item.id" :tamanhodoprojeto="parseInt(item.nivel_projeto)" :nivelprofissional="parseInt(item.nivel_profissional)" :descricao="item.descricao" :nome="item.nome_usuario" :img="item.imagem_usuario" :valor="item.valor" :id_usuario="item.id_usuario" v-on:aberto-modal="v => dataVue.abremodal(v)"></wm-projeto-item>
                </trasition-group>
            </div>
            <div :key="2" v-else>
                <div style="width: 90%;display: flex;flex-direction: row-reverse;align-items: center;justify-content: center;margin-left: 8%;">
                    <img style="height: 40vh;" src="src/img/svg/undraw_new_ideas_jdea.svg" />
                    <div style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
                        <p>Não encontramos nenhum projeto, que tal criar um agora mesmo!</p>
                        <button class="botaoSalvarCardPlano" style="width: 43%;padding: 7px;" @click="(c)=>{
                            if( c.view.window.app.dataVue.meusprojetos.categoria != null)
                            c.view.window.RediredionarComParametros('criarservico',[{chave:'C',valor: c.view.window.app.dataVue.meusprojetos.categoria}]);
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
                                <div class="BHPreco">{{dataVue.selecionadoController.valorproposta !== null?'R$'+ dataVue.selecionadoController.valorproposta :dataVue.selecionadoController.valor}}</div>
                                <div class="BHPublicado"><i class="fas fa-clock reloginhoBH"></i> {{dataVue.selecionadoController.publicado}}</div>
                            </div>
                        </div>
                        <div class="cardQuadradoBody BDescricao" v-html="dataVue.selecionadoController.descricao">
                        </div>
                        <wm-image-viewer style="z-index: 3;" :imgs="dataVue.selecionadoController.Fotos"></wm-image-viewer>

                    </div>
                </div>
                <div class="d-contents" style="display:flex;width: 100%;justify-content: center;margin-top: 7px;">
                    <div class="visualizarProposta" style="width: 60%;">
                        <button class="btn botaoProposta menor w-100 d-flex text-center justify-content-center btn-success text-light" style="cursor: pointer">
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
<script src="pages/meusprojetos/script.js"></script>