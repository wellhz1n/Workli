<script src="pages/chat/script.js" type="application/javascript"></script>

<div class="col-12 p-0 m-0" style="overflow-y: hidden;">
    <div class="row p-0 m-0">

        <div style="height: 89vh" id="barlateral" :class="['m-0','p-0',dataVue.menuLateral?null:'close']">
            <div class="categories_box h-100 d-flex flex-column justify-content-center align-items-center ">
                <div @click="dataVue.menuLateral = !dataVue.menuLateral" class="IconeMenuBar">
                    <span><i class="fas fa-chevron-right"></i></span>
                </div>
                <div class="scroolChatProjeto">
                    <div v-if="dataVue.ListaDeProjetos == undefined">
                        <wm-loading></wm-loading>
                    </div>
                    <div v-else-if="dataVue.ListaDeProjetos.length > 0 ">

                        <div :class="['ItemProjetoChat',item == dataVue.ChatSelecionado?'selecionado': null]" @click="dataVue.ChatClick(item)" v-for="item in dataVue.ListaDeProjetos">
                            <div class="ImageContainerChatItem">
                                <div class="Dados">
                                    <div class="UsuarioProjeto">
                                        <wm-user-img :img="item.imagem_usuario" class="imagemUsuario" style="margin-bottom: -10px;" class_icone="iconeImagemNull" class_imagem="imagemU"></wm-user-img>
                                        <p class="font_Poopins text-white" :style="[{'font-size': item.nome.length > 15?'10px':'14px'},{'margin': '0px'}]">{{item.nome}}</p>
                                    </div>
                                    <div class="textos">
                                        <h3 :style="[{'font-size': item.titulo.length > 15?'14px':'20px'}]" class="font_Poopins_B text-white">{{item.titulo}}</h3>
                                        <p class="font_Poopins text-white" style="font-size: 14px;margin: 0px;"><i class="far fa-clock text-white"></i> {{item.postado}}</p>
                                        <p class="font_Poopins text-white" style="font-size: 14px;margin: 0px;">
                                            <span v-show="dataVue.UsuarioContexto.NIVEL_USUARIO == 1">
                                                <i class="far fa-comments"></i>
                                            </span>
                                            <span v-show="dataVue.UsuarioContexto.NIVEL_USUARIO == 0">
                                                <i class="fas fa-users"></i>
                                            </span>
                                            {{item.MSG}}</p>
                                    </div>
                                </div>
                                <img :src="item.imagem_servico != null?'data:image/jpg;base64,'+item.imagem_servico :'src/img/background/background.png'" alt="" class="ImagemItemProjeto">
                            </div>
                        </div>
                    </div>

                    <div v-else>
                        <wm-error mensagem="Nada Encontrado"></wm-error>
                    </div>

                </div>
            </div>
        </div>
        <div v-if="dataVue.Carregando" class="col">
            <wm-loading></wm-loading>
        </div>

            <div v-else-if="!dataVue.Carregando" style="height: 89vh" class="col-12 m-0 p-0 " @click="dataVue.menuLateral = false" id="CHAT">
            <div>
            <div class="categories_box h-100  d-flex flex-column justify-content-center align-items-center ">
                    <div  class="col-12 p-0 m-0 " id="HeaderCHAT">
                        <div class="col-12 categories_box  HeaderTamanho d-flex flex-column justify-content-center align-items-center ">
                            <div class="headerChat">
                                <span @click="dataVue.BackButton()" class="col-3" ><div v-if="dataVue.ConversaSelecionada != null" ><i class="fas fa-arrow-left"></i></div></span>
                                <p class="col-6 font_Poopins_SB" v-html="dataVue.HeaderTitulo"></p>
                                <a style="cursor: pointer;" class="col-3 btn btn-success text-white">Abrir Projeto</a>
                            </div>
                        </div>
                    </div>
            </div>   
                    <div class="col-12 m-0 p-0">
                    <div v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0 && !dataVue.MostraChat" class="ListaDeChats">
                        <div class="itemConversas" @click="dataVue.ConversaClick(i)" v-for="i in dataVue.ListaDeConversas">
                            <wm-user-img :img="i.imagem" class="imagemUsuario" style="margin-bottom: -10px;" class_icone="iconeImagemNull" class_imagem="imagemU1"></wm-user-img>
                            <p class="font_Poopins_SB">{{i.nome}}</p>
                        </div>
                    </div>
                    <div  v-else class="col-12 m-0 p-0">
                        <div style="height: 80vh !important" class="categories_box  d-flex flex-column justify-content-center align-items-center ">
                            <div v-if="dataVue.MostraChat" class="col-12 mt-1 p-0" >
                            <wm-chat heigth="60vh" :userpropostaimage=" dataVue.UsuarioContexto.NIVEL_USUARIO == 0?dataVue.ConversaSelecionada.imagem:dataVue.ChatSelecionado.imagem_usuario"
                            :mensagens="dataVue.Mensagens" 
                            :idusuariodestinatario="JSON.parse(dataVue.UsuarioContexto.NIVEL_USUARIO == 0?dataVue.ConversaSelecionada.id:dataVue.ChatSelecionado.id_usuario)" v-on:novamensagem="M=> dataVue.NovaMensagem(M)"></wm-chat>
                            </div>
                            </div>
                    </div>
                    </div>

                </div>
            </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="pages/chat/estilo.css" />