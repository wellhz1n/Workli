<script src="pages/chat/script.js" type="application/javascript"></script>

<div class="col-12 p-0 m-0" style="overflow-y: hidden;">
    <div class="row p-0 m-0">

        <div style="height: 89vh" id="barlateral" :class="['m-0','p-0',dataVue.menuLateral?null:'close']">
            <div class="categories_box h-100 d-flex flex-column justify-content-center align-items-center ">
                <div @click="dataVue.menuLateral = !dataVue.menuLateral" class="IconeMenuBar">
                    <span><i class="fas fa-chevron-right"></i></span>
                </div>
                <div class="scroolChatProjeto">
                    <transition name="fade" mode="out-in">
                        <div :key="0" v-if="dataVue.ListaDeProjetos == undefined">
                            <wm-loading></wm-loading>
                        </div>
                        <div :key="1" v-else-if="dataVue.ListaDeProjetos.length > 0 ">

                            <div :class="['ItemProjetoChat',item == dataVue.ChatSelecionado?'selecionado': null]" @click="dataVue.ChatClick(item,$event)" v-for="item in dataVue.ListaDeProjetos">
                                <div class="ImageContainerChatItem">
                                    <div class="Dados">
                                        <div class="UsuarioProjeto">
                                            <wm-user-img :img="item.imagem_usuario" class="imagemUsuario" style="margin-bottom: -10px;" class_icone="iconeImagemNull" class_imagem="imagemU"></wm-user-img>
                                            <p class="font_Poopins text-white" :style="[{'font-size': item.nome.length > 15?'10px':'14px'},{'margin': '0px'}]">{{item.nome}}</p>
                                        </div>
                                        <div class="textos">
                                            <h3 :style="[{'font-size': item.titulo.length > 15?'14px':'20px'}]" class="font_Poopins_B text-white">{{item.titulo}}</h3>
                                            <p class="font_Poopins text-white" style="font-size: 14px;margin: 0px;"><i class="far fa-clock text-white"></i> {{item.postado}}</p>
                                            <div class="d-flex flex-row">
                                                <p class="font_Poopins text-white" style="font-size: 14px;margin: 0px;">
                                                    <span v-show="dataVue.UsuarioContexto.NIVEL_USUARIO == 1">
                                                        <i class="far fa-comments"></i>
                                                    </span>
                                                    <span v-show="dataVue.UsuarioContexto.NIVEL_USUARIO == 0">
                                                        <i class="fas fa-users"></i>
                                                    </span>
                                                    {{item.MSG}}</p>
                                                <p v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0" class="font_Poopins text-white ml-2" style="font-size: 14px;margin: 0px;">
                                                    <span v-show="item.situacao_servico == '0'">
                                                        <i class="fas fa-project-diagram"></i>
                                                    </span>
                                                    <span v-show="item.situacao_servico == '1'">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                                    <span v-show="item.situacao_servico == '2'">
                                                        <i class="fas fa-tasks"></i>
                                                    </span>
                                                    <span v-show="item.situacao_servico == '3'">
                                                        <i class="fa fa-times"></i>
                                                    </span>
                                                    <span v-show="item.situacao_servico == '4'">
                                                        <i class="fas fa-check-double"></i>
                                                    </span>
                                                </p>
                                                <p v-else class="font_Poopins text-white ml-2" style="font-size: 14px;margin: 0px;">
                                                    <span v-show="item.situacao_proposta == '0'">
                                                        <i class="fas fa-project-diagram"></i>
                                                    </span>
                                                    <span v-show="item.situacao_proposta == '1'">
                                                        <i class="fas fa-clock"></i>
                                                    </span>
                                                    <span v-show="item.situacao_proposta == '2'">
                                                        <i class="fas fa-tasks"></i>
                                                    </span>
                                                    <span v-show="item.situacao_proposta == '3'">
                                                        <i class="fas fa-times"></i>
                                                    </span>
                                                    <span v-show="item.situacao_proposta == '4'">
                                                        <i class="fas fa-check-double"></i>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <img :src="item.imagem_servico != null?'data:image/jpg;base64,'+item.imagem_servico :'src/img/background/background.png'" alt="" class="ImagemItemProjeto">
                                </div>
                            </div>
                        </div>

                        <div :key="2" v-else>
                            <wm-error mensagem="Nada Encontrado"></wm-error>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
        <transition name="fade" mode="out-in">
            <div :key="0" v-if="dataVue.Carregando" class="col">
                <wm-loading></wm-loading>
            </div>
            <div :key="1" v-else-if="!dataVue.Carregando" style="height: 89vh" class="col-12 m-0 p-0 " @click="dataVue.menuLateral = false" id="CHAT">
                <div>
                    <div class="categories_box h-100  d-flex flex-column justify-content-center align-items-center ">
                        <div class="col-12 p-0 m-0 " id="HeaderCHAT">
                            <div class="col-12 categories_box  HeaderTamanho d-flex flex-column justify-content-center align-items-center ">

                                <div class="headerChat" :style="[{'justify-content': dataVue.UsuarioContexto.NIVEL_USUARIO != 0 || dataVue.ChatSelecionado == null?'unset !important':'space-between !important' }]">
                                    <span @click="dataVue.BackButton()" class="col-3">
                                        <transition name="fade" mode="out-in">
                                            <div :key="0" v-if="dataVue.ConversaSelecionada != null"><i class="fas fa-arrow-left"></i></div>
                                        </transition>
                                    </span>
                                    <p class="col-6 font_Poopins_SB" v-html="dataVue.HeaderTitulo"></p>
                                    <transition name="fade" mode="out-in">
                                        <a :key="0" v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0 && dataVue.ChatSelecionado != null " @click="RedirectComParan('meusprojetos',[{chave:'P',valor:dataVue.ChatSelecionado.id_servico}],true)" style="cursor: pointer;" class="col-3 btn btn-success text-white">Abrir Projeto</a>
                                    </transition>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 m-0 p-0">
                        <transition name="fade" mode="out-in">
                            <div :key="0" v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0 && !dataVue.MostraChat" class="ListaDeChats">
                                <transition-group class="d-contents" name="fade" mode="out-in">
                                    <div :key="JSON.parse(i.id)" class="itemConversas" @click="dataVue.ConversaClick(i)" v-for="i in dataVue.ListaDeConversas">
                                        <wm-user-img :img="i.imagem" class="imagemUsuario" style="margin-bottom: -10px;" class_icone="iconeImagemNull" class_imagem="imagemU1"></wm-user-img>
                                        <p class="font_Poopins_SB">{{i.nome}}</p>
                                    </div>
                                </transition-group>
                            </div>
                            <div :key="1" v-else class="col-12 m-0 p-0">
                                <div style="height: 80vh !important" class="categories_box  d-flex flex-column justify-content-center align-items-center ">
                                    <transition name="fade" mode="out-in">
                                        <div :key="0" v-if="dataVue.MostraChat" class="col-12 mt-1 p-0">
                                            <wm-chat heigth="68vh" :exibemandar="dataVue.UsuarioContexto.NIVEL_USUARIO == 0? (dataVue.ChatSelecionado.situacao_servico != '3' && dataVue.ChatSelecionado.situacao_servico != '4')&&(dataVue.ConversaSelecionada.situacao_proposta != '3' && dataVue.ConversaSelecionada.situacao_proposta != '4') :dataVue.ChatSelecionado.situacao_proposta != '3' && dataVue.ChatSelecionado.situacao_proposta != '4'" :userpropostaimage=" dataVue.UsuarioContexto.NIVEL_USUARIO == 0?dataVue.ConversaSelecionada.imagem:dataVue.ChatSelecionado.imagem_usuario" :mensagens="dataVue.Mensagens" :idusuariodestinatario="JSON.parse(dataVue.UsuarioContexto.NIVEL_USUARIO == 0?dataVue.ConversaSelecionada.id:dataVue.ChatSelecionado.id_usuario)" v-on:novamensagem="M=> dataVue.NovaMensagem(M)"></wm-chat>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </transition>
                    </div>

                </div>
            </div>
        </transition>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="pages/chat/estilo.css" />