<script src="pages/chat/script.js" type="application/javascript"></script>

<div class="col-12 p-0 m-0">
    <div class="row p-0 m-0">

        <div style="height: 89vh" id="barlateral" :class="['col-3','m-0','p-0',dataVue.menuLateral?null:'close']">
            <div class="categories_box h-100 d-flex flex-column justify-content-center align-items-center ">
                <div @click="dataVue.menuLateral = !dataVue.menuLateral" class="IconeMenuBar">
                    <i class="fas fa-chevron-right"></i>
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
                <div class="categories_box h-100  d-flex flex-column justify-content-center align-items-center ">
                    <div style="height: 9vh !important" class="col-12 p-0 m-0 " id="HeaderCHAT">
                        <div style="height: 9vh !important" class="categories_box  d-flex flex-column justify-content-center align-items-center ">
                            <div class="headerChat">
                                <span><i class="fas fa-arrow-left"></i></span>
                                <p class="font_Poopins_SB">Selecione Uma Conversa</p>
                                <a style="cursor: pointer;" class="btn btn-success text-white">Abrir Projeto</a>
                            </div>
                        </div>
                    </div>
                    <div v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0" class="ListaDeChats">
                        <div class="itemConversas" v-for="i in 60">
                            <wm-user-img :img="null" class="imagemUsuario" style="margin-bottom: -10px;" class_icone="iconeImagemNull" class_imagem="imagemTamanhoUser"></wm-user-img>
                            <p class="font_Poopins_SB">Mateus</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="pages/chat/estilo.css" />