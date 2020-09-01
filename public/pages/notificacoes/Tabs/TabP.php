<!-- Cliente -->
<div v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == '0'">
    <div class="wrapperMensagemVazia" v-if="dataVue.Propostas !== undefined && dataVue.Propostas.listaP.length == 0 && dataVue.Propostas.listaN.length == 0 &&  !dataVue.PropostasCarregando && dataVue.TabPFiltro.Projeto == null ">
        <p>Não encontramos nenhuma proposta para seus projetos, mas não se assuste é normal demorar um pouco para conseguir sua primeira proposta.</p>
        <img src="src/img/svg/undraw_ideas_flow_cy7b.svg" />
    </div>
    <div v-else class=" WrapperNotificacoesTab" style="height: min-content;">
        <wm-select ref="SeletorFiltra" style="align-self: flex-end;" v-if="dataVue.ProjetoSeletor != undefined" class="col-md-3" v-bind="dataVue.ProjetoSeletor"></wm-select>
        <p class="m-0 p-0" style="font-size: 12px;opacity: 0.6;">*Recomendamos as propostas patrocinadas e as <span style="color: #218838;">brilhantes</span></p>

        <div v-if="dataVue.Propostas.listaP.length == 0 && dataVue.Propostas.listaN.length == 0 && dataVue.TabPFiltro.Projeto != null" style="display: flex;flex-direction: column; align-items: center;justify-content: center;padding: 2%;">
            <p>Não encontramos nenhuma proposta para esse projeto</p>
            <img style="height: 40vh;" src="src/img/svg/undraw_blank_canvas_3rbb.svg" />
        </div>
        <div v-else>
            <div class="" style="width: 100%;padding: 3%; margin-top: -2%;">
                <div class="header" style="display: flex;align-items: center;">
                    <div class="linha col mx-2"></div>
                    <p class="col-sm-4 col-md-2 mx-2 p-0 m-0" style="text-align: center;">Propostas Patrocinadas</p>
                    <div class="linha col mx-2"></div>
                </div>
                <div class="col-12 p-2 ScrollVerde" style="height: 90%;
                         overflow-y: auto;">
                    <div class="mt-5" v-if="dataVue.Propostas === undefined || dataVue.PropostasCarregando == undefined ||dataVue.PropostasCarregando">
                        <wm-loading></wm-loading>
                    </div>
                    <div v-else-if="!dataVue.PropostasCarregando && dataVue.Propostas.listaP.length > 0 ">
                        <wm-proposta v-for="p in dataVue.Propostas.listaP" :key="JSON.parse(p.id)" :titulo="p.Titulo" :descricao="p.descricao" :avaliacao="Math.floor(p.avaliacao_media)" :nome="p.funcionario" :imagem_funcionario="p.imagem" :categoria="p.categoria" :valor="p.valor" :brilha="p.destacado == 1?true:false" v-on:cancelar="(item)=>{ dataVue.CancelaProposta(p.id)}" v-on:aprovar="(item)=>{dataVue.AprovaProposta(p.id)}"></wm-proposta>
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
                    <div style="height:100%;display: flex;align-items: center;" v-if="dataVue.Propostas === undefined || dataVue.PropostasCarregando == undefined ||dataVue.PropostasCarregando">
                        <wm-loading></wm-loading>
                    </div>
                    <div v-else-if="!dataVue.PropostasCarregando && dataVue.Propostas.listaN.length > 0 ">
                        <wm-proposta v-for="p in dataVue.Propostas.listaN" :key="p.id" :titulo="p.Titulo" :descricao="p.descricao" :avaliacao="Math.floor(p.avaliacao_media)" :nome="p.funcionario" :imagem_funcionario="p.imagem" :categoria="p.categoria" :valor="p.valor" :brilha="p.destacado == 1?true:false" v-on:cancelar="(item)=>{ dataVue.CancelaProposta(p.id)}" v-on:aprovar="(item)=>{dataVue.AprovaProposta(p.id)}"></wm-proposta>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
<!-- FIM Cliente -->

