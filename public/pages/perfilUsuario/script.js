

$(document).ready(async () => {
    

    await BloquearTela();
    
    

    /*Id do usuário atual*/
    let usuarioId = await GetSessaoPHP("IDUSUARIOCONTEXTO");
    let nivelUsuarioAtual = await GetSessaoPHP("NIVELUSUARIO");

    /*Pega o Id do topo da tela*/
    let idGet = "";
    app.$set(dataVue, "editavel", true);
    app.$set(dataVue, "nivelUsuario", 0);

    app.$set(dataVue, "idGeral", "usuarioId")
    
    let img = "";
    
    let planoN = "";
    
    await app.$set(dataVue, "Usuario", { imagem: img == "" ? null : img, imgTemp: null });
    
    setTimeout(async () => {
        idGet = getURLParameter("id");
        if(idGet == "null") {
            window.location.href = `?page=perfilUsuario&id=${usuarioId}`;
        } else if(idGet != usuarioId && idGet != "null" && !isNaN(parseInt(idGet))) {
            dataVue.editavel = false
            dataVue.idGeral = idGet;
            dataVue.nivelUsuario = await WMExecutaAjax("UsuarioBO", "GetNivelUsuarioById", {"idUsuario" : dataVue.idGeral});;
            img = await WMExecutaAjax("UsuarioBO", "GetImagemUserById", {"idUsuario" : dataVue.idGeral});
            planoN = parseInt(await WMExecutaAjax("UsuarioBO", "GetPlanoById", {"idUsuario" : dataVue.idGeral}));
            
        } 
        else if (isNaN(parseInt(idGet)) && idGet != "null") {
            window.location.href = "?page=404Perfil";
        } else {
            
            dataVue.idGeral = usuarioId;
            dataVue.nivelUsuario = nivelUsuarioAtual;

            img = await GetSessaoPHP("FOTOUSUARIO");
            planoN = parseInt(await GetSessaoPHP("PLANO"));
            
        }
        await app.$set(dataVue, "Usuario", { imagem: img == "" ? null : img, imgTemp: null });
    }, 1);
    
    
    

    
    
    $("#Titulo").text("Editar Usuário");


    
    /*Modal de Contratar */
    app.$set(dataVue, "modalVisivelContratar", false);

    app.$set(dataVue, "abremodalContratar", async () => {
        dataVue.modalVisivelContratar = true;
    });

    app.$set(dataVue, "callbackContratar", () => {
        dataVue.modalVisivelContratar = false;
    });



    if(document.getElementById("tagsCPWrapper")) {
        function scrollHorizontally(e) {
            e = window.event || e;
            var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
            document.getElementById('tagsCPWrapper').scrollLeft -= (delta*20); // Multiplied by 40
            e.preventDefault();
        }
        if (document.getElementById('tagsCPWrapper').addEventListener) {
            // IE9, Chrome, Safari, Opera
            document.getElementById('tagsCPWrapper').addEventListener("mousewheel", scrollHorizontally, false);
            // Firefox
            document.getElementById('tagsCPWrapper').addEventListener("DOMMouseScroll", scrollHorizontally, false);
        } else {
            // IE 6/7/8
            document.getElementById('tagsCPWrapper').attachEvent("onmousewheel", scrollHorizontally);
        }
    }
    //MODAL

    app.$set(
        dataVue, 
        "valorNaCarteira", 
        isNaN(parseFloat(await GetSessaoPHP("VALORCARTEIRA"))) ? "00,00" : parseFloat(await GetSessaoPHP("VALORCARTEIRA")).toFixed(2).replace(".", ",")  
    );


    

    await retornaValorAvaliacao();


    /* Pega os dados do usuário do banco */    
    let usuario = await WMExecutaAjax("UsuarioBO", "GetFuncionarioById", {"ID": dataVue.idGeral });

    /*STAR RATING*/
    await app.$set(dataVue, "Rating", !parseFloat(usuario.avaliacao_media) ? 0 : parseFloat(usuario.avaliacao_media));
    await app.$set(dataVue, "StarSize", 40);

    if(innerWidth < 1620) {
        await app.$set(dataVue, "StarSize", 32);
    }
    else if(innerWidth < 1300) {
        await app.$set(dataVue, "StarSize", 30);
    }

    /* ATIVADOR DO POPOVER */
    setTimeout(() => {
        $(function () {
            $('[data-toggle="popover"]').popover()
        })
    }, 10);
    /*--------------------*/

    /*----------------- CÓDIGO PARA ABRIR e fechar MODAL --------------*/
    app.$set(dataVue, "modalVisivelController", false);

    app.$set(dataVue, "abremodal", async () => {
        dataVue.modalVisivelController = true;
        dataVue.imagemCropada = null;
    });

    app.$set(dataVue, "fechamodal", async () => {
        dataVue.modalVisivelController = false;
    });


    
    
    app.$set(dataVue, "modalVisivelEditPerfil", false); /* Modal de Editar Perfil*/

    function abrirFecharModalEP() {
        dataVue.modalVisivelEditPerfil = !dataVue.modalVisivelEditPerfil;

        setTimeout(
            () => {
                /* Aplica estilo css no input de tags */
                $($("#tagsInput input")[0]).on("focus", ()=>{
                    $($("#tagsInput .form-control")[0]).addClass("shadowInputTags");
                });
        
                $($("#tagsInput input")[0]).on("focusout", ()=>{
                    $($("#tagsInput .form-control")[0]).removeClass("shadowInputTags");
                });
                $($("#tagsInput input")[0]).attr("placeholder","Adicionar Tag")


                $($("#tagsInput input")[0]).keydown(function(event){
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if(keycode == '8'){
                        if($($("#tagsInput input")[0]).val() == "") {
                            let tagsParaColocar = dataVue.usuarioDadosEdit.tags.split(",");
                            tagsParaColocar.pop();
                            dataVue.usuarioDadosEdit.tags = tagsParaColocar.join(",");
                        }
                    }
                });
        }, 200);
        
    }

    $("#botaoEditarPerfil").on("click", async () => {
        await abrirFecharModalEP();
    })

    setTimeout(async () => {
        let edit = getURLParameter("edit");
        if(edit != "null" && edit == 1) {
            await abrirFecharModalEP();  
            setTimeout( () => {
                $($("#tagsInput input")[0]).on("focus", ()=>{
                    $($("#tagsInput .form-control")[0]).addClass("shadowInputTags");
                });  
                
                $($("#tagsInput input")[0]).focus();
            }, 1)
                
        }
    }, 10);

    app.$set(dataVue, "callbackEP", (salvar) => {
        
        if(JSON.stringify(dataVue.usuarioDados) != JSON.stringify(dataVue.usuarioDadosEdit)) {
            dataVue.abreModalConfirmacao();
        } else {
            dataVue.modalVisivelEditPerfil = false;
        }

        if(salvar && WMVerificaForm()) {
            dataVue.fechaModalConfirmacao(true, true);
        }
    });

    
    /*--------------------------------------------------------*/

    /*----------------- CÓDIGO PARA PASSAR IMAGEM PARA O CROP --------------*/
    app.$set(dataVue, "imagemToCrop", "");
    app.$set(dataVue, "mudaImagemToCrop", async (imgData) => {
        dataVue.imagemToCrop = imgData;
    });
    /*--------------------------------------------------------*/

    /*----------------- CÓDIGO PARA PASSAR IMAGEM PARA O CROP --------------*/
    app.$set(dataVue, "imagemCropadaBanner", "");
    app.$set(dataVue, "imagemCropadaUsuario", "");

    app.$set(dataVue, "passaImagemCropada", async (img) => {
        if(img.componente == "banner") 
        {
            dataVue.imagemCropadaBanner = img.img;
        } 
        else 
        {
            dataVue.imagemCropadaUsuario = img.img;
        }
    });
    /*--------------------------------------------------------*/

    /* Código para configurações do modal crop */
    app.$set(dataVue, "configuracoesCrop", 
    {
        proporcao: '1/1',
        titulo: 'RECORTAR IMAGEM',
        componente: '',
        redondo: "rectangle-stencil"

    });

    app.$set(dataVue, "salvaConfiguracoes", async (arrayConfig) => {
        dataVue.configuracoesCrop.proporcao = arrayConfig.proporcao;
        dataVue.configuracoesCrop.titulo = arrayConfig.titulo;
        dataVue.configuracoesCrop.componente = arrayConfig.componente;
        if(arrayConfig.redondo) {
            dataVue.configuracoesCrop.redondo = "circle-stencil";
        } else {
            dataVue.configuracoesCrop.redondo = "rectangle-stencil";
        }
    });


    let funcionario = await WMExecutaAjax("UsuarioBO", "GetFuncionarioByIdDataEdit", {ID: dataVue.idGeral});
    app.$set(dataVue, "usuarioDados", { /* Se você precisar adicionar mais algum, os nomes tem que ser exatamente iguais aos do banco, Mateus do futuro. */
            nome: funcionario.nome,
            profissao: funcionario.profissao,
            tags: funcionario.tags == null ? "" : funcionario.tags,
            descricao: funcionario.descricao
    });

    atualizaOsDadosDoPerfil();
    app.$set(dataVue, "usuarioDadosEdit", { /* Se você precisar adicionar mais algum, os nomes tem que ser exatamente iguais aos do banco, Mateus do futuro. */
        nome: "",
        profissao: "",
        tags: "",
        descricao: ""
    });
    resetaOsDadosDoPerfilEdit();

    /* Função de Salvar os dados*/ 
    app.$set(dataVue, "mandarDados", async () => {
        let usuarioId = await GetSessaoPHP("IDUSUARIOCONTEXTO");

        let objectToSend = CopiaEntidade(dataVue.usuarioDadosEdit);
        objectToSend.ID = usuarioId;
        let resultado = await WMExecutaAjax("UsuarioBO", "EditaUsuario", {UsuarioDados: objectToSend});
        if(resultado) {
            if(resultado != 1) 
            {
                toastr.info(resultado, 'Ops');
            }
            else if(resultado == 1) 
            {
                let funcionario = await WMExecutaAjax("UsuarioBO", "GetFuncionarioByIdDataEdit", {ID: usuarioId});
                toastr.success("Os seus novos dados foram salvos", "Dados Atualizados");
                for (let prop in dataVue.usuarioDados) {
                    if(prop == "avaliacao_media") {

                    } else {
                        dataVue.usuarioDados[prop] = funcionario[prop];
                    }
                }
                atualizaOsDadosDoPerfil();
            }

        } else {
            toastr.info("Dados inválidos", 'Ops');
        }
    });


    /*Controle do modal de confirmação */
    app.$set(dataVue, "modalVisivelControllerConfirmacao", false);

    app.$set(dataVue, "abreModalConfirmacao", async () => {
        dataVue.botaoSalvarConfirmacao = "Descartar";
        dataVue.tituloModalConfirmacao = "Descartar alterações";
        dataVue.textoModalConfirmacao = "Você tem certeza que deseja descartar as alterações?";
        dataVue.modalVisivelControllerConfirmacao = true;
    });

    app.$set(dataVue, "fechaModalConfirmacao", async (confirmacao, salvar) => {
        // O parametro de confirmação é pra confirmar se é pra fechar mesmo, se só quiser fechar só colocar como true.
        dataVue.modalVisivelControllerConfirmacao = false;
        if(confirmacao) {
            if(dataVue.modalVisivelEditPerfil) {
                dataVue.modalVisivelEditPerfil = false;
                if(!salvar) {
                    resetaOsDadosDoPerfilEdit()
                }
            } else if(dataVue.modalVisivelAtribuirP) {
                dataVue.modalVisivelEditPerfil = false;
                if(!salvar) {
                    mandaOsDadosAtribuirProjeto();
                }
            }
        }
    });




    /* Modal de Planos*/
    app.$set(dataVue, "modalVisivelPlanos", false);

    app.$set(dataVue, "abremodalPlanos", async () => {
        dataVue.modalVisivelPlanos = true;
    });

    app.$set(dataVue, "callbackPlanos", () => {
        dataVue.modalVisivelPlanos = false;
    });

    app.$set(dataVue, "darUpgradePlano", async (nivel) => {

        let valorNaCarteira = parseFloat(await GetSessaoPHP("VALORCARTEIRA"));
        valorNaCarteira = isNaN(valorNaCarteira) ? 0 : valorNaCarteira; 
        let valor = 0;

        let valesNaCarteira = parseFloat(await GetSessaoPHP("VALESPATROCINIOS"));
        valesNaCarteira = isNaN(valesNaCarteira) ? 0 : valesNaCarteira; 
        let vales = 0;

        switch (nivel) {
            case 1:
                valor = 25;
                break;
            case 2:
                valor = 50;
                vales = 10;
                break;
            case 3:
                valor = 80;
                vales = 20;
                break;
            default:
                break;
        }

        if(valor <= valorNaCarteira) {
            let usuarioId = await GetSessaoPHP("IDUSUARIOCONTEXTO");
            let resultado = await AtualizaUsuarioColuna(usuarioId, "plano", nivel, "PLANO", "funcionario");
            
            if(resultado) {
                let resultadoVales = AtualizaUsuarioColuna(usuarioId, "vales_patrocinios", vales, "VALES_PATROCINIOS", "funcionario");
                switch (nivel) {
                    case 0:
                        toastr.info("Seus planos foram cancelados.", 'Assinado!');
                        break;
                    case 1:
                        toastr.info("R$ 25,00 foram deduzidos de sua carteira.", 'Você agora é um Membro Plus!');
                        break;
                    case 2:
                        toastr.info("R$ 50,00 foram deduzidos de sua carteira.", 'Você agora é um Membro Prime!');
                        break;
                    case 3:
                        toastr.info("R$ 80,00 foram deduzidos de sua carteira.", 'Você agora é um Membro Master!');
                        break;
                    default:
                        toastr.info("Upgrade na conta concedido.", 'Assinado!');
                        break;
                }
    
                valor = valorNaCarteira - valor;
                let resultado = AtualizaUsuarioColuna(usuarioId, "valor_carteira", valor, "VALOR_CARTEIRA");
                dataVue.valorNaCarteira = valor.toFixed(2).replace(".", ",");

                dataVue.callbackPlanos();
                
                dataVue.iconePlano = await dataVue.retornaPlano();
            }

        } else {
            toastr.error("Adicione mais fundos pelo botão \"+\" ao lado de sua carteira.", 'Falta de fundos na carteira!');
        }
            
            
        
    });


    app.$set(dataVue, "iconePlano", "src/img/icons/perfil/planoPadrao.svg");
    app.$set(dataVue, "situacaoBotao", [2, 0, 0, 0]);
    app.$set(dataVue, "retornaPlano", async () => {
        let planoNLet = parseInt(await GetSessaoPHP("PLANO"));
        planoNLet = !planoNLet? 0 : planoNLet;

        let vales = Number.parseFloat(await GetSessaoPHP("VALESPATROCINIOS"));
        let membro = "Membro Padrão";
        switch (planoNLet) {
            case 0:
                planoNLet = "src/img/icons/perfil/planoPadrao.svg";
                dataVue.situacaoBotao = [2, 0, 0, 0];

                membro = "Membro Padrão";
                $("#popoverPlano").attr("data-content", `Como ${membro}, você tem uma taxa de 20% por serviço terminado, além de não possuir nenhum privilégio.`);
                document.getElementById("tituloMembroPlano").innerText = membro;

                break;
            case 1:
                planoNLet = "src/img/icons/perfil/planoPlus.svg";
                dataVue.situacaoBotao = [0, 1, 0, 0];

                membro = "Membro Plus";
                $("#popoverPlano").attr("data-content", `Como ${membro}, você tem uma taxa de 15% por serviço terminado.`);
                document.getElementById("tituloMembroPlano").innerText = membro;

                break;
            case 2:
                planoNLet = "src/img/icons/perfil/planoPrime.svg";
                dataVue.situacaoBotao = [0, 0, 1, 0];

                membro = "Membro Prime";
                $("#popoverPlano").attr("data-content", `Como ${membro}, você tem uma taxa de 12% por serviço terminado. Você também possui ${vales} vales patrocínio restantes.`);
                document.getElementById("tituloMembroPlano").innerText = membro;

                break;
            case 3:
                planoNLet = "src/img/icons/perfil/planoMaster.svg";
                dataVue.situacaoBotao = [0, 0, 0, 1];
                
                membro = "Membro Master";
                $("#popoverPlano").attr("data-content", `Como ${membro}, você tem uma taxa de 8% por serviço terminado. Você também possui ${vales} vales patrocínio restantes.`);
                document.getElementById("tituloMembroPlano").innerText = membro;

                break;
            default:
                break;
        }
        return planoNLet;
    });
    
    if(dataVue.nivelUsuario == 1) {
        dataVue.iconePlano = await dataVue.retornaPlano();
    }
    

    /* Modal de Carteira*/
    app.$set(dataVue, "modalVisivelCarteira", false);

    app.$set(dataVue, "abremodalCarteira", async () => {
        dataVue.modalVisivelCarteira = true;

        setTimeout(() => {
            $("#inputDinheiro").on("keyup", (e) => {
                let valorInput = $("#inputDinheiro").val();
                if(!isNaN(valorInput)) {
                    if(valorInput > 10000) {
                        $("#inputDinheiro").val(10000.00)
                    }
                }
            });
            $("#inputDinheiro").on("change", () => {
                let valorInput = $("#inputDinheiro").val();
                $("#inputDinheiro").val(parseFloat(valorInput).toFixed(2));

                dataVue.usuarioDadosPagamento.valor = valorInput;
            });

            $("input[name='cartao']").on("change", (e) => {
                let valorInput = e.currentTarget.id;
                dataVue.usuarioDadosPagamento.cartao = valorInput;
            });


            $('#inputDataCartaoP').mask('00/00');
            $('#inputCVCCartaoP').mask('0000');
            $('#inputNumeroCartaoP').mask('0000 0000 0000 0000');
            
        }, 100);
        
    });

    app.$set(dataVue, "callbackCarteira", () => {
        dataVue.modalVisivelCarteira = false;
        app.$set(dataVue, "usuarioDadosPagamento", {
            numeroCartao: "",
            nome: "",
            expiracao: "",
            CVC: "",
            cartao: "",
            valor: ""
        });
    });
    
    await DesbloquearTela();


    app.$set(dataVue, "adicionarFundos", async (valor) => {

        $("#inputDinheiro").removeClass("erroImportant");
        let formVerificado = WMVerificaForm()

        /*Validações básicas adicionais*/

        if(!dataVue.usuarioDadosPagamento.valor || parseFloat(dataVue.usuarioDadosPagamento.valor).toFixed(2) == "0.00") {
            $("#inputDinheiro").addClass("erroImportant");
            if(formVerificado) {
                if(!dataVue.usuarioDadosPagamento.cartao) {
                    toastr.error("Preencha todos os campos.", 'Ops!');
                } else {
                    toastr.error("Preencha a quantia de dinheiro a ser adicionada.", 'Campo Inválido!');
                }
            }
            
        } else if(!dataVue.usuarioDadosPagamento.cartao) {
            toastr.error("Selecione o tipo do seu cartão.", 'Cartão Inválido!');
        }

        if(formVerificado && dataVue.usuarioDadosPagamento.cartao && dataVue.usuarioDadosPagamento.valor) {
            let usuarioId = await GetSessaoPHP("IDUSUARIOCONTEXTO");
            let valorNaCarteira = await GetSessaoPHP("VALORCARTEIRA");
            valorNaCarteira = isNaN(valorNaCarteira) || !valorNaCarteira ? 0 : valorNaCarteira;

            valor = parseFloat(valorNaCarteira) + parseFloat(valor);

            let resultado = AtualizaUsuarioColuna(usuarioId, "valor_carteira", valor, "VALOR_CARTEIRA");

            if(resultado) {
                dataVue.callbackCarteira();
                toastr.info("Você pode checar o tanto que possui em carteira no seu perfil.", 'Fundos adicionados!');
                dataVue.valorNaCarteira = valor.toFixed(2).replace(".", ",");
                // dataVue.iconePlano = await dataVue.retornaPlano();
                
            }
        }

        
    });


    /* Modal de Pagamentos */
    app.$set(dataVue, "usuarioDadosPagamento", {
        numeroCartao: "",
        nome: "",
        expiracao: "",
        CVC: "",
        cartao: "",
        valor: ""
    });



    app.$set(dataVue, "meusProjetos", {});

    /*Modal de Atribuir Projeto */
    app.$set(dataVue, "modalVisivelAtribuirP", false);

    app.$set(dataVue, "abremodalAtribuirP", async () => {
        dataVue.modalVisivelAtribuirP = true;
        let result = await WMExecutaAjax("ProjetoBO", "BuscaMeusProjetosAtribuirP", {id_destinatario: dataVue.idGeral});
        dataVue.meusProjetos = result.lista;
    });

    app.$set(dataVue, "callbackAtribuirP", () => {
        dataVue.modalVisivelAtribuirP = false;
    });


    app.$set(dataVue, "botaoSalvarConfirmacao", "");
    app.$set(dataVue, "idMeuProjeto", 0);
    app.$set(dataVue, "tituloMeuProjeto", "");
    app.$set(dataVue, "mandarPropostaUsuario", (id, titulo) => {
        dataVue.tituloMeuProjeto = titulo;
        dataVue.idMeuProjeto = id;
        dataVue.botaoSalvarConfirmacao = "Confirmar";
        dataVue.tituloModalConfirmacao = "ENVIAR PROJETO";
        dataVue.textoModalConfirmacao = "Você deseja enviar este projeto para o funcionário?";
        dataVue.modalVisivelControllerConfirmacao = true;
    })

    setTimeout(async () => {
        let edit = getURLParameter("edit");
        if(edit != "null" && edit == 2 && dataVue.UsuarioContexto.NIVEL_USUARIO == "0") {
            dataVue.abremodalContratar();                     
        }
    }, 1);
    
    app.$set(dataVue, "tituloModalConfirmacao", "Descartar alterações");
    app.$set(dataVue, "textoModalConfirmacao", "Você tem certeza que deseja descartar as alterações?");

});




