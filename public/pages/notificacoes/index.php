<link rel="stylesheet" href="pages/notificacoes/estilo.css">
<div class="m-3 mx-5 p-2 " style="display: flex;
    flex-direction: column;
    justify-content: center;
">
    <nav>
        <div class="nav nav-tabs" style="display: flex; justify-content: space-around;" id="nav-tab" role="tablist">
            <a :style="[{'border-top-left-radius': '5px'},{display: 'flex'}, dataVue.UsuarioContexto.NIVEL_USUARIO == 2?{'border-top-right-radius': '5px'}:null]" :class="['nav-item', 'nav-link', dataVue.UsuarioContexto.NIVEL_USUARIO == 2?'w-100':'w-50',dataVue.Tabs.Notificacao == true?'active':null]" id="nav-notificacoes-tab">Notificações <div style="height: 10px;width: 10px; background-color: red;border-radius: 100%;opacity: 0.8;" v-if="dataVue.NotificacaoNumero > 0"></div></a>
            <a v-if=" dataVue.UsuarioContexto.NIVEL_USUARIO != 2" style="border-top-right-radius: 5px;" :class="['nav-item', 'nav-link','w-50',dataVue.Tabs.Propostas == true?'active':null]" id="nav-proposta-tab">Propostas</a>

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent" style="background-color: white;padding: 10px;">
        <transition name="fade" mode="out-in">
            <div key=1 v-if="dataVue.Tabs.Notificacao" class="" id="nav-home">
                <div class=" WrapperNotificacoesTab" style="height: 60vh;">
                    <div v-if="!dataVue.TabNCarregando">

                        <div class="col-12 mt-3" style=" align-self: center;display: flex;justify-content: center;">
                            <wm-paginacao :totaldepaginas="dataVue.TabNPageController.paginas" :paginaatual="JSON.parse(dataVue.TabNPageController.pagina_Atual)" v-on:changepagina="(a)=>{dataVue.TabNPageController.pagina_Atual= a}" />
                        </div>
                        <div class=" WrapperNotificacoesTabItem">

                            <div class="filtrosWrapper">
                                <h2 class="font_Poopins_SB m-0 p-0 mb-3" style="font-size: 18px;">Tipos de Notificação</h2>
                                <span @click="dataVue.TabNCategorias.Info = !dataVue.TabNCategorias.Info" :class="['iconNotiAcao',dataVue.TabNCategorias.Info?'active':null]">
                                    <i style="color:#56b7c9 ;font-size: 20px;" class="fas fa-info"></i>
                                    <p class="mx-2">Informações</p>
                                </span>

                                <span @click="dataVue.TabNCategorias.Chat = !dataVue.TabNCategorias.Chat" :class="['iconNotiAcao',dataVue.TabNCategorias.Chat?'active':null]">

                                    <i style="color:#0f8b8d ;font-size: 20px;margin-left: -8px;" class="fas fa-comments"></i>
                                    <p class="mx-2">Chat</p>

                                </span>
                                <span @click="dataVue.TabNCategorias.Avisos = !dataVue.TabNCategorias.Avisos" :class="['iconNotiAcao',dataVue.TabNCategorias.Avisos?'active':null]">
                                    <i style="color:#ec9a29  ;font-size: 20px;margin-left: -8px;" class="fas fa-exclamation-triangle"></i>
                                    <p class="mx-2">Avisos</p>
                                </span>
                                <span @click="dataVue.TabNCategorias.Cancelados = !dataVue.TabNCategorias.Cancelados" :class="['iconNotiAcao',dataVue.TabNCategorias.Cancelados?'active':null]">
                                    <i style="color:#a8201a ;font-size: 20px;margin-left: -5px;" class="fas fa-times"></i>
                                    <p class="mx-2">Cancelados</p>
                                </span>
                                <span @click="dataVue.TabNCategorias.Concluidos = !dataVue.TabNCategorias.Concluidos" :class="['iconNotiAcao',dataVue.TabNCategorias.Concluidos?'active':null]">
                                    <i style="color:#28a745 ;font-size: 20px;margin-left: -5px;" class="fas fa-check"></i>
                                    <p class="mx-2">Concluidos</p>
                                </span>
                            </div>
                            <div class="col NotificacoesListWrapper">
                                <div v-if="dataVue.TabNList.length != 0 && !dataVue.TabNListCarregando">

                                    <div v-for="item in dataVue.TabNList">

                                        <div v-if="item.tipo == -1" class="dataChatDiv"><span class="dataChatDivTexto">{{item.titulo}}</span></div>
                                        <div style="cursor: pointer;" v-else @click="dataVue.ClickFuncao(this,item)">
                                            <wm-notify :tipo="JSON.parse(item.tipo)" :hora="item.hora" :titulo="item.titulo" :descricao="item.descricao" :subtitulo="{titulo:item.subtitulo,descricao:item.subdescricao}"></wm-notify>
                                        </div>
                                    </div>
                                </div>

                                <div style="width: 75%;" v-else-if="dataVue.TabNList.length == 0 && !dataVue.TabNListCarregando">
                                    <wm-error style="margin-top: 0px !important;" mensagem="Nenhuma Notificação encontrada" />
                                </div>
                                <div v-else style="height: 100%;
    display: flex;
    align-items: center;
    width: 75%;">
                                    <wm-loading />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <wm-loading />
                    </div>

                </div>

            </div>
            <div key=2 v-else-if="dataVue.Tabs.Propostas" id="nav-profile">
                <div class=" WrapperNotificacoesTab" style="height: min-content;">
                    <div v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0">
                        <p class="m-0 p-0" style="font-size: 12px;opacity: 0.6;">*Recomendamos as propostas patrocinadas e as <span style="color: #218838;">brilhantes</span></p>
                        <div class="" style="width: 100%;padding: 3%; margin-top: -2%;">
                            <div class="header" style="display: flex;align-items: center;">
                                <div class="linha col mx-2"></div>
                                <p class="col-sm-4 col-md-2 mx-2 p-0 m-0" style="text-align: center;">Propostas Patrocinadas</p>
                                <div class="linha col mx-2"></div>
                            </div>
                            <div class="col-12 p-2 ScrollVerde" style="height: 90%;
                         overflow-y: auto;">

                                <div class="PropostaItem my-2  ">
                                    <div class="TituloProposta">

                                        <h4 class="font_Poopins_B">Projeto: Criar Case Propostas</h4>
                                        <p class="font_Poopins" style="font-size: 12px;">descricao do projeto e tals jdlksads sdkçad dakaçsd dakçdas dakdçad dkaçdksaç dakçdsa
                                            dsaldkjal sdlkadsla lsdajalkd dsajdlk
                                            daplçdjalksçd adjaslkdjalkd adjlkasdjlsdjl asdajdlkajd djalkd adsjsalkda dlakjdlkad adjlakdjal dadjklaj
                                        </p>
                                        <span style="background-color: #ec9a29;" class="badge badge-pill">Software</span>
                                        <div style="display: flex; align-items: center; height: 60px; ">
                                            <wm-user-img class="imagemProposta" class_icone="iconeImagemNull" class_imagem="imagemTamanhoUser"></wm-user-img>
                                            <div class="d-flex">
                                                <p class="p-0 m-0 ml-1">Rogério</p>
                                                <span class="mx-1"><i style="color: #ec9a29;font-size: 13px;" class="fas fa-star"></i><span style="font-size:12px ;">4</span></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="height: auto;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: space-between;
                                    align-items: center;">
                                        <span class="m-0 p-0 font_Poopins_SB" style="display: flex;color: #ffffff !important ;font-size: 16px;">R$:200</span>
                                        <div class="WrapperBotoesProposta">
                                            <a class="BotoesProposta Recusar"><i class="fas fa-times"></i></a>
                                            <a class="BotoesProposta Aceitar"><i class="fas fa-check"></i></a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="" style="height: 60vh;width: 100%;padding: 3%;">
                            <div class="header" style="display: flex;align-items: center;">
                                <div class="linha col mx-2"></div>
                                <p class="col-sm-4 col-md-2 mx-2 p-0 m-0" style="text-align: center;">Propostas Normais</p>
                                <div class="linha col mx-2"></div>
                            </div>
                            <div class="col-12 p-2  ScrollVerde" style="height: 90%;
                         overflow-y: auto;">

                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p>Funcionario</p>
                    </div>

                </div>
            </div>
        </transition>
    </div>
</div>
<script type="" src="pages/notificacoes/script.js"></script>