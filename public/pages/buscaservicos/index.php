<div class="col-12 p-0 m-0">
    <div class="row p-0 m-0">
        <div style="height: 100vh" class="col-2 m-0 p-0 ">
            <div class="categories_box h-25 d-flex flex-column justify-content-center align-items-center ">
                <h5 class="m-0" style="font-family: Poppins-Bold;">Crie Seu Projeto</h5>
                <p class="p-3 m-0" style="font-size: 14px; font-family: Poppins-Regular">Deseja contratar alguem para resolver seu Problema?</p>
                <div class="m-0">
                    <button style="font-size: 14px" onclick='window.location.href = location.origin + location.pathname + "?page=criarservico"' class=" btn btn-success text-white" href="">Criar Projeto</button>
                </div>
            </div>
            <div class="categories_box d-flex flex-column  justify-content-start  align-items-center ">
                <h5 class="m-0 py-2" style="font-family: Poppins-Bold;">Categorias</h5>
                <template>
                    <a @click="dataVue.Categorias.Click(item.id)" :style="[{cursor:'pointer'},item.checado?{'font-weight':'bold'}:'',{color:item.checado?'#000':'#6c757d'},item.checado?{'text-shadow': '0px 0px 10px rgba(74,74,74,0.91)'}:null]" v-for="item in dataVue.Categorias.categoria">{{item.nome}}</a>
                </template>
            </div>
        </div>
        <div style="height: 800vh" class="col-10  ">
            <div class="row justify-content-center ">
                <div class="p-3 col-9">
                    <input placeholder="Pequise um Projeto" type="text" class="form-control" @input="
                                                                                dataVue.FiltroProjeto.Q = $event.target.value;
                                                                            " />
                </div>
            </div>
            <div class="col-6 my-0">
                <wm-paginacao :totaldepaginas="JSON.parse(dataVue.Projetos.pagina)" :paginaatual="JSON.parse(dataVue.FiltroProjeto.P)" v-on:changepagina="(a)=>{dataVue.FiltroProjeto.P = a;}" />
            </div>
            <div class="col-12 mx-2 justify-content-center">
                <wm-loading v-if="dataVue.Carregando"></wm-loading>
                <div v-else>
                    <div v-if="dataVue.Projetos.lista.length < 1 ">
                        <wm-error mensagem="Nenhum projeto encontrado" />
                    </div>
                    <wm-projeto-item v-else :titulo="item.titulo" :publicado="item.postado" :propostas="0" :categoria="item.categoria" :identidade="item.id" :id="'item'+item.id" :tamanhodoprojeto="item.nivel_projeto" :nivelprofissional="item.nivel_profissional" :descricao="item.descricao" :nome="item.usuario" :img="item.img" :valor="item.valor" v-for="item in dataVue.Projetos.lista" v-on:aberto-modal="v => dataVue.abremodal(v)"></wm-projeto-item>
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
                    <div class="bodyDetalhes">
                        <div class="bodyHeader">
                            <div class="BHDetalhes">
                                Detalhes do Projeto
                            </div>
                            <div class="wrapperBH2">
                                <div class="BHPreco">{{dataVue.selecionadoController.valor}}</div>
                                <div class="BHPublicado"><i class="fas fa-clock reloginhoBH"></i> {{dataVue.selecionadoController.publicado}}</div>
                            </div>
                        </div>
                        <div class="BDescricao" v-html="dataVue.selecionadoController.descricao">
                        </div>
                        <wm-image-viewer style="z-index: 3;" :imgs="dataVue.selecionadoController.Fotos"></wm-image-viewer>

                    </div>
                    <div class="bodyChat">
                        <div id="bodyChatChat">

                            <div class="dataChatDiv"><span class="dataChatDivTexto">Ontem</span></div>
                            <div class="textoFuncionario">
                                <wm-user-img :img="dataVue.UsuarioContexto.Foto" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                                <div class="textoTF">
                                    Boa tarde, antes de continuarmos com o projeto, precisamos discutir o pagamento.
                                    <div class="tempoTF">14:22</div>
                                </div>
                            </div>

                            <div class="textoCliente">
                                <wm-user-img :img="dataVue.selecionadoController.imagem" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                                <div class="textoTC">
                                    Boa tarde, a minha verba é a demarcada no projeto, mas pagarei de acordo com o trabalho a ser feito.
                                    <div class="tempoTC">14:54</div>
                                </div>
                            </div>

                            <div class="textoFuncionario">
                                <wm-user-img :img="dataVue.UsuarioContexto.Foto" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                                <div class="textoTF">
                                    Como você pode ver, a nossa empresa, Soft Systems é especializada no ramo de sistema RP's, que você pretende desenvolver, além disso, demandamos grandes esforcos para a eficiência e rapidez, por isso, gostariamos de 100% da verba destinada ao projeto.
                                    <div class="tempoTF">15:08</div>
                                </div>
                            </div>

                            <div class="textoCliente">
                                <wm-user-img :img="dataVue.selecionadoController.imagem" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                                <div class="textoTC">
                                    Entendo, mas por nunca ter feito nada com vocês, não tenho certeza da confiabilidade, então pretendo destinar 90% da verba.
                                    <div class="tempoTC">15:09</div>
                                </div>
                            </div>

                            <div class="textoFuncionario">
                                <wm-user-img :img="dataVue.UsuarioContexto.Foto" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                                <div class="textoTF">
                                    Está bem, 90% é um bom número.
                                    <div class="tempoTF">15:36</div>
                                </div>
                            </div>

                            <div class="dataChatDiv"><span class="dataChatDivTexto">HOJE</span></div>

                            <div class="textoFuncionario">
                                <wm-user-img :img="dataVue.UsuarioContexto.Foto" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                                <div class="textoTF">
                                    Olá, você tem algum prazo mínimo?
                                    <div class="tempoTF">13:46</div>
                                </div>
                            </div>

                            <div class="textoCliente">
                                <wm-user-img :img="dataVue.selecionadoController.imagem" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                                <div class="textoTC">
                                    Boa tarde, eu preciso do trabalho pronto em 2 meses. Você acha que é tempo o bastante?
                                    <div class="tempoTC">14:01</div>
                                </div>
                            </div>

                            <div class="textoFuncionario">
                                <wm-user-img :img="dataVue.UsuarioContexto.Foto" class="imagemGeralBC" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                                <div class="textoTF">
                                    Com certeza, focarei toda a minha equipe nesse projeto para entregarmos o mais cedo possível :)
                                    <div class="tempoTF">14:59</div>
                                </div>
                            </div>


                            <div id="ancora">

                            </div>
                        </div>
                        <div class="bodyChatEnviar">
                            <div class="wrapperImagemBC">
                                <wm-user-img :img="dataVue.UsuarioContexto.Foto" class_icone="BCNullIcon" class_imagem="BCImageIcon"></wm-user-img>
                            </div>
                            <div class="wrapperInputBC">
                                <input type="text" class="inputBC" placeholder="Faça uma pergunta..."></input>
                                <i class="fas fa-caret-right iconeSetaEnviar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="bodyProposta">
                        <div class="wrapperSlider">
                            <input type="range" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <!-- Ainda vou melhorar o estilo desse modal -->
</wm-modal>

<link rel="stylesheet" type="text/css" href="pages/buscaservicos/estilo.css" />
<script src="pages/buscaservicos/script.js"></script>