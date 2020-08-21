$(document).ready(async () => {
    //#region Vuedata
    dataVue.UsuarioContexto.NIVEL_USUARIO = await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO);
    await app.$set(dataVue, 'Tabs', { Notificacao: false, Propostas: true });
    await app.$set(dataVue, 'TabNCategorias', GetTipoMensagem());
    await app.$set(dataVue, "TabNPagina", 1);
    await app.$set(dataVue, "TabNListCarregando", false);
    await app.$set(dataVue, "TabNCarregando", true);

    await app.$set(dataVue, "TabNList", []);
    await app.$set(dataVue, "TabNPageController", { paginas: 1, pagina_Atual: 1 })
    //#endregion

    await BuscaListaNotificacoes();
    await app.$watch("dataVue.TabNPageController", async function (a, o) {
        await BuscaListaNotificacoes();
    }, { deep: true });
    await app.$watch("dataVue.TabNCategorias", async function (a, o) {
        await BuscaListaNotificacoes();
    }, { deep: true });
    document.addEventListener("BuscaNotificacao", async () => {
        await BuscaListaNotificacoes();
    });
    //await WMExecutaAjax("NotificacoesBO","BuscaNotificacoesFormatadoComParametros",{PARAMETROS:[]})
    $('#nav-notificacoes-tab').on('click', () => {
        app.dataVue.Tabs.Notificacao = true;
        app.dataVue.Tabs.Propostas = false;
    });
    $('#nav-proposta-tab').on('click', () => {
        app.dataVue.Tabs.Notificacao = false;
        app.dataVue.Tabs.Propostas = true;
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