$(document).ready(async () => {



    //#region DATAVUE
    app.$set(dataVue, "carregando", false);
    app.$set(dataVue, "AvaliacaoModalController", false);
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

    var Paramns = GetParam();
    if (Paramns.length > 0) {
        if (Paramns.filter(x => { return Object.entries(x)[0][0] == 'P' }).length > 0) {
            try {

                BloquearTela();
                let idProjeto = Paramns.filter(x => { return Object.entries(x)[0][0] == 'P' })[0].P;
                var buscaProjeto = await WMExecutaAjax("ProjetoBO", "BuscaProjetoPorIdModal", { ID: idProjeto });
                if (buscaProjeto.error == undefined) {

                    let Dependencias = await WMExecutaAjax("ProjetoBO", "BuscaDependeciasModal", { id: buscaProjeto.id });
                    if (Dependencias.length > 0) {
                        buscaProjeto.FotoPrincipal = Dependencias.filter(item => item.principal == 1);
                        buscaProjeto.FotoPrincipal = buscaProjeto.FotoPrincipal.length > 0 ? buscaProjeto.FotoPrincipal[0].imagem : null;
                        let lista = Dependencias.filter(item => item.principal != 1);
                        buscaProjeto.Fotos = lista.map(x => { return x.imagem });
                        buscaProjeto.Fotos = [buscaProjeto.FotoPrincipal, ...buscaProjeto.Fotos];
                    }
                    //Renomear Uns parametros e tals;
                    buscaProjeto.titulo = buscaProjeto.nome;
                    buscaProjeto.nome = buscaProjeto.nome_usuario
                    buscaProjeto.valor = Valores[buscaProjeto.valor];
                    buscaProjeto.profissional = NivelFuncionario[buscaProjeto.nivel_profissional];
                    buscaProjeto.tamanho = NivelProjeto[buscaProjeto.nivel_projeto];
                    buscaProjeto.imagem = buscaProjeto.imagem_usuario;
                    buscaProjeto.publicado = buscaProjeto.postado;
                    buscaProjeto.proposta = buscaProjeto.propostas;

                    //Limpar para o Objeto ficar igual ao do click do botão
                    buscaProjeto.nivel_profissional = undefined;
                    buscaProjeto.nivel_projeto = undefined;
                    buscaProjeto.imagem_usuario = undefined;
                    buscaProjeto.postado = undefined;
                    buscaProjeto.propostas = undefined;
                    //Abre o Modal
                    app.dataVue.selecionadoController = buscaProjeto;
                    app.dataVue.modalVisivelController = true;
                    await setTimeout(() => { $('[data-toggle="tooltip"]').tooltip(); });
                }
                else
                    MostraMensagem(buscaProjeto.error, ToastType.ERROR, "Erro ao Abrir Projeto");


                if (Paramns.filter(x => { return Object.entries(x)[0][0] == 'A' }).length > 0) {
                    dataVue.AvaliacaoModalController = true;
                }
            }
            finally {

                DesbloquearTela();
            }
        }
    }

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