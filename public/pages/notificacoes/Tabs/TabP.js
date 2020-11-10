$(document).ready(async () => {

    dataVue.UsuarioContexto.NIVEL_USUARIO = await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO);

    if (dataVue.UsuarioContexto.NIVEL_USUARIO == '0') {
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
        app.$set(dataVue, "modalVisivelControllerConfirmacao", false);
        app.$set(dataVue, "idPropostaSelecionada", null);
        app.$set(dataVue, "fechaModalConfirmacao", async (e) => {

            if (e) {
                await BloquearTela();

                $AProvou = await WMExecutaAjax("PropostaBO", "APROVARPROPOSTA", { IDPROPOSTA: dataVue.idPropostaSelecionada });

                if ($AProvou != true && $AProvou.split('|').length > 1) {
                    MostraMensagem($AProvou.split('|')[1], ToastType.INFO, "Saldo Insuficiente");
                }
                else {
                    await BuscaPropostas();
                    MostraMensagem("Proposta aprovada com sucesso.", ToastType.SUCCESS, "Sucesso");
                    if (app.dataVue.Propostas.listaP.length == 0 && app.dataVue.Propostas.listaN.length == 0 && app.dataVue.TabPFiltro.Projeto != null)
                        app.$refs.SeletorFiltra.$refs.ProjetosSeletor.clearSelection();
                }
                await DesbloquearTela();
            }
            dataVue.idPropostaSelecionada = null;
            dataVue.modalVisivelControllerConfirmacao = false;
        })
        app.$set(dataVue, "CancelaProposta", async (idProposta) => {
            await BloquearTela()
            $cancelou = await WMExecutaAjax("PropostaBO", "RECUSARPROPOSTA", { IDPROPOSTA: idProposta });
            await BuscaPropostas();
            MostraMensagem("Proposta cancelada com sucesso.", ToastType.SUCCESS, "Sucesso");
            if (app.dataVue.Propostas.listaP.length == 0 && app.dataVue.Propostas.listaN.length == 0 && app.dataVue.TabPFiltro.Projeto != null)
                app.$refs.SeletorFiltra.$refs.ProjetosSeletor.clearSelection();
            await DesbloquearTela();
        });
        app.$set(dataVue, "AprovaProposta", async (idProposta) => {
            dataVue.idPropostaSelecionada = idProposta;
            dataVue.modalVisivelControllerConfirmacao = true;
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
    else if (dataVue.UsuarioContexto.NIVEL_USUARIO == '1') {
        //#region  TAB PROPOSTA FUNCIONARIO
        //#region DataVue
        app.$set(dataVue, 'TabPFuncionarioCarregando', true);
        app.$set(dataVue, 'TabPFuncionarioPossuiAprovada', false);
        app.$set(dataVue, 'TabPSituacaoProposta', GetSituacaoProposta());
        app.$set(dataVue, 'TabPropostaFuncinarioTab', { paginas: 1, pagina_Atual: 1 });
        app.$set(dataVue, "PropostaFuncionario", []);
        app.$set(dataVue, "PropostaFuncionarioCarregando", true);

        //#endregion
        //#region Watchers
        await app.$watch("dataVue.TabPropostaFuncinarioTab", async function (a, o) {
            dataVue.PropostaFuncionarioCarregando = true;
            await BuscaPropostas();
        }, { deep: true });
        await app.$watch("dataVue.TabPSituacaoProposta", async function (a, o) {
            dataVue.PropostaFuncionarioCarregando = true;
            await BuscaPropostas();
        }, { immediate: true, deep: true });
        //#endregion


        //  await WMExecutaAjax("PropostaBO","BUSCAPROPOSTASFUNCIONARIOTAB",{FILTROS:[],PAGINA:1})


        app.dataVue.TabPFuncionarioCarregando = false;
        //#endregion
    }
})


