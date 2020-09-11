$(document).ready(async () => {
    app.$set(dataVue, "carregando", false);
    app.$set(dataVue, "ListaCarregando", true);
    app.$set(dataVue, "Lista", []);
    app.$set(dataVue, "PageController", { paginas: 1, pagina_Atual: 1 });
    app.$set(dataVue, "meusprojetos", { Q: null, categoria: null, situacao: null });
    app.$set(dataVue, "modalVisivelController", false);
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
    app.$watch("dataVue.meusprojetos", async function (a, o) {
        await BuscaMeusProjetos();
    }, { deep: true });
    app.$watch("dataVue.PageController.pagina_Atual", async function (a, o) {
        if (a != o)
            await BuscaMeusProjetos();
    }, {});
});
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