$(document).ready(async () => {
    
    

    await BloquearTela();
    let img =  await GetSessaoPHP("FOTOUSUARIO");
    await app.$set(dataVue, "Usuario", { imagem:img == ""?null:img, imgTemp: null });
    $("#Titulo").text("Editar Usuário");


    $('#imgcontainer').hover(() => {
        $('#maskEditImg').removeAttr('hidden');
    }, () => {
        $('#maskEditImg').attr('hidden', 'hidden');
    });

    // $("#tagsCPWrapper").on("mousewheel", function(event, delta) {

    //     this.scrollLeft -= (delta * 300);
    //     debugger
    //     event.preventDefault();
  
    // });

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
    //MODAL
    $('#maskEditImg').on('click', () => {
        $('#EditImgModal').modal('show');
        $('#maskEditImg').attr('hidden', 'hidden');
    });

    $('#imgcontainerModal').hover(() => {
        $('#maskEditImgModal').removeAttr('hidden');
    }, () => {
        $('#maskEditImgModal').attr('hidden', 'hidden');
    });

    $('#maskEditImgModal').on('click', () => {
        $('#maskEditImgModal').attr('hidden', 'hidden');
        var input = $(document.createElement("input"));
        input.attr("type", "file");
        input.attr("accept", "image/x-png,image/gif,image/jpeg");
        // add onchange handler if you wish to get the file :)
        input.trigger("click"); // opening dialog

        $(input).on('change', async () => {
            let imgBase = await LerImagem($(input)[0]);
            app.dataVue.Usuario.imgTemp = imgBase;
        });
        return false; // avoiding navigation


    });
    $('#FechaModal').click(async () => {
       await $('#EditImgModal').modal('hide');
       setTimeout(()=>{
           app.dataVue.Usuario.imgTemp = null;

       },800);
    })
    $('#SalvarImg').click(async () => {
       let retorno = await WMExecutaAjax("UsuarioBO","SalvaImagem",{'IMAGEM':app.dataVue.Usuario.imgTemp},false);
        if(retorno == "OK"){
            await $('#EditImgModal').modal('hide');
            dataVue.Usuario.imagem = dataVue.Usuario.imgTemp;
            app.dataVue.Usuario.imgTemp = null;
            toastr.info('Imagem Atualizada com Sucesso!','Sucesso',);
        }
        else{
            toastr.info(`Imagem Não Atualizada:<br><strong>${retorno}</strong>`,'Algo Deu Errado');
            console.warn(`ERROR:::${retorno}`);
            

        }

    });
    $('.cpf').mask('000.000.000-00');
    $('.telefone').mask('(00) 0000-0000');

    $(".telefone").keypress(() => {
        if($(".telefone")[0].value[5] == "9" && $(".telefone")[0].value.length == 14) {
            $(".telefone").keypress(() => {    
                $('.telefone').mask('(00) 0 0000-0000');
            });
        };
    });
    
    
    $(".telefone").keyup((e) => {  
        if ( $(".telefone")[0].value.length < 16) { 
            if(e.keyCode == 8 || $(".telefone")[0].value[5] != 9 ) {        
                $('.telefone').mask('(00) 0000-0000');
            }
        }
    })

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

});

function retornaValorAvaliacao() {
    let funcTemp = async () => {
        // let avaliacaoMedia = await GetSessaoPHP("AVALIACAOMEDIA");
        
        // ($(".rating").removeClass("value-3")).addClass(valorRating[0]);
        // $(".label-value")[0].innerText = Math.trunc(avaliacaoMedia * 10) / 10;
    }
    return funcTemp();
}