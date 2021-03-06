$("#Titulo").text("Buscar Projetos | Conserta");

let UltimoFiltro = { C: Array(), Q: "", P: 1 };
let PaginaAntesDigitar = 1;
$(document).ready(async () => {
    var usrContexto = await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO);
    BloquearTela();
    await app.$set(dataVue, "FiltroProjeto", { C: Array(), Q: "", P: 1 });
    app.$set(dataVue, "Carregando", true);
    /* ENTIDADE DA PROPOSTA */
    app.$set(dataVue, "Proposta", {
        IdServico: -1,
        IdCliente: -1,
        IdFuncionario: -1,
        Valor: "",
        Descricao: "",
        Upgrades: {
            upgrade1: false,
            upgrade2: false
        },
    });
    let CategoriaParan = GetParam();
    app.$set(dataVue, "Categorias", await GetCategorias());
    app.$set(dataVue, "PropostaController", {
        carregando: false,
        mandou: false
    });
    app.$set(dataVue, "Plano", 0);
    app.$watch("dataVue.FiltroProjeto", async function (a, o) {
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

    if (CategoriaParan.length > 0) {
        if (CategoriaParan[0].C != null) {
            dataVue.Categorias.Click(CategoriaParan[0].C);
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

    var timerChatModal = null;



    app.$set(dataVue, "modalVisivelController", false);
    app.$set(dataVue, "modalVisivelController1", false);
    app.$set(dataVue, "selecionadoController", {});

    /* FUNÇÃO DE SALVAR */
    app.$set(dataVue, "enviaproposta", async () => {

        let valesNaCarteira = parseFloat(await GetSessaoPHP("VALESPATROCINIOS"));
        let valorCarteira = parseFloat(await GetSessaoPHP("VALORCARTEIRA"));

        if (!valesNaCarteira && dataVue.Proposta.Upgrades.upgrade1 && dataVue.Proposta.Upgrades.upgrade2) {
            valorCarteira -= 6;
        } else if (!valesNaCarteira && dataVue.Proposta.Upgrades.upgrade1) {
            valorCarteira -= 5;
        } else if (dataVue.Proposta.Upgrades.upgrade2) {
            valorCarteira -= 1;
        }

        if ((!dataVue.Proposta.Upgrades.upgrade1 && !dataVue.Proposta.Upgrades.upgrade2) || (valesNaCarteira && !dataVue.Proposta.Upgrades.upgrade2) || valorCarteira >= 0) {
            BloquearTelaSemLoader();
            dataVue.PropostaController.carregando = true;

            let result = await WMExecutaAjax("PropostaBO", "SalvarProposta", { proposta: dataVue.Proposta })
            if (result.error != undefined) {
                toastr.error("Algo deu errado, tente novamente", "Ops");
                dataVue.PropostaController.carregando = false;
            } else if (result == true) {
                if (valesNaCarteira && dataVue.Proposta.Upgrades.upgrade1) {
                    toastr.info("Upgrade concedido através de um vale patrocínio.", "Upgrades!");
                }

                if (!valesNaCarteira && dataVue.Proposta.Upgrades.upgrade1 && dataVue.Proposta.Upgrades.upgrade2) {
                    toastr.info("R$ 6,00 foram deduzidos de sua carteira.", "Upgrades!");
                } else if (!valesNaCarteira && dataVue.Proposta.Upgrades.upgrade1) {
                    toastr.info("R$ 5,00 foram deduzidos de sua carteira.", "Upgrades!");
                } else if (dataVue.Proposta.Upgrades.upgrade2) {
                    toastr.info("R$ 1,00 foram deduzidos de sua carteira.", "Upgrades!");
                }
                let usuarioId = await GetSessaoPHP("IDUSUARIOCONTEXTO");

                let resultadoVales = AtualizaUsuarioColuna(usuarioId, "vales_patrocinios", --valesNaCarteira, "VALES_PATROCINIOS", "funcionario");
                let resultadoCarteira = AtualizaUsuarioColuna(usuarioId, "valor_carteira", valorCarteira, "VALOR_CARTEIRA");

                dataVue.Projetos.lista.map(x => {
                    if (x.id == dataVue.selecionadoController.id)
                        x.propostaFuncionario = 1
                    return x;
                });
                toastr.success("Proposta enviada com sucesso!", "Sucesso");

            }

            dataVue.PropostaController.carregando = false;
            dataVue.PropostaController.mandou = true;

            dataVue.selecionadoController.id_chat = JSON.parse(await WMExecutaAjax("ChatBO", "GetChatPorServico", { ID_SERVICO: dataVue.selecionadoController.id }));
            let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", { ID_CHAT: dataVue.selecionadoController.id_chat, ID_USUARIO1: usrContexto, ID_USUARIO2: dataVue.selecionadoController.id_usuario });
            dataVue.selecionadoController.msg = msg.map(x => {
                x.tipo = TipoMensagem.MSG
                return x;
            });

            timerChatModal = setInterval(async () => {
                if (dataVue.modalVisivelController == true) {
                    let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", { ID_CHAT: dataVue.selecionadoController.id_chat, ID_USUARIO1: usrContexto, ID_USUARIO2: dataVue.selecionadoController.id_usuario });
                    if(dataVue.selecionadoController != null) {
                        dataVue.selecionadoController.msg = msg.map(x => {
                            x.tipo = TipoMensagem.MSG
                            return x;
                        });
                    }


                }
                return;
            }, 500);

            /* Desativa os inputs após clicar no botão*/

            //#region Variaveis para porcentagem
            valorMax = dataVue.selecionadoController.valor.split(" - ")[1].replace("R$", "");
            valorMin = dataVue.selecionadoController.valor.split(" - ")[0].replace("R$", "");
            valorCliente = $(".inputProposta")[0].value;

            porcentagemAtual = ((valorCliente - valorMin) * 100) / (valorMax - valorMin);
            //#endregion
            $(".inputProposta").attr("disabled", true);
            $(".upgradeCard").attr("background-color", "#f2f2f2");

            DesbloquearTelaSemLoader();
        } else {
            toastr.error("Não é possível dar upgrade devido a falta de fundos na carteira.", "Falta de fundos!");
        }

    });


    app.$set(dataVue, "igualaTamanhoChat", async () => {
        if($(".bodyDetalhes").height() > 500) {
            let bodyDetalhesHeight = $(".bodyDetalhes").height();
            $(".bodyChat").attr("style", `max-height: ${bodyDetalhesHeight}px !important;`);

            let porcentagem = 78;
            if(bodyDetalhesHeight > 800) {
                porcentagem = 90;
            } else if (bodyDetalhesHeight > 700) {
                porcentagem = 86;
            } else if(bodyDetalhesHeight > 600) {
                porcentagem = 82;
            }

            let tamanhoBodyChatChat = bodyDetalhesHeight / 100 * porcentagem;
            $("#bodyChatChat").attr("style", `height: ${tamanhoBodyChatChat}px !important;`);

        }
    });

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

            propriedades.id_chat = JSON.parse(await WMExecutaAjax("ChatBO", "GetChatPorServico", { ID_SERVICO: propriedades.id }));
            let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", { ID_CHAT: propriedades.id_chat, ID_USUARIO1: usrContexto, ID_USUARIO2: propriedades.id_usuario });
            propriedades.msg = msg.map(x => {
                x.tipo = TipoMensagem.MSG
                return x;
            });

            DesbloquearTela();
            dataVue.modalVisivelController = true;
            dataVue.selecionadoController = propriedades;
            if (dataVue.UsuarioContexto.NIVEL_USUARIO == 1){
                if(dataVue.Projetos.lista.filter(x => {return dataVue.selecionadoController.id == x.id}).length > 0) {
                    dataVue.selecionadoController.propostaFuncionario = dataVue.Projetos.lista.filter(x => {return dataVue.selecionadoController.id == x.id })[0].propostaFuncionario;
                }
            }

            dataVue.Proposta.IdServico = dataVue.selecionadoController.id;
            dataVue.Proposta.IdFuncionario = dataVue.UsuarioContexto.id_funcionario;
            dataVue.Proposta.IdCliente = dataVue.selecionadoController.id_usuario;

            let valesNaCarteira = parseFloat(await GetSessaoPHP("VALESPATROCINIOS"));

            /* Algumas atualizacoes do modal*/

            timerChatModal = setInterval(async () => {
                if (dataVue.modalVisivelController == true) {
                    let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", { ID_CHAT: dataVue.selecionadoController.id_chat, ID_USUARIO1: usrContexto, ID_USUARIO2: dataVue.selecionadoController.id_usuario });
                    if(dataVue.selecionadoController != null) {
                        dataVue.selecionadoController.msg = msg.map(x => {
                            x.tipo = TipoMensagem.MSG
                            return x;
                        });
                    }


                }
                return;
            }, 500);

            setTimeout(() => {
                /*JS DO SLIDER*/
                var i = 1;
                porcentagemAtual = 50;


                /* PUXA O VALOR DINAMICO */
                if (dataVue.selecionadoController.propostaFuncionario == 0) {
                    $('#precoMin')[0].innerText = dataVue.selecionadoController.valor.split(" - ")[0];
                    $('#precoMax')[0].innerText = dataVue.selecionadoController.valor.split(" - ")[1];
                }


                $('#rangeSlider').wrap("<div class='range'></div>");


                $('.range').each(function () {
                    valorCliente = (dataVue.selecionadoController.valor.split(" - ")[0].replace("R$", "") * 1 + dataVue.selecionadoController.valor.split(" - ")[1].replace("R$", "") * 1) / 2;
                    taxaPorcentagem = 15;


                    /* Seta a taxa (o retornaPlano seta também daquele cardzinho verde)*/
                    dataVue.retornaPlano().then(p => {
                        taxaPorcentagem = p;
                        switch (taxaPorcentagem) {
                            case 0:
                                taxaPorcentagem = 20;
                                break;
                            case 1:
                                taxaPorcentagem = 15;
                                break;
                            case 2:
                                taxaPorcentagem = 12;
                                break;
                            case 3:
                                taxaPorcentagem = 8;
                                break;
                            default:
                                break;
                        }

                        taxaValor = (valorCliente / 100) * taxaPorcentagem;
                        valorFuncionario = valorCliente - taxaValor;

                      
                        /* Atualiza o popover dentro da bolinha de interrogação*/
                        $("#linkPopover").attr("data-content", (
                            "Você receberá: R$ " +
                            (Math.round(valorCliente * 100) / 100).toFixed(2) +
                            " - R$ " +
                            (Math.round(taxaValor * 100) / 100).toFixed(2) +
                            " = R$ " +
                            ((Math.round(valorFuncionario) * 100) / 100).toFixed(2) +
                            ""
                        ).split(".").join(",")

                        );

                    });


                    taxaValor = (valorCliente / 100) * taxaPorcentagem;
                    valorFuncionario = valorCliente - taxaValor;

                    var n = this.getElementsByTagName('input')[0].value / 1;
                    this.id = 'range' + i;
                    if (this.getElementsByTagName('input')[0].value == 0) {
                        this.className = "range"
                    } else {
                        this.className = "range rangeM"

                    }
                    this.innerHTML = "<input type='range' id='rangeSlider' class='inputProposta' min='" + dataVue.selecionadoController.valor.split(" - ")[0].replace("R$", "") + "' max='" + dataVue.selecionadoController.valor.split(" - ")[1].replace("R$", "") + "'><style>#" + this.id + " #rangeSlider::-webkit-slider-runnable-track {background:linear-gradient(to right, #62de57 0%, #059c06 " + n / 2 + "%, #62de57 " + n + "%, #515151 " + n + "%);}</style>";
                    i++

                    $("#valorAtualSlider")[0].innerHTML = "R$ " + valorCliente + ",00&nbsp;";

                    /* Atualiza o popover dentro da bolinha de interrogação*/
                    $("#linkPopover").attr("data-content", (
                        "Você receberá: R$ " +
                        (Math.round(valorCliente * 100) / 100).toFixed(2) +
                        " - R$ " +
                        (Math.round(taxaValor * 100) / 100).toFixed(2) +
                        " = R$ " +
                        ((Math.round(valorFuncionario) * 100) / 100).toFixed(2) +
                        ""
                    ).split(".").join(",")

                    );

                    dataVue.Proposta.Valor = valorCliente;


                });

                $('#rangeSlider').on("input", function () {
                    valorMax = dataVue.selecionadoController.valor.split(" - ")[1].replace("R$", "");
                    valorMin = dataVue.selecionadoController.valor.split(" - ")[0].replace("R$", "");
                    valorTotal = valorMax - valorMin;

                    valorCliente = event.target.value;
                    taxaValor = (valorCliente / 100) * taxaPorcentagem;
                    valorFuncionario = valorCliente - taxaValor;


                    porcentagemAtual = ((valorCliente - valorMin) * 100) / (valorMax - valorMin);
                    var a = this.value / 1;
                    if (a == 0) {
                        this.parentNode.className = "range"
                    } else {
                        this.parentNode.className = "range rangeM"
                    }
                    this.parentNode.getElementsByTagName('style')[0].innerHTML = "#" + this.parentNode.id + " #rangeSlider::-webkit-slider-runnable-track {background:linear-gradient(to right, #62de57 0%, #059c06 " + porcentagemAtual / 2 + "%, #62de57 " + porcentagemAtual + "%, #515151 " + porcentagemAtual + "%);}";


                    event.target.style.setProperty('--r', valorCliente + 'deg');
                    if (porcentagemAtual > 66.6) {
                        event.target.style.setProperty('--moeda', "url('../../src/img/icons/propostas/moeda-ouro.svg')")
                    } else if (porcentagemAtual > 33.3) {
                        event.target.style.setProperty('--moeda', "url('../../src/img/icons/propostas/moeda-prata.svg')")
                    } else {
                        event.target.style.setProperty('--moeda', "url('../../src/img/icons/propostas/moeda-bronze.svg')")
                    }





                    /* Valores das cores de inicio e final: rgb(3, 7, 30) e  rgb(0, 128, 0)*/
                    let corASerModificada = "rgb(" + ((porcentagemAtual * -0.03) + 3) + ", " + ((porcentagemAtual * 1.21) + 7) + ", " + ((porcentagemAtual * -0.30) + 30) + ")";
                    $(".valorDoSlider")[0].style.setProperty('--corASerModificada', corASerModificada);

                    $("#valorAtualSlider")[0].innerHTML = "R$ " + valorCliente + ",00&nbsp;";


                    /* Atualiza a bolinha */
                    $("#valorDetalheInterrogacao").addClass("animacaoInterrogacao");



                    $("#linkPopover").attr("data-content", (
                        "Você receberá: R$ " +
                        (Math.round(valorCliente * 100) / 100).toFixed(2) +
                        " - R$ " +
                        (Math.round(taxaValor * 100) / 100).toFixed(2) +
                        " = R$ " +
                        ((Math.round(valorFuncionario) * 100) / 100).toFixed(2) +
                        ""
                    ).split(".").join(","));

                    /* ATUALIZA O DATAVUE COM O VALOR DO CLIENTE */
                    dataVue.Proposta.Valor = valorCliente;

                })

                $("#descricaoDaPropostaInput").on("input", () => {
                    dataVue.Proposta.Descricao = $("#descricaoDaPropostaInput")[0].value;
                });

                $("#rangeSlider").on("mouseover", function () {
                    $('#rangeSlider').addClass("hovered");
                });

                if (dataVue.selecionadoController.propostaFuncionario == 0) {
                    $(".valorDoSlider")[0].innerHTML += "<style></style>";
                }
                $("#rangeSlider").on({
                    mouseenter: () => {
                        $('#rangeSlider').addClass("hovered");
                    },
                    mouseleave: () => {
                        $('#rangeSlider').removeClass("hovered");
                    },
                    mousedown: () => {
                        $('#rangeSlider').addClass("clicked");
                        $(".clicked").on("input", () => {
                            event.target.style.setProperty('--r', porcentagemAtual * 18 + 'deg')
                        });
                    },
                    mouseup: () => {
                        $('#rangeSlider').removeClass("clicked");
                    }
                });

                /* FIM JS DO SLIDER*/

                /* ATIVADOR DO POPOVER */
                $(function () {
                    $('[data-toggle="popover"]').popover()
                })

                $("#linkPopover").on("click", () => {
                    $("#valorDetalheInterrogacao").removeClass("animacaoInterrogacao");
                });
                /* -------------------*/


                setTimeout(async () => {
                    if (dataVue.modalVisivelController == true) {
                        let resultadoProposta = await WMExecutaAjax("PropostaBO", "RetornaValorPropostaMedia", { ID_SERVICO: dataVue.Proposta.IdServico });
                        if (resultadoProposta[0].soma) {
                            resultadoProposta = String(Number.parseFloat(resultadoProposta[0].soma).toFixed(2)).replace(".", ",");
                        } else {
                            resultadoProposta = "0,00"
                        }
                        if (dataVue.selecionadoController.propostaFuncionario == 0) {
                            document.getElementById("propostaMedia").innerText = `R$ ${resultadoProposta}`;
                        }

                        if (valesNaCarteira > 0) {
                            $("#patrocinadoValor").text("Gratuito");
                            $("#patrocinadoValor").addClass("text-success");
                        }

                    }
                }, 100);
            }, 1);
            setTimeout(() => {
                $("#rangeSlider").attr("min", dataVue.selecionadoController.valor.split(" - ")[0].replace("R$", ""));
                $("#rangeSlider").attr("max", dataVue.selecionadoController.valor.split(" - ")[1].replace("R$", ""));
            }, 10);



            

        } catch (error) {
            console.warn("ERROR++++++=====+++++" + error.message);
            toastr.error("Algo Deu Errado!<br>tente novamente mais tarde.", "Ops");
        } finally {
            DesbloquearTela();
        }

    });
    app.$set(dataVue, "callback", () => {
        clearInterval(timerChatModal);
        dataVue.modalVisivelController = false;
        dataVue.selecionadoController = null;
        dataVue.PropostaController.mandou = false;
    });

    //#region CHAT
    app.$set(dataVue, "NovaMensagem", async (mensagem = MensagemEntidade()) => {
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
    //         MensagemEntidade(6, -1, 'Não existe mais a necessidade deste projeto', 1, 2, '2020-06-12', '12:00:04')
    //     ];
    //     return msg;
    // }
    // app.$set(dataVue, "msg", GetMensagens());
    //#endregion


    //#region Porcentagem do plano
    app.$set(dataVue, "retornaPlano", () => {
        return new Promise((result) => {
            GetSessaoPHP("PLANO").then(planoN => {
                planoN = Number.parseInt(planoN);
                let membro = "Padrão";
                switch (planoN) {
                    case 0:
                        membro = "Padrão";
                        document.getElementById("taxaModal").innerText = `Plano ${membro}: 20%`;
                        break;
                    case 1:
                        membro = "Plus";
                        document.getElementById("taxaModal").innerText = `Plano ${membro}: 15%`;
                        break;
                    case 2:
                        membro = "Prime";
                        document.getElementById("taxaModal").innerText = `Plano ${membro}: 12%`;
                        break;
                    case 3:
                        membro = "Master";
                        document.getElementById("taxaModal").innerText = `Plano ${membro}: 8%`;
                        break;
                    default:
                        break;
                }
                result(planoN);
            });
        });


    });


    let idProjetoGet = "";
    idProjetoGet = getURLParameter("id_projeto");
    if(idProjetoGet != "null" && idProjetoGet) {
        let result = await WMExecutaAjax("ProjetoBO", "BuscaProjetoPorIdBuscaServico", {ID: idProjetoGet});

        //Renomear uns parametros e tals;
        result.titulo = result.nome;
        result.nome = result.nome_usuario
        result.valor = Valores[result.valor];
        result.profissional = NivelFuncionario[result.nivel_profissional];
        result.tamanho = NivelProjeto[result.nivel_projeto];
        result.imagem = result.imagem_usuario;
        result.publicado = result.postado;
        result.proposta = result.propostas;

        //Limpar para o objeto ficar igual ao do click do botão
        
        delete result.nome_usuario;
        delete result.nivel_profissional;
        delete result.nivel_projeto;
        delete result.imagem_usuario;
        delete result.postado;
        delete result.propostas;

        dataVue.abremodal(result);  

    }
    //#endregion

});