<link rel="stylesheet" href="pages/notificacoes/estilo.css">
<div class="m-3 mx-5 p-2  ContainerWrapperGeralNotificacoes" style="display: flex;
    flex-direction: column;
    justify-content: center;
">
    <nav>
        <div class="nav nav-tabs" style="display: flex; justify-content: space-around;" id="nav-tab" role="tablist">
            <a @click=" ()=>{
                dataVue.Tabs.Notificacao = true;
                dataVue.Tabs.Propostas = false;}" :style="[{'border-top-left-radius': '5px'},{display: 'flex'}, dataVue.UsuarioContexto.NIVEL_USUARIO == 2?{'border-top-right-radius': '5px'}:null]" :class="['nav-item', 'nav-link', dataVue.UsuarioContexto.NIVEL_USUARIO == 2?'w-100':'w-50',dataVue.Tabs.Notificacao == true?'active':null]" id="nav-notificacoes-tab">Notificações <div style="height: 10px;width: 10px; background-color: red;border-radius: 100%;opacity: 0.8;" v-if="dataVue.NotificacaoNumero > 0"></div></a>
            <a @click=" ()=>{
                dataVue.Tabs.Notificacao = false;
                dataVue.Tabs.Propostas = true;}" v-if=" dataVue.UsuarioContexto.NIVEL_USUARIO != 2" style="border-top-right-radius: 5px;display: flex;" :class="['nav-item', 'nav-link','w-50',dataVue.Tabs.Propostas == true?'active':null]" id="nav-proposta-tab">Propostas <div style="height: 10px;width: 10px; background-color: red;border-radius: 100%;opacity: 0.8;" v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0 && dataVue.Propostas.listaN.length > 0 ||dataVue.Propostas.listaP.length > 0"></div></a>

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent" style="background-color: white;padding: 10px;">
        <transition name="fade" mode="out-in">
            <div key=1 v-if="dataVue.Tabs.Notificacao" class="" id="nav-home">
                <?php require('Tabs/TabN.php') ?>

            </div>
            <div key=2 v-else-if="dataVue.Tabs.Propostas" id="nav-profile">
                <div v-if="dataVue.UsuarioContexto.NIVEL_USUARIO == 0">
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
                    <div v-else>
                        <p>Funcionario</p>
                    </div>

                </div>
            </div>
        </transition>
    </div>
</div>
<script type="" src="pages/notificacoes/script.js"></script>