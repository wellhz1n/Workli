$(document).ready(async () => {
    //#region Vuedata
    dataVue.UsuarioContexto.NIVEL_USUARIO = await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO);
    await app.$set(dataVue, 'Tabs', { Notificacao: true, Propostas: false });
    //#endregion

 
    document.addEventListener("BuscaNotificacao", async () => {
        await BuscaListaNotificacoes();
        await BuscaPropostas();
    });

    app.dataVue.TabNCarregando = false;



 
});

const GetTipoMensagem = () => {
    return {
        Info: false,
        Chat: false,
        Avisos: false,
        Cancelados: false,
        Concluidos: false
    }
}
const GetTipoNotificacoesArray = (TipoMensagens = GetTipoMensagem()) => {
    var arr = [];
    if (TipoMensagens.Avisos)
        arr.push(TipoNotificacao.ALERT);
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
const BuscaListaNotificacoes = async () => {
    app.dataVue.TabNListCarregando = true;
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
    $busca = await WMExecutaAjax("PropostaBO", "BuscaPropostasPendentesClientes", { IDPROJETO: dataVue.TabPFiltro.Projeto });
    if ($busca.error == undefined) {
        app.dataVue.Propostas.listaP = $busca.listaP;
        app.dataVue.Propostas.listaN = $busca.listaN;
        app.dataVue.PropostasCarregando = false;
    }
}
