let UltimoFiltro = { C: Array(), Q: "", P: 1 };
let PaginaAntesDigitar = 1;
$(document).ready(async() => {
    BloquearTela();
    await app.$set(dataVue, "FiltroProjeto", { C: Array(), Q: "", P: 1 });
    app.$set(dataVue, "Carregando", true);

    let CategoriaParan = GetParam();

    app.$set(dataVue, "Categorias", await GetCategorias());
    app.$watch("dataVue.FiltroProjeto", async function(a, o) {
        //Guambiarra que da Orgulho pro pai
        var aObj = { C, P, Q } = JSON.parse(JSON.stringify(a));
        if (o != undefined && (aObj.C.join() != UltimoFiltro.C.join() || aObj.P != UltimoFiltro.P || aObj.Q != UltimoFiltro.Q)) {
            if (UltimoFiltro.Q == "" && aObj.Q.length >= 1) {
                dataVue.FiltroProjeto.P = 1;
                aObj.P = 1;
            } else if (UltimoFiltro.Q.length >= 1 && aObj.Q.length == "") {
                dataVue.FiltroProjeto.P = 1;
                aObj.P = 1;
            }
            UltimoFiltro = aObj;
            dataVue.Projetos = await getProjetos(dataVue.FiltroProjeto.C,
                dataVue.FiltroProjeto.Q,
                dataVue.FiltroProjeto.P);
        }
    }, { deep: true });

    if (CategoriaParan.length > 1) {
        if (CategoriaParan[1].C != null) {
            dataVue.Categorias.Click(CategoriaParan[1].C);
        }
    }
    app.$set(dataVue, "Projetos", await getProjetos(dataVue.FiltroProjeto.C,
        dataVue.FiltroProjeto.Q,
        dataVue.FiltroProjeto.P));

    DesbloquearTela();


    async function GetCategorias() {
        var Resultado = await WMExecutaAjax("TipoServicoBO", "GetTipoServicoCategoria");
        Resultado = Resultado.map(x => {
            return {
                id: x.id,
                nome: x.nome,
                checado: false,
            }
        });
        return {
            categoria: Resultado,
            Click: (id) => {
                let cat = Resultado.filter(x => x.id == id)[0];
                cat.checado = !cat.checado;
                if (cat.checado)
                    dataVue.FiltroProjeto.C.push(id);
                else {
                    let indice = dataVue.FiltroProjeto.C.indexOf(id);
                    dataVue.FiltroProjeto.C.splice(indice, 1);
                }
            }
        }
    }

    async function getProjetos(C = Array(), Q = "", P = 1) {
        dataVue.Carregando = true;
        let result = await WMExecutaAjax("ProjetoBO", "BuscarProjetos", { C, Q, P });
        dataVue.Carregando = false;

        return {
            lista: result.lista,
            pagina: result.pagina == 0 ? 1 : result.pagina,
            paginaAtual: result.paginaAtual > result.pagina ? result.paginaAtual - result.pagina : result.paginaAtual
        }

    };
    //Guambiarra para simular um realtime
    // setInterval(async() => {
    //     console.log("ATUALIZANDO");
    //     dataVue.Projetos = await await getProjetos(dataVue.FiltroProjeto.C,
    //         dataVue.FiltroProjeto.Q,
    //         dataVue.FiltroProjeto.P, false);
    // }, 10000);
    // Fim da guambiarra
    app.$set(dataVue, "modalVisivelController", false);
    app.$set(dataVue, "selecionadoController", {});
    app.$set(dataVue, "abremodal", async(propriedades) => {
        BloquearTela();
        let Dependencias = await WMExecutaAjax("ProjetoBO", "BuscaDependeciasModal", { id: propriedades.id });
        if (Dependencias.length > 0) {
            propriedades.FotoPrincipal = Dependencias.filter(item => item.principal = 1);
            propriedades.FotoPrincipal = propriedades.FotoPrincipal.length > 0 ? propriedades.FotoPrincipal[0].imagem : null;
            propriedades.Fotos = Dependencias.filter(item => item.principal != 1);
        }
        DesbloquearTela();
        dataVue.modalVisivelController = true;
        dataVue.selecionadoController = propriedades;


    });
    app.$set(dataVue, "callback", () => {
        dataVue.modalVisivelController = false;
    });



});