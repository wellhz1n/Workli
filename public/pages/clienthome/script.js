$(document).ready(async() => {
    // var usrContexto = await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO);
    // app.$set(dataVue, "msg", []);
    // var ft = await WMExecutaAjax("UsuarioBO", "GetUsuarioById", { ID: 1 });
    // debugger
    // app.$set(dataVue, "ftbot", '');
    // dataVue.ftbot = ft.imagem;

    // dataVue.msg = await GetMensagens();

    // async function GetMensagens() {
    //     let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", { ID_CHAT: 4, ID_USUARIO1: usrContexto, ID_USUARIO2: 1 });
    //     msg = msg.map(x => {
    //         x.tipo = TipoMensagem.MSG
    //         return x;
    //     });
    //     return msg;
    // }
    // setInterval(async() => {
    //     dataVue.msg = await GetMensagens();
    // }, 1000)
    // app.$set(dataVue, "NovaMensagem", async(mensagem = MensagemEntidade()) => {
    //     try {

    //         mensagem.id_chat = 4;
    //         let saida = await WMExecutaAjax("ChatBO", "NovaMensagem", { MENSAGEM: mensagem, ID_SERVICO: 23 });

    //         if (saida.error == undefined) {
    //             if (saida.split('|')[0] == "OK")
    //                 console.log('a');
    //             // dataVue.selecionadoController.id_chat = JSON.parse(saida.split('|')[1]);
    //             else if (saida == false)
    //                 throw Error("Mensagem não enviada");
    //         } else
    //             throw Error("Mensagem não enviada");
    //     } catch (err) {
    //         console.warn("Error+++++= " + err.message);
    //         toastr.error("Mensagem não enviada", "Chat");
    //     }
    // });

});