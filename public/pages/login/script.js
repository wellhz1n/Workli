(function($) {
    "use strict";

    /*==================================================================
    [ Focus no Contact ]*/
    $('.input100').each(function() {
        $(this).on('blur', function() {
            if ($(this).val().trim() != "") {
                $(this).addClass('has-val');
            } else {
                $(this).removeClass('has-val');
            }
        })
    })


    /*==================================================================
    [ Validação depois de digitar ]*/
    $('.validate-input .input100').each(function() {
        $(this).on('blur', function() {
            if (validate(this) == false) {
                showValidate(this);
            } else {
                $(this).parent().addClass('true-validate');
            }
        })
    })

    /*==================================================================
    [ Validação]*/
    var input = $('.validate-input .input100');

    $('.validate-form').on('submit', function() {
        var check = true;

        for (var i = 0; i < input.length; i++) {
            if (validate(input[i]) == false) {
                showValidate(input[i]);
                check = false;
            }
        }

        return check;
    });


    $('.validate-form .input100').each(function() {
        $(this).focus(function() {
            hideValidate(this);
            $(this).parent().removeClass('true-validate');
        });
    });

    function validate(input) {
        if ($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if ($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        } else {
            if ($(input).val().trim() == '') {
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }



})(jQuery);


let _User = Usuario; // Usuario do Registro

$(document).ready(() => {
    $('.cpf').mask('000.000.000-00');
    $('#cpfModal').mask('000.000.000-00'); // Coloca mascara no input de cpf
    // Coloca mascara no input de cpf
    /* Validação basica */
    $("#BTN_Login").on('click', () => { /* Manda dados do Login*/

        let Dados = SerialiazaGrupoForm($('#formLogin'))[0];
        let Campos = Dados.map(x => {
            if (x.value == null || x.value == '') {
                toastr.warning(`Campo necessário ${x.name}`, 'Ops');
                return false;
            }
            return true;
        });
        if (!Campos.filter(x => x == false).length > 0) {
            Logar(Dados[0].value, Dados[1].value);
        }
    });


    $("#BTN_Register").on('click', () => {
        let form = SerialiazaGrupoForm($("#formRegistrar")); /* Manda os dados para logar */
        form[0].map(x => {
            _User[x.name] = x.value
        });
        $.ajax({
            url: '../app/BO/UsuarioBO.php',
            data: { metodo: 'CadastraUsuario', Usuario: JSON.stringify(_User) },
            type: 'post',
        }).then((output) => {
            if (output == true) {
                Logar(_User.email, _User.senha);
            } else
                toastr.warning(output, 'Ops');
            console.log(output)
        })
    });

    /* Faz a animação do registro de ir pro lado*/


    $(".alternarLoginRegistro").click(function() {
        alternaLadoImagem();
    });

    $(".botaoDeTroca").on('click', () => {
        ativaBotaoFuncionarioClientes();
    });

    $("#cpfModal").on('keyup', (e) => {
        if ($('#cpfModal').cleanVal().length == 11) {
            $('#mdContinuar').removeAttr('disabled');
            $('#mdContinuar').removeClass('disabled');
        } else if (!$('#mdContinuar').hasClass('disabled')) {
            $('#mdContinuar').addClass('disabled');
            $('#mdContinuar').attr('disabled', true);
        }
    });



    /* Atualiza o visual da class OU */
    $(".textoOu").css("padding", "0 24px 0 24px")

});
// function alternaLadoImagem() {
//     if ($('.funcionarioForm').hasClass('taNoRegistro')) {

//         $('.funcionarioForm').removeClass("taNoRegistro");
//         $("#formRegistrar").addClass("formRegistrarInvisivelTran")

//         $("#formRegistrar").on('transitionend', () => {     
//             $("#formRegistrar").removeClass("formRegistrarInvisivelTran");
//             $("#formRegistrar").removeClass("formRegistrarVisivel");
//             $(".sombraFundoRegistrar").removeClass("sombraFundoRegistrar");

//             $(".usuarioForm").addClass("usuarioDivTran");
//             $(".usuarioForm").on('transitionend', () => { 
//                 $(".usuarioForm").removeClass("usuarioDivTran");
//                 $(".usuarioForm").removeClass("usuarioDiv");
//                 $(".funcionarioForm").addClass("funcionarioDivTrans");
//                 $(".funcionarioForm").on('transitionend', () => { 
//                     $(".funcionarioForm").removeClass("funcionarioDiv");
//                     $("#formLogin").removeClass("formInvisivel");
//                 });
//             });
//         });

//         $(".funcionarioForm").removeClass("funcionarioDivTrans");
//         // $(".usuarioForm").on('transitionend', () => { 
//         //     $(".funcionarioForm").removeClass("funcionarioDiv");
//         // });
//         // $(".funcionarioForm").removeClass("funcionarioDiv")

//         //     $(".funcionarioForm").on('transitionend', () => { 
//         //         $(".usuarioForm").removeClass(".usuarioDiv");
//         //         $(".usuarioForm").on('transitionend', () => { 
//         //             $("#formLogin").removeClass(".formInvisivel");
//         //         });
//         //     });
//         //     // $("#formRegistrar").removeClass("formRegistrarVisivel")
//         //     // 
//         //     // debugger
//         // });


//         // $("#Titulo").text("Login")



//     } else {

//         // $(".imagemPrincipalLogin").addClass("imagemIntermediariaDiv")

//         // $("#Titulo").text("Registrar")
//         debugger
//         $(".funcionarioForm").removeClass("funcionarioDivTrans");

//         $("#formLogin").addClass("formVisualmenteInvisivel");

//         $("#formLogin").on('transitionend', () => { //Quando terminar a animação
//             $("#formLogin").addClass("formInvisivel");
//             $("#formLogin").removeClass("formVisualmenteInvisivel");
//         });



//         $(".sombraFundoLogar").addClass("sombraFundoRegistrar");

//         $(".funcionarioForm").addClass("funcionarioDiv")
//         $(".sombraFundoLogar").on('transitionend', () => { //Quando terminar a animação de aumentar o height;
//             $(".funcionarioForm").on('transitionend', () => {
//                 $(".usuarioForm").addClass("usuarioDiv");
//             });
//             $(".usuarioForm").on('transitionend', () => {
//                 if ($('.funcionarioForm').hasClass('taNoRegistro')) 
//                 {   
//                     $("#formRegistrar").addClass("formRegistrarVisivel"); 
//                 } else {
//                 }
//             });
//         });
//     }

//     $('.funcionarioForm').addClass("taNoRegistro");
// }

function alternaLadoImagem() {
    if ($('.funcionarioForm').hasClass('funcionarioDiv')) {

        $(".funcionarioForm").removeClass("funcionarioDiv")
        $(".imagemPrincipalLogin").removeClass("imagemIntermediariaDiv")
        $(".usuarioForm").removeClass("usuarioDiv")

        $("#Titulo").text("Login")

    } else {

        $(".usuarioForm").addClass("usuarioDiv")
        $(".funcionarioForm").addClass("funcionarioDiv")
        $(".imagemPrincipalLogin").addClass("imagemIntermediariaDiv")

        $("#Titulo").text("Registrar")

    }
}


function ativaBotaoFuncionarioClientes() {
    if ($(".deslize").hasClass("corAzulDegradeSlider")) {
        $(".deslize").removeClass("corAzulDegradeSlider");
        $(".fundoTrocarPraAzul").removeClass("corAzulDegrade");
        $(".fundoTrocarPraAzulImagem").removeClass("corAzulDegradeImagem")
        $(".tituloRegistrar").text("Registrar — Cliente")

        // Texto Switch
        $(".switchTexto").text("Deseja-se registrar como Funcionário?")


        // Usado dentro do modal
        $("#legenda").text("Cliente");
        $(".checkFuncionario").each(
            (key) => {
                $(".checkFuncionario")[key].checked = false
            }
        );
    } else {
        $(".deslize").addClass("corAzulDegradeSlider");
        $(".fundoTrocarPraAzul").addClass("corAzulDegrade");
        $(".fundoTrocarPraAzulImagem").addClass("corAzulDegradeImagem");
        $(".tituloRegistrar").text("Registrar — Funcionário")

        // Texto Switch
        $(".switchTexto").text("Deseja-se registrar como Cliente?")

        // Usado dentro do modal
        $("#legenda").text("Funcionário");

        $(".checkFuncionario").each(
            (key) => {
                $(".checkFuncionario")[key].checked = true
            }
        );
    }
}