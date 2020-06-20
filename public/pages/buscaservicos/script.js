let UltimoFiltro = { C: Array(), Q: "", P: 1 };
let PaginaAntesDigitar = 1;
$(document).ready(async() => {
    var usrContexto = await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO);
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
                dataVue.FiltroProjeto.P = 1;
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
    app.$set(dataVue, "modalVisivelController1", false);
    app.$set(dataVue, "selecionadoController", {});
    app.$set(dataVue, "abremodal", async(propriedades) => {
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
            propriedades.id_chat = JSON.parse(await WMExecutaAjax("ChatBO", "GetChatPorServico", { ID_SERVICO: propriedades.id }));
            let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", { ID_CHAT: propriedades.id_chat, ID_USUARIO1: usrContexto, ID_USUARIO2: propriedades.id_usuario });
            propriedades.msg = msg.map(x => {
                x.tipo = TipoMensagem.MSG
                return x;
            });
            DesbloquearTela();
            dataVue.modalVisivelController = true;
            dataVue.selecionadoController = propriedades;

            /* Algumas atualizacoes do modal*/
            setTimeout(() => {
                if (($(".bodyDetalhes").first().height()) > 500) {
                    $(".bodyChat").css("max-height", $(".bodyDetalhes").first().height());
                }
                var bodyChatScroll = document.getElementById("bodyChatChat");
                bodyChatScroll.scrollTop = bodyChatScroll.scrollHeight;
            }, 1);
            setInterval(async() => {
                if (dataVue.modalVisivelController == true) {
                    let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", { ID_CHAT: dataVue.selecionadoController.id_chat, ID_USUARIO1: usrContexto, ID_USUARIO2: dataVue.selecionadoController.id_usuario });
                    dataVue.selecionadoController.msg = msg.map(x => {
                        x.tipo = TipoMensagem.MSG
                        return x;
                    });
                    console.log("Foi");
                }
                return;
            }, 1000);
        } catch (error) {
            console.warn("ERROR++++++=====+++++ " + error.message);
            toastr.error("Algo Deu Errado!<br>tente novamente mais tarde.", "Ops");
        } finally {
            DesbloquearTela();
        }

    });
    app.$set(dataVue, "callback", () => {
        dataVue.modalVisivelController = false;
        dataVue.selecionadoController = null;
    });

    //#region CHAT
    app.$set(dataVue, "NovaMensagem", async(mensagem = MensagemEntidade()) => {
        try {

            mensagem.id_chat = dataVue.selecionadoController.id_chat;
            let saida = await WMExecutaAjax("ChatBO", "NovaMensagem", { MENSAGEM: mensagem, ID_SERVICO: dataVue.selecionadoController.id }, false);

            if (saida.error == undefined) {
                if (saida.split('|')[0] == "OK")
                    dataVue.selecionadoController.id_chat = JSON.parse(saida.split('|')[1]);
                else if (saida == false)
                    throw Error("Mensagem não enviada");
            } else
                throw Error("Mensagem não enviada");
        } catch (err) {
            console.warn("Error+++++= " + err.message);
            toastr.error("Mensagem não enviada", "Chat");
        }
    });

    // function GetMensagens() {

    //     let msg = [MensagemEntidade(1, -1, 'oi', 1, 2, '2020-06-10', '12:00:04'),
    //         MensagemEntidade(2, -1, 'oi tudo bem?', 2, usrContexto, '2020-06-10', '12:05:09'),
    //         MensagemEntidade(3, -1, 'Vou bem e você?', 1, 2, '2020-06-10', '12:15:04'),
    //         MensagemEntidade(4, -1, 'Desculpe a demora para responder, estou bem também', 2, usrContexto, '2020-06-11', '07:00:04'),
    //         MensagemEntidade(5, -1, 'Mas sobre o Projeto?', 2, usrContexto, '2020-06-11', '07:01:00'),
    //         MensagemEntidade(6, -1, 'Não existe mais a nescessidade deste projeto', 1, 2, '2020-06-12', '12:00:04')
    //     ];
    //     return msg;
    // }
    // app.$set(dataVue, "msg", GetMensagens());
    //#endregion

});