function retornaValorAvaliacao() {
    let funcTemp = async () => {
    }
    return funcTemp();
}

function atualizaOsDadosDoPerfil() {
    /* Atualiza dados do perfil */
    $("#bVNome").text(dataVue.usuarioDados.nome)
    $("#nomeCP").text(dataVue.usuarioDados.nome)

    if(dataVue.usuarioDados.profissao) {
        $("#profCP").text(dataVue.usuarioDados.profissao)
    }

    if(document.getElementById("tagsCPWrapper")) {
        let tagsParaColocar = dataVue.usuarioDados.tags.split(",");
        let tagsParaColocar2 = "";
        tagsParaColocar.forEach(tag => {
            if(tag == "") {

            } else {
                tagsParaColocar2 += `<div class='tagCP'>${tag}</div>`;
            }
            
        });

        document.getElementById("tagsCPWrapper").innerHTML = tagsParaColocar2;
    }
    if(dataVue.usuarioDados.descricao) {
        document.getElementById("descricaoPerfil").innerHTML = dataVue.usuarioDados.descricao.replace(/(?:\r\n|\r|\n)/g, '<br>');
    }
}

function resetaOsDadosDoPerfilEdit() {
    
    /* 
    Atualiza os dados do modal de editar perfil com o que tem dentro do usuario Dados 
    (é mais rapido pois não puxa do banco, mas só serve quando descarta o modal) 
    */
    dataVue.usuarioDadosEdit.nome = dataVue.usuarioDados.nome;
    
    dataVue.usuarioDadosEdit.descricao = dataVue.usuarioDados.descricao;
    dataVue.usuarioDadosEdit.profissao = dataVue.usuarioDados.profissao;
    dataVue.usuarioDadosEdit.tags = dataVue.usuarioDados.tags ? dataVue.usuarioDados.tags : "";
}


async function mandaOsDadosAtribuirProjeto () {
    dataVue.callbackAtribuirP();
    dataVue.callbackContratar();
    let idGet = getURLParameter("id");
    
    mandaNotificacaoFuncionario(dataVue.idMeuProjeto, idGet, dataVue.UsuarioContexto.Nome, dataVue.tituloMeuProjeto, dataVue.usuarioDados.nome);

}

