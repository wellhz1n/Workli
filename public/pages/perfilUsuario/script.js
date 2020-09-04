$(document).ready(async () => {
    

    await BloquearTela();
    let img =  await GetSessaoPHP("FOTOUSUARIO");
    await app.$set(dataVue, "Usuario", { imagem:img == ""? null : img, imgTemp: null });
    $("#Titulo").text("Editar Usuário");


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

    await DesbloquearTela();

    await retornaValorAvaliacao();


    /* Pega os dados do usuário do banco */    
    let usuarioId = await GetSessaoPHP("IDUSUARIOCONTEXTO");
    let usuario = await WMExecutaAjax("UsuarioBO", "GetFuncionarioById", {"ID": usuarioId });

    /*STAR RATING*/
    await app.$set(dataVue, "Rating", parseFloat(usuario.avaliacao_media));
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

    $("#botaoEditarPerfil").on("click", () => {
        abrirFecharModalEP();
    })

    app.$set(dataVue, "callbackEP", (salvar) => {
        dataVue.abreModalConfirmacao();
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


    let funcionario = await WMExecutaAjax("UsuarioBO", "GetFuncionarioByIdDataEdit", {ID: usuarioId});
    app.$set(dataVue, "usuarioDados", { /* Se você precisar adicionar mais algum, os nomes tem que ser exatamente iguais aos do banco, Mateus do futuro. */
            nome: funcionario.nome,
            profissao: funcionario.profissao,
            tags: funcionario.tags,
            descricao: funcionario.descricao
    });

    atualizaOsDadosDoPerfil();
    app.$set(dataVue, "usuarioDadosEdit", { /* Se você precisar adicionar mais algum, os nomes tem que ser exatamente iguais aos do banco, Mateus do futuro. */
        nome: "",
        profissao: "",
        tags: [],
        descricao: ""
    });
    resetaOsDadosDoPerfilEdit();


    
    /* Função de Salvar os dados*/ 
    app.$set(dataVue, "mandarDados", async () => {
        let usuarioId = await GetSessaoPHP("IDUSUARIOCONTEXTO");
        let objectToSend = Object.assign(dataVue.usuarioDadosEdit, {"ID": usuarioId});
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
        dataVue.modalVisivelControllerConfirmacao = true;
    });

    app.$set(dataVue, "fechaModalConfirmacao", async (confirmacao, salvar) => {
        // O parametro de confirmação é pra confirmar se é pra fechar mesmo, se só quiser fechar só colocar como true.
        dataVue.modalVisivelControllerConfirmacao = false;
        if(confirmacao) {
            dataVue.modalVisivelEditPerfil = false;
            if(!salvar) {
                resetaOsDadosDoPerfilEdit()
            }
        } else {
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
        let usuarioId = await GetSessaoPHP("IDUSUARIOCONTEXTO");
        let objectToSend = {ID: usuarioId, coluna: "plano", dado: nivel, secao: "PLANO"}
        let resultado = await WMExecutaAjax("UsuarioBO", "SetDadoUsuario", {dados: objectToSend});
        if(resultado) {
            dataVue.callbackPlanos();
            toastr.info("Upgrade na conta concedido.", 'Assinado!');
            dataVue.iconePlano = await dataVue.retornaPlano();
            
        }
    });


    app.$set(dataVue, "iconePlano", "src/img/icons/perfil/planoPadrao.svg");
    app.$set(dataVue, "situacaoBotao", [0, 0, 0]);
    app.$set(dataVue, "retornaPlano", async () => {
        let planoN = Number.parseInt(await GetSessaoPHP("PLANO"));
        let membro = "Membro Padrão";
        switch (planoN) {
            case 0:
                planoN = "src/img/icons/perfil/planoPadrao.svg";
                dataVue.situacaoBotao = [0, 0, 0];

                membro = "Membro Padrão";
                $("#popoverPlano").attr("data-content", `Como ${membro}, você tem uma taxa de 20% por serviço terminado, além de não possuir nenhum privilégio.`);
                document.getElementById("tituloMembroPlano").innerText = membro;

                break;
            case 1:
                planoN = "src/img/icons/perfil/planoPlus.svg";
                dataVue.situacaoBotao = [1, 0, 0];

                membro = "Membro Plus";
                $("#popoverPlano").attr("data-content", `Como ${membro}, você tem uma taxa de 15% por serviço terminado.`);
                document.getElementById("tituloMembroPlano").innerText = membro;

                break;
            case 2:
                planoN = "src/img/icons/perfil/planoPrime.svg";
                dataVue.situacaoBotao = [0, 1, 0];

                membro = "Membro Prime";
                $("#popoverPlano").attr("data-content", `Como ${membro}, você tem uma taxa de 12% por serviço terminado.`);
                document.getElementById("tituloMembroPlano").innerText = membro;

                break;
            case 3:
                planoN = "src/img/icons/perfil/planoMaster.svg";
                dataVue.situacaoBotao = [0, 0, 1];
                
                membro = "Membro Master";
                $("#popoverPlano").attr("data-content", `Como ${membro}, você tem uma taxa de 8% por serviço terminado.`);
                document.getElementById("tituloMembroPlano").innerText = membro;

                break;
            default:
                break;
        }
        return planoN;
    });

    dataVue.iconePlano = await dataVue.retornaPlano();
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

    if(dataVue.usuarioDados.tags && document.getElementById("tagsCPWrapper")) {
        let tagsParaColocar = dataVue.usuarioDados.tags.split(",");
        let tagsParaColocar2 = "";
        tagsParaColocar.forEach(tag => {
            tagsParaColocar2 += `<div class='tagCP'>${tag}</div>`;
        });

        document.getElementById("tagsCPWrapper").innerHTML = tagsParaColocar2;
    }
    if(dataVue.usuarioDados.descricao) {
        document.getElementById("descricaoPerfil").innerHTML = dataVue.usuarioDados.descricao.replace(/(?:\r\n|\r|\n)/g, '<br>');
    }
}
function resetaOsDadosDoPerfilEdit() {
    
    /* Atualiza os dados do modal de editar perfil com o que tem dentro do usuario Dados 
    (é mais rapido pois não puxa do banco, mas só serve quando descarta o modal) 
    */
    dataVue.usuarioDadosEdit.nome = dataVue.usuarioDados.nome;
    
    dataVue.usuarioDadosEdit.descricao = dataVue.usuarioDados.descricao;
    dataVue.usuarioDadosEdit.profissao = dataVue.usuarioDados.profissao;
    dataVue.usuarioDadosEdit.tags = dataVue.usuarioDados.tags ? dataVue.usuarioDados.tags : "";
}