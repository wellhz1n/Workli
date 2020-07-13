$(document).ready(async() => {
    app.$set(dataVue, "carregando", false);

    app.$set(dataVue, "meusprojetos", { categoria: "", situacao: "" });


    // setTimeout(() => {

    //     dataVue.carregando = false;
    // }, 1000)
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
            obrigatorio: false,
            ajax: async(ss) => {
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
            obrigatorio: false,
            ajax: async(ss) => {
                return await new Promise(async resolve => {

                    var saida = await WMExecutaAjax("Seletores", "GetSituacaoSeletor");
                    resolve(saida)

                });
            }
        };
    };
    app.$set(dataVue, "seletorcategoria", CategoriaSeletor());
    app.$set(dataVue, "seletorsituacao", SituacaoSeletor());


});