$(document).ready(async()=>{


    if (dataVue.UsuarioContexto.NIVEL_USUARIO == 0) {
   //#region TAB PROPOSTA Cliente

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
   //#region VueData
    app.$set(dataVue, "TabPFiltro", { Projeto: null });
    app.$set(dataVue, "PropostasCarregando", true);
    app.$set(dataVue, "Propostas", { listaP: [], listaN: [] });
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
    //#endregion
    //#region Watcher
    app.$watch("dataVue.TabPFiltro", async (n, o) => {
        await BuscaPropostas();
    }, { immediate: true, deep: true });
    //#endregion
    await BuscaPropostas();
 //#endregion
    }
    else if(dataVue.UsuarioContexto.NIVEL_USUARIO == 1){
        //#region  TAB PROPOSTA FUNCIONARIO
        
        /* TODO PARA CASE PROPOSTAS Para CLIENTES A SER IMPLEMENTADA NA TAREFA #6 E #7*/
        
        //#endregion
    }


});