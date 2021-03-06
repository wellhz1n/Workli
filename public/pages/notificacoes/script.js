$("#Titulo").text("Notificações e Propostas");
$("document").ready(()=>{

    BloquearTela()

})
$(document).ready(async () => {

    //#region Vuedata
    dataVue.UsuarioContexto.NIVEL_USUARIO = await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO);
     app.$set(dataVue, "NotificacaoNumeroTab", 0);
     app.$set(dataVue, 'Tabs', { Notificacao: true, Propostas: false });
    //#endregion
    var Paramns = GetParam();
    if (Paramns.length > 0) {
        if (Paramns.filter(x => { return Object.entries(x)[0][0] == 'P' })) {
            app.dataVue.Tabs.Propostas = true;
            app.dataVue.Tabs.Notificacao = false;
        }
    }
    setTimeout(() => {

        document.addEventListener("BuscaNotificacao", async (a) => {
            await BuscaListaNotificacoes(false);
            await BuscaPropostas();
        });
    }, 8000);




    app.dataVue.TabNCarregando = false;

    DesbloquearTela();


});

const GetTipoMensagem = () => {
    return {
        Info: false,
        Avaliacoes: false,
        PropostaRecebida: false,
        Chat: false,
        Avisos: false,
        Cancelados: false,
        Concluidos: false
    }
}
const GetSituacaoProposta = () => {
    return {
        Pendente: false,
        Em_Andamento: false,
        Rejeitada: false,
        Aprovada: false,
        Concluidos: false
    }
}
const GetTipoNotificacoesArray = (TipoMensagens = GetTipoMensagem()) => {
    var arr = [];
    if (TipoMensagens.Avisos)
        arr.push(TipoNotificacao.ALERT);
    if (TipoMensagens.Avaliacoes)
        arr.push(TipoNotificacao.AVALIAR_PROJETO)
    if (TipoMensagens.PropostaRecebida)
        arr.push(TipoNotificacao.PROPOSTA_RECEBIDA)
    if (TipoMensagens.Cancelados)
        arr.push(TipoNotificacao.ERROR);
    if (TipoMensagens.Chat)
        arr.push(TipoNotificacao.CHAT);
    if (TipoMensagens.Concluidos)
        arr.push(TipoNotificacao.SUCCESS);
    if (TipoMensagens.Info)
        arr.push(TipoNotificacao.DEFAULT);
    return arr;
}
const GetSituacaoArray = (SituacaoProposta = GetSituacaoProposta()) => {
    var arr = [];
    if (SituacaoProposta.Pendente)
        arr.push(Situacao.Pendente);
    if (SituacaoProposta.Em_Andamento)
        arr.push(Situacao.Andamento);
    if (SituacaoProposta.Rejeitada)
        arr.push(Situacao.Rejeitada);
    if (SituacaoProposta.Concluidos)
        arr.push(Situacao.Concluido);
    if (SituacaoProposta.Aprovada)
        arr.push(Situacao.Aprovada);
    return arr;
}
const BuscaListaNotificacoes = async (carregando = true) => {
    if (carregando) {
        app.dataVue.TabNListCarregando = true;
    }
    dataVue.NotificacaoNumeroTab = await WMExecutaAjax("NotificacoesBO", "GetNumeroNotificacoes", { CONTAPROPOSTA: false });
    var parans = GetTipoNotificacoesArray(app.dataVue.TabNCategorias);
    var result = await WMExecutaAjax("NotificacoesBO",
        "BuscaNotificacoesFormatadoComParametros", { PARAMETROS: parans, PAGINA: app.dataVue.TabNPageController.pagina_Atual })
    if (result.error == undefined) {
        app.dataVue.TabNPageController.paginas = result.paginas;
        app.dataVue.TabNList = result.lista;
    }
    else {
        MostraMensagem("Algo Deu Errado", ToastType.ERROR, "Diacho!!");
    }
    app.dataVue.TabNListCarregando = false;

}

async function BuscaPropostas() {
    if (dataVue.UsuarioContexto.NIVEL_USUARIO == '0') {

        $busca = await WMExecutaAjax("PropostaBO", "BuscaPropostasPendentesClientes", { IDPROJETO: dataVue.TabPFiltro.Projeto });
        if ($busca.error == undefined) {
            app.dataVue.Propostas.listaP = $busca.listaP;
            app.dataVue.Propostas.listaN = $busca.listaN;
            app.dataVue.PropostasCarregando = false;
        }
    }
    else {
        $busca = await WMExecutaAjax("PropostaBO", "BUSCAPROPOSTASFUNCIONARIOTAB", { FILTROS: GetSituacaoArray(app.dataVue.TabPSituacaoProposta), PAGINA: app.dataVue.TabPropostaFuncinarioTab.pagina_Atual })
        if ($busca.error == undefined) {
            app.dataVue.TabPropostaFuncinarioTab.paginas = $busca.paginas;
            app.dataVue.PropostaFuncionario = $busca.lista;
            app.dataVue.TabPFuncionarioPossuiAprovada = $busca.possuiAprovada;
            app.dataVue.PropostaFuncionarioCarregando = false;
        }
    }
}