<!-- Funcionario -->
<div v-else>
    <div class=" WrapperNotificacoesTab" style="height: 60vh;">
        <div v-if="!dataVue.TabNCarregando">
            <div class="col-12 mt-3" style=" align-self: center;display: flex;justify-content: center;">
                <wm-paginacao :totaldepaginas="dataVue.TabNPageController.paginas" :paginaatual="JSON.parse(dataVue.TabNPageController.pagina_Atual)" v-on:changepagina="(a)=>{dataVue.TabNPageController.pagina_Atual= a}" />
            </div>
            <div class=" WrapperNotificacoesTabItem">

                <div class="filtrosWrapper">
                    <h2 class="font_Poopins_SB m-0 p-0 mb-3" style="font-size: 17px;">Situação da Proposta</h2>
                    <span @click="dataVue.TabPSituacaoProposta.Pendente = !dataVue.TabPSituacaoProposta.Pendente; 
                                   " :class="['iconNotiAcao',dataVue.TabPSituacaoProposta !== undefined && dataVue.TabPSituacaoProposta.Pendente?'active':null]">
                        <i style="color:rgb(152 152 152) ;font-size: 20px;margin-left: -8px;" class="fas fa-clock"></i>
                        <p class="mx-2">Pendente</p>
                    </span>

                    <span @click="dataVue.TabPSituacaoProposta.Rejeitada = !dataVue.TabPSituacaoProposta.Rejeitada; 
                                   " :class="['iconNotiAcao',dataVue.TabPSituacaoProposta !== undefined && dataVue.TabPSituacaoProposta.Rejeitada?'active':null]">
                        <i style="color:#a8201a ;font-size: 20px;margin-left: -5px;" class="fas fa-times"></i>
                        <p class="mx-2">Rejeitada</p>
                    </span>
                    <span  @click="dataVue.TabPSituacaoProposta.Aprovada = !dataVue.TabPSituacaoProposta.Aprovada; 
                                   " :class="['iconNotiAcao',dataVue.TabPSituacaoProposta !== undefined && dataVue.TabPSituacaoProposta.Aprovada?'active':null]">
                        <i style="color:#2855a7 ;font-size: 20px;margin-left: -5px;" class="fas fa-thumbs-up"></i>
                        <p class="mx-2">Aprovada</p>
                    </span>
                    <span  @click="dataVue.TabPSituacaoProposta.Em_Andamento = !dataVue.TabPSituacaoProposta.Em_Andamento; 
                                   " :class="['iconNotiAcao',dataVue.TabPSituacaoProposta !== undefined && dataVue.TabPSituacaoProposta.Em_Andamento?'active':null]">

                        <i style="color:#0f8b8d ;font-size: 20px;margin-left: -8px;" class="fas fa-tasks"></i>
                        <p class="mx-2">Em Andamento</p>

                    </span>
                    <span  @click="dataVue.TabPSituacaoProposta.Concluidos = !dataVue.TabPSituacaoProposta.Concluidos; 
                                   " :class="['iconNotiAcao',dataVue.TabPSituacaoProposta !== undefined && dataVue.TabPSituacaoProposta.Concluidos?'active':null]">
                        <i style="color:#28a745 ;font-size: 20px;margin-left: -5px;" class="fas fa-check-circle"></i>
                        <p class="mx-2">Concluidos</p>
                    </span>
                </div>
                <div class="col NotificacoesListWrapper">
                    <wm-proposta-funcionario titulo="Teste Projeto" descricao="descrição" nome="Cliente" :imagem_cliente="null" :situacao="0" categoria="Só vái" valor="3000"></wm-proposta-funcionario>
                    <wm-proposta-funcionario titulo="Teste Projeto" descricao="descrição" nome="Cliente" :imagem_cliente="null" :situacao="1" categoria="Só vái" valor="3000"></wm-proposta-funcionario>
                    <wm-proposta-funcionario titulo="Teste Projeto" descricao="descrição" nome="Cliente" :imagem_cliente="null" :situacao="3" categoria="Só vái" valor="3000"></wm-proposta-funcionario>
                    <wm-proposta-funcionario titulo="Teste Projeto" descricao="descrição" nome="Cliente" :imagem_cliente="null" :situacao="2" categoria="Só vái" valor="3000"></wm-proposta-funcionario>
                    <wm-proposta-funcionario titulo="Teste Projeto" descricao="descrição" nome="Cliente" :imagem_cliente="null" :situacao="4" categoria="Só vái" valor="3000"></wm-proposta-funcionario>
                </div>
            </div>
        </div>
        <div v-else>
            <wm-loading />
        </div>

    </div>
</div>
<!-- FIM Funcionario -->

<script type="" src="pages/notificacoes/Tabs/TabP.js"></script>