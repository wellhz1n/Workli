<link rel="stylesheet" href="pages/notificacoes/estilo.css">
<script type="" src="pages/notificacoes/script.js"></script>
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
                dataVue.Tabs.Propostas = true;}" v-if=" dataVue.UsuarioContexto.NIVEL_USUARIO != 2" 
                style="border-top-right-radius: 5px;display: flex;" 
                :class="['nav-item', 'nav-link','w-50',dataVue.Tabs.Propostas == true?'active':null]" 
                id="nav-proposta-tab">Propostas <div 
                style="height: 10px;width: 10px; background-color: red;border-radius: 100%;opacity: 0.8;" 
                v-if="(dataVue.UsuarioContexto.NIVEL_USUARIO == 0 && ((dataVue.Propostas !== undefined && dataVue.Propostas.listaN.length > 0 )|| (dataVue.Propostas !== undefined && dataVue.Propostas.listaP.length > 0)))
                ||
                (dataVue.UsuarioContexto.NIVEL_USUARIO == 1 && (dataVue.TabPFuncionarioPossuiAprovada !== undefined && dataVue.TabPFuncionarioPossuiAprovada ))
                "></div></a>

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent" style="background-color: white;padding: 10px;">
        <transition name="fade" mode="out-in">
            <div key=1 v-if=" dataVue.Tabs!== undefined && dataVue.Tabs.Notificacao" class="" id="nav-home">
                <?php require('Tabs/TabN.php') ?>

            </div>
            <div key=2 v-else-if="dataVue.Tabs!== undefined && dataVue.Tabs.Propostas" id="nav-profile">
                <?php require('Tabs/TabP.php') ?>
            </div>
        </transition>
    </div>
</div>