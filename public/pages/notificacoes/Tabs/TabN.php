<transition name="fadefast" mode="out-in">
    <div :key="0" class="wrapperMensagemVazia" v-if="dataVue.TabNList !== undefined && dataVue.TabNList.length == 0 &&  Object.values(dataVue.TabNCategorias).filter(r=> r==true).length == 0 && !dataVue.TabNCarregando &&  !dataVue.TabNListCarregando ">
        <p>Não encontramos nenhuma notificação para você, mas não se assuste logo você receberá uma.</p>
        <img src="src/img/svg/undraw_online_message_xq4c.svg" />
    </div>
    <div :key="1" v-else class=" WrapperNotificacoesTab" style="height: 60vh;">
        <div v-if="!dataVue.TabNCarregando">
            <transition name="fadefast" mode="out-in">

                <div :key="0" v-if="dataVue.TabNPageController !== undefined && dataVue.TabNPageController.paginas > 1" class="col-12 mt-3" style=" align-self: center;display: flex;justify-content: center;">
                    <wm-paginacao :totaldepaginas="dataVue.TabNPageController.paginas" :paginaatual="JSON.parse(dataVue.TabNPageController.pagina_Atual)" v-on:changepagina="(a)=>{dataVue.TabNPageController.pagina_Atual= a}" />
                </div>
            </transition>

            <div class=" WrapperNotificacoesTabItem">

                <div class="filtrosWrapper">
                    <h2 class="font_Poopins_SB m-0 p-0 mb-3" style="font-size: 18px;">Tipos de Notificação</h2>
                    <span @click="dataVue.TabNCategorias.Info = !dataVue.TabNCategorias.Info; 
                                   " :class="['iconNotiAcao',dataVue.TabNCategorias.Info?'active':null]">
                        <i style="color:#56b7c9 ;font-size: 20px;" class="fas fa-info"></i>
                        <p class="mx-2">Informações</p>
                    </span>
                    <span v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0" @click="dataVue.TabNCategorias.Avaliacoes = !dataVue.TabNCategorias.Avaliacoes; 
                                   " :class="['iconNotiAcao',dataVue.TabNCategorias.Avaliacoes?'active':null]">
                        <i style="color:#422057 ;font-size: 20px;margin-left: -8px;" class="fas fa-star"></i>
                        <p class="mx-2">Avaliações</p>
                    </span>
                    <span v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 1" @click="dataVue.TabNCategorias.PropostaRecebida = !dataVue.TabNCategorias.PropostaRecebida; 
                                   " :class="['iconNotiAcao',dataVue.TabNCategorias.PropostaRecebida?'active':null]">
                        <i style="color:#2861a7 ;font-size: 20px;margin-left: -8px;" class="fas fa-file-signature"></i>
                        <p class="mx-1">Proposta Recebida</p>
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
                    <transition name="fadefast" mode="out-in">
                        <div :key="0" v-if="dataVue.TabNList.length != 0 && !dataVue.TabNListCarregando">
                            <div v-for="item in dataVue.TabNList">

                                <div v-if="item.tipo == -1" class="dataChatDiv"><span class="dataChatDivTexto">{{item.titulo}}</span></div>
                                <div style="cursor: pointer;" v-else @click="dataVue.ClickFuncao(this,item)">
                                    <wm-notify :key="item.id_usuario_criacao+item.id" :tipo="JSON.parse(item.tipo)" :hora="item.hora" :titulo="item.titulo" :descricao="item.descricao" :subtitulo="{titulo:item.subtitulo,descricao:item.subdescricao}"></wm-notify>
                                </div>
                            </div>
                        </div>

                        <div :key="1" style="width: 75%;" v-else-if="dataVue.TabNList.length == 0 && !dataVue.TabNListCarregando">
                            <wm-error style="margin-top: 0px !important;" mensagem="Nenhuma Notificação encontrada" />
                        </div>
                        <div :key="2" v-else style="height: 100%;
    display: flex;
    align-items: center;
    width: 75%;">
                            <wm-loading />
                        </div>
                    </transition>
                </div>
            </div>
        </div>
        <div :key="2" v-else>
            <wm-loading />
        </div>

    </div>
</transition>
<script type="" src="pages/notificacoes/Tabs/TabN.js"></script>