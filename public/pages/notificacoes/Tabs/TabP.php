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
                        <wm-proposta v-for="p in dataVue.Propostas.listaP" @redireciona_usuario="RedirecionaPerfil(p.id_usuario)" :key="JSON.parse(p.id)" :titulo="p.Titulo" :descricao="p.descricao" :avaliacao="Math.floor(p.avaliacao_media)" :nome="p.funcionario" :imagem_funcionario="p.imagem" :categoria="p.categoria" :valor="p.valor" :brilha="p.destacado == 1?true:false" v-on:cancelar="(item)=>{ dataVue.CancelaProposta(p.id)}" v-on:aprovar="(item)=>{dataVue.AprovaProposta(p.id)}"></wm-proposta>
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
                        <wm-proposta  v-for="p in dataVue.Propostas.listaN" @redireciona_usuario="RedirecionaPerfil(p.id_usuario)" :key="p.id" :titulo="p.Titulo" :descricao="p.descricao" :avaliacao="Math.floor(p.avaliacao_media)" :nome="p.funcionario" :imagem_funcionario="p.imagem" :categoria="p.categoria" :valor="p.valor" :brilha="p.destacado == 1?true:false" v-on:cancelar="(item)=>{ dataVue.CancelaProposta(p.id)}" v-on:aprovar="(item)=>{dataVue.AprovaProposta(p.id)}"></wm-proposta>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <wm-modal-botoes-generico
    id="modalConfirmacao"
    :visivel="dataVue.modalVisivelControllerConfirmacao" 
    @fechar-modal="(confirmacao) => {dataVue.fechaModalConfirmacao(confirmacao)}"
    text_botao_salvar="Aprovar">
        <template v-slot:titulo>
            Deseja Aprovar a Proposta?
        </template>
        <template v-slot:descricao>
                <p>Após aprovar a proposta será deduzido da sua carteiro o valor de <strong>R$:{{[...dataVue.Propostas.listaN,...dataVue.Propostas.listaP].filter(x=> x.id == dataVue.idPropostaSelecionada)[0].valor}}</strong> </p>
        </template>
    </wm-modal-botoes-generico>


</div>
<!-- FIM Cliente -->

<!-- Funcionario -->
<div v-else>
    <div class=" WrapperNotificacoesTab" style="height: 60vh;">
        <transition name="fade" mode="out-in">
            <div :key="0" v-if="dataVue.TabPFuncionarioCarregando !== undefined && !dataVue.TabPFuncionarioCarregando">
                <transition name="fade" mode="out-in">
                    <div class="col-12 mt-3" v-if="dataVue.TabPropostaFuncinarioTab.paginas > 1" style=" align-self: center;display: flex;justify-content: center;">
                        <wm-paginacao :totaldepaginas="dataVue.TabPropostaFuncinarioTab.paginas" :paginaatual="JSON.parse(dataVue.TabPropostaFuncinarioTab.pagina_Atual)" v-on:changepagina="(a)=>{dataVue.TabPropostaFuncinarioTab.pagina_Atual= a}" />
                    </div>
                </transition>
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
                        <span @click="dataVue.TabPSituacaoProposta.Aprovada = !dataVue.TabPSituacaoProposta.Aprovada; 
                                   " :class="['iconNotiAcao',dataVue.TabPSituacaoProposta !== undefined && dataVue.TabPSituacaoProposta.Aprovada?'active':null]">
                            <i style="color:#2855a7 ;font-size: 20px;margin-left: -5px;" class="fas fa-thumbs-up"></i>
                            <p class="ml-2 mr-1">Aprovada<div style="height: 10px;width: 10px; background-color: red;border-radius: 100%;opacity: 0.8;" v-if="(dataVue.UsuarioContexto.NIVEL_USUARIO == 1 && (dataVue.TabPFuncionarioPossuiAprovada !== undefined && dataVue.TabPFuncionarioPossuiAprovada ))
                "></div>
                            </p>
                        </span>
                        <span @click="dataVue.TabPSituacaoProposta.Em_Andamento = !dataVue.TabPSituacaoProposta.Em_Andamento; 
                                   " :class="['iconNotiAcao',dataVue.TabPSituacaoProposta !== undefined && dataVue.TabPSituacaoProposta.Em_Andamento?'active':null]">

                            <i style="color:#0f8b8d ;font-size: 20px;margin-left: -8px;" class="fas fa-tasks"></i>
                            <p class="mx-2">Em Andamento</p>

                        </span>
                        <span @click="dataVue.TabPSituacaoProposta.Concluidos = !dataVue.TabPSituacaoProposta.Concluidos; 
                                   " :class="['iconNotiAcao',dataVue.TabPSituacaoProposta !== undefined && dataVue.TabPSituacaoProposta.Concluidos?'active':null]">
                            <i style="color:#28a745 ;font-size: 20px;margin-left: -5px;" class="fas fa-check-circle"></i>
                            <p class="mx-2">Concluidos</p>
                        </span>
                    </div>
                    <div class="col NotificacoesListWrapper">
                        <transition name="fadefast" mode="out-in">
                            <div style="height: 100%;
                          display: flex;
                         align-items: center;width: 75%;" v-if="dataVue.PropostaFuncionario === undefined || dataVue.PropostaFuncionarioCarregando == undefined ||dataVue.PropostaFuncionarioCarregando">
                                <wm-loading></wm-loading>
                            </div>
                            <div :key="2" v-else-if="dataVue.PropostaFuncionario !== undefined &&  dataVue.PropostaFuncionario.length > 0">
                                <wm-proposta-funcionario  class="list-item" v-for="item in dataVue.PropostaFuncionario" :key="JSON.parse(item.ID)"  @redireciona_usuario="RedirecionaPerfil(item.IDCLIENTE)"  :titulo="item.TITULO" :descricao="item.DESCRICAO" :nome="item.CLIENTE" :idcliente="item.IDCLIENTE" @muda_situacao="({idProposta})=>{
                                    if(dataVue.PropostaFuncionario.length == 1 && dataVue.PropostaFuncionario.filter(a=>{return a.ID == idProposta}).length == 1){
                                        dataVue.PropostaFuncionario =[];
                                         dataVue.TabPFuncionarioPossuiAprovada = false 
                                        }
                                        else
                                            dataVue.PropostaFuncionario = dataVue.PropostaFuncionario.filter(a=>{return a.ID != idProposta});
                                }" :idservico="item.IDSERVICO" :imagem_cliente="item.IMAGEM" :situacao="item.SITUACAO" :categoria="item.CATEGORIA" :valor="item.VALOR" :data="item.DATAPROPOSTA"></wm-proposta-funcionario>
                            </div>
                            <div :key="3" style="width: 75%;" v-else-if=" dataVue.PropostaFuncionario.length == 0 && !dataVue.PropostaFuncionarioCarregando">
                                <wm-error style="margin-top: 0px !important;" mensagem="Nenhuma Proposta foi encontrada" />
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
            <div :key="1" v-else>
                <wm-loading />
            </div>
        </transition>
    </div>
</div>
<!-- FIM Funcionario -->

<script type="" src="pages/notificacoes/Tabs/TabP.js"></script>