
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
});

function retornaValorAvaliacao() {
    let funcTemp = async () => {
        let avaliacaoMedia = await GetSessaoPHP("AVALIACAOMEDIA");
        // Funcão que define o valor do rating:
        let valorRating = Math.round(avaliacaoMedia * 2) / 2;
        valorRating = ["value-" + valorRating, valorRating];
        
        ($(".rating").removeClass("value-3")).addClass(valorRating[0]);
        $(".label-value")[0].innerText = Math.trunc(avaliacaoMedia * 10) / 10;
    }
    return funcTemp();
}