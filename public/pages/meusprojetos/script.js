$(document).ready(async () => {
    app.$set(dataVue, "carregando", false);
    app.$set(dataVue, "ListaCarregando", true);
    app.$set(dataVue, "Lista", []);
    app.$set(dataVue, "PageController", { paginas: 1, pagina_Atual: 1 });
    app.$set(dataVue, "meusprojetos", { Q: null, categoria: null, situacao: null });
    app.$set(dataVue, "modalVisivelController", false);
    app.$set(dataVue, "BTClick", BTClick);
    app.$set(dataVue, "callback", () => {
        dataVue.modalVisivelController = false;
    });
    app.$set(dataVue, 'selecionadoController', null);



    //#region Seletores

    var CategoriaSeletor = () => {
        return {
            id: 'Cachumba',
            visivel: () => { return true },
            titulo: 'Categoria',
            disabled: () => { return false },
            entidade: "meusprojetos",
            campo: "categoria",
            limpavel: true,
            icone: false,
            placeholder: "Selecione uma Categoria",
            obrigatorio: false,
            ajax: async (ss) => {
                return await new Promise(async resolve => {

                    var saida = await WMExecutaAjax("TipoServicoBO", "GetTipoServicoCategoria");
                    saida.map(item => {
                        return {
                            id: item.id,
                            nome: item.nome
                        }
                    });
                    resolve(saida)

                });
            }
        };
    };

    var SituacaoSeletor = () => {
        return {
            id: 'SituacaoSeletor',
            visivel: () => { return true },
            titulo: 'Situação',
            disabled: () => { return false },
            entidade: "meusprojetos",
            campo: "situacao",
            limpavel: true,
            icone: true,
            placeholder: "Selecione uma Situação",
            obrigatorio: false,
            ajax: async (ss) => {
                return await new Promise(async resolve => {

                    var saida = await WMExecutaAjax("Seletores", "GetSituacaoSeletor");
                    resolve(saida)

                });
            }
        };
    };
    app.$set(dataVue, "seletorcategoria", CategoriaSeletor());
    app.$set(dataVue, "seletorsituacao", SituacaoSeletor());
    //#endregion 
    //#region  Whatchers
    app.$watch("dataVue.meusprojetos", async function (a, o) {
        await BuscaMeusProjetos();
    }, { deep: true });
    app.$watch("dataVue.PageController.pagina_Atual", async function (a, o) {
        if (a != o)
            await BuscaMeusProjetos();
    }, {});
    //#endregion
    //#region AbreModal
    app.$set(dataVue, "abremodal", async (propriedades) => {
        try {

            BloquearTela();
            let Dependencias = await WMExecutaAjax("ProjetoBO", "BuscaDependeciasModal", { id: propriedades.id });
            if (Dependencias.length > 0) {
                propriedades.FotoPrincipal = Dependencias.filter(item => item.principal == 1);
                propriedades.FotoPrincipal = propriedades.FotoPrincipal.length > 0 ? propriedades.FotoPrincipal[0].imagem : null;
                let lista = Dependencias.filter(item => item.principal != 1);
                propriedades.Fotos = lista.map(x => { return x.imagem });
                propriedades.Fotos = [propriedades.FotoPrincipal, ...propriedades.Fotos];
            }
            dataVue.modalVisivelController = true;
            dataVue.selecionadoController = propriedades;
            await setTimeout(() => { $('[data-toggle="tooltip"]').tooltip(); });

        }
        catch (e) {

        }
        finally {
            DesbloquearTela();
        }
    });

    //#endregion
    $('[data-toggle="tooltip"]').tooltip();

})
async function BuscaMeusProjetos() {
    app.dataVue.ListaCarregando = true;
    var consultaObj = {};
    if (app.dataVue.meusprojetos.Q != null)
        consultaObj.Q = app.dataVue.meusprojetos.Q;
    if (app.dataVue.meusprojetos.categoria != null)
        consultaObj.C = app.dataVue.meusprojetos.categoria;
    if (app.dataVue.meusprojetos.situacao != null)
        consultaObj.S = app.dataVue.meusprojetos.situacao;

    consultaObj.P = app.dataVue.PageController.pagina_Atual;

    var resultado = await WMExecutaAjax("ProjetoBO", "BuscarMeusProjetos", consultaObj);
    if (resultado.error === undefined) {
        app.dataVue.Lista = resultado.lista;
        app.dataVue.PageController.paginas = parseInt(resultado.pagina);
    }
    app.dataVue.ListaCarregando = false;
};
function BTClick(evento, Botao) {
    debugger
    if (Botao == "CHAT") {
        if (app.dataVue.selecionadoController.situacao == 0)
            evento.view.RedirecionarComParametros('chat', [{ chave: 'P', valor: app.dataVue.selecionadoController.id }]);
    }
    if (Botao == "CANCELA") {
        BloquearTela();
        WMExecutaAjax("ProjetoBO", "CANCELA", { ID: app.dataVue.selecionadoController.id }).then(resultado => {
            if (resultado.error !== undefined)
                throw resultado.error;
            if (resultado == true) {
                app.dataVue.selecionadoController.situacao = 3;
                app.dataVue.Lista.filter(p => p.id == app.dataVue.selecionadoController.id)[0].situacao = 3;
            }

        }).catch(Err => {
            console.warn("Erro ao Cancelar Projeto: \n" + Err);
            MostraMensagem("Algo deu errado, tente novamente mais tarde.", ToastType.ERROR, "Cancelar Projeto");
        }).then((saida) => {
            DesbloquearTela();
        });
    }
}