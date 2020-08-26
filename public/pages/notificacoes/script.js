$(document).ready(async () => {
    //#region Vuedata
    dataVue.UsuarioContexto.NIVEL_USUARIO = await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO);
    await app.$set(dataVue, 'Tabs', { Notificacao: true, Propostas: false });
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
    }, { immediate:true,deep: true });
    document.addEventListener("BuscaNotificacao", async () => {
        await BuscaListaNotificacoes();
        await BuscaPropostas();
    });

    app.dataVue.TabNCarregando = false;



    //#region TAB PROPOSTA
    if (dataVue.UsuarioContexto.NIVEL_USUARIO == 0) {

        app.$set(dataVue, "TabPFiltro", { Projeto: null });
        //#region Seletor
        var ProjetoSeletor = () => {
            return {
                id: 'ProjetosSeletor',
                visivel: () => { return true },
                titulo: 'Filtrar por projeto',
                disabled: () => { return false },
                entidade: "TabPFiltro",
                campo: "Projeto",
                limpavel: true,
                icone: false,
                obrigatorio: false,
                ajax: async (ss) => {
                    return await new Promise(async resolve => {

                        var saida = await WMExecutaAjax("ProjetoBO", "GETPROJETOSPORUSUARIOCONTEXTO");
                        saida.map(item => {
                            return {
                                id: item.id,
                                nome: item.nome
                            }
                        });
                        resolve(saida);

                    });
                }
            };
        };
        await app.$set(dataVue, "ProjetoSeletor", ProjetoSeletor());

        //#endregion

        app.$watch("dataVue.TabPFiltro", async (n, o) => {
            await BuscaPropostas();
        }, { immediate: true, deep: true });


        app.$set(dataVue, "PropostasCarregando", true);
        app.$set(dataVue, "Propostas", { listaP: [], listaN: [] });

        await BuscaPropostas();

        app.$set(dataVue, "CancelaProposta", async (idProposta) => {
            await BloquearTela()
            $cancelou = await WMExecutaAjax("PropostaBO", "RECUSARPROPOSTA", { IDPROPOSTA: idProposta });
            await BuscaPropostas();
            MostraMensagem("Proposta cancelada com sucesso.", ToastType.SUCCESS, "Sucesso");
            if (app.dataVue.Propostas.listaP.length == 0 && app.dataVue.Propostas.listaN.length == 0 && app, dataVue.TabPFiltro.Projeto != null)
                app.$refs.SeletorFiltra.$refs.ProjetosSeletor.clearSelection();
            await DesbloquearTela();
        });
        app.$set(dataVue, "AprovaProposta", async (idProposta) => {
            await BloquearTela();
            $AProvou = await WMExecutaAjax("PropostaBO", "APROVARPROPOSTA", { IDPROPOSTA: idProposta });
            await BuscaPropostas();
            MostraMensagem("Proposta aprovada com sucesso.", ToastType.SUCCESS, "Sucesso");
            if (app.dataVue.Propostas.listaP.length == 0 && app.dataVue.Propostas.listaN.length == 0 && app, dataVue.TabPFiltro.Projeto != null)
                app.$refs.SeletorFiltra.$refs.ProjetosSeletor.clearSelection();
            await DesbloquearTela();
        });



    }
    //TODO TRAZER AS PROPOSTAS AI BELE WELL DO FUTURO
    // await WMExecutaAjax("PropostaBO","BuscaPropostasPendentesClientes")
    //{listaP: Array(1), listaN: Array(0)}
    //#endregion

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
