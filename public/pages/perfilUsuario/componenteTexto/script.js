function mudaACorAoEditar(idContador) {
    let texto = async () => {
        var iconeEditar = $("#iconeEditar" + idContador)
        var inputCampo = $("#contadorInput" + idContador)
        var linhaDeBaixo = $("#contador" + idContador)
        if (iconeEditar.hasClass("fa-edit")) {
            linhaDeBaixo.addClass("focus-input100");

            inputCampo.attr("disabled", false);
            inputCampo.focus();
            inputCampo[0].selectionStart = inputCampo[0].selectionEnd = inputCampo[0].value.length;

            iconeEditar.addClass('fa-check').removeClass('fa-edit');
        } else {

            linhaDeBaixo.removeClass("focus-input100");

            inputCampo.attr("disabled", true);
            iconeEditar.addClass('fa-edit').removeClass('fa-check');


            /* Atualiza os dados no banco */
            let nomeCampo = inputCampo[0].name;
            let valorCampo = inputCampo[0].value;
            if (valorCampo) {
                // BloquearTela();
                await $.ajax({
                    url: '../app/BO/UsuarioBO.php',
                    data: { metodo: 'EditaUsuario', nomeCampo: nomeCampo, valorCampo: valorCampo, idUsuario: await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO) },
                    type: 'post'
                }).then((output) => {
                    console.log(output)
                    if (!!output && output != 1) {
                        toastr.warning(output, 'Ops');
                        
                    }
                })
                txt = await GetSessaoPHP(SESSOESPHP.NOME);
                $("#bemVindo")[0].innerText = "Bem vindo, " + txt;
                // await DesbloquearTela();
            }
        };
    }
    texto();
};