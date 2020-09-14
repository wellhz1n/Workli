$(document).ready(async() => {
    //REDIRECIONAR PARA CLIENTE
    //RediredionarComParametros('chat',[{chave:'id_chat',valor:3},{chave:'id',valor:7}])
    //PARA FUNCIONARIO NESCESSITA ID_CHAT E ID DO FUNCIONARIO
    BloquearTela();
    //#region Propriedades
    app.$set(dataVue, "menuLateral", false);
    app.$set(dataVue, "Carregando", true);
    app.$set(dataVue, "ListaDeProjetos", []);
    app.$set(dataVue, "ChatClick", (item, e) => { ProjetoClick(item, e) });
    app.$set(dataVue, "ConversaClick", ConversaClick);
    app.$set(dataVue, "ChatSelecionado", null);
    app.$set(dataVue, "Mensagens", []);
    app.$set(dataVue, "ListaDeConversas", []);
    app.$set(dataVue, "ConversaSelecionada", null);
    app.$set(dataVue, "BackButton", BackButton);
    app.$set(dataVue, "MostraChat", false);
    app.$set(dataVue, "HeaderTitulo", "Selecione Uma Projeto");
    app.$set(dataVue, "NovaMensagem", NovaMensagem);

    //#endregion
    //#region Variaveis
    var ChatTimeInterval = null;
    var Parametros = GetParam();
    //#endregion
    dataVue.ListaDeProjetos = await WMExecutaAjax("ChatBO", "BuscaServicosChat");
    //#region  AUTOMACAO
    if (dataVue.UsuarioContexto.NIVEL_USUARIO != await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO))
        dataVue.UsuarioContexto.NIVEL_USUARIO = await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO);

    if (Parametros.length > 0) {
        if (Parametros[0].id_chat != undefined) {
            await dataVue.ListaDeProjetos.map(async(item) => {
                if (item.id_chat == Parametros[0].id_chat)
                    await ProjetoClick(item)
            });
        }
        if(Parametros[0].P !== undefined){
            await dataVue.ListaDeProjetos.map(async(item) => {
                if (item.id_servico == Parametros[0].P)
                    await ProjetoClick(item)
            });
        }
    }
    //#endregion
    dataVue.Carregando = false
    dataVue.menuLateral = Parametros.length > 0 ? false : true;
    DesbloquearTela();

    function BackButton() {
        dataVue.MostraChat = false;
        dataVue.ConversaSelecionada = null;
        dataVue.Mensagens = [];
        dataVue.HeaderTitulo = "Selecione Uma Conversa";
        clearInterval(ChatTimeInterval);
    }
    async function ConversaClick(item) {
        dataVue.ConversaSelecionada = item;
        dataVue.Mensagens = [];
        await BuscaMensagem(dataVue.ConversaSelecionada.id);
        dataVue.MostraChat = true;
        dataVue.menuLateral = false;
        dataVue.HeaderTitulo = "Conversa com: <p class='m-0 p-0 ml-1' style='font-size:17px'><b style='color:red'>" + dataVue.ConversaSelecionada.nome + "</b></p>";
        ChatTimeInterval = setInterval(async() => {
            await BuscaMensagem(dataVue.ConversaSelecionada.id);
        }, 1500);
    }
    async function ProjetoClick(item, e) {
        if (item == dataVue.ChatSelecionado && e != undefined) {
            clearInterval(ChatTimeInterval);
            dataVue.MostraChat = false;
            dataVue.ChatSelecionado = null;
            dataVue.ListaDeConversas = [];
            ChatTimeInterval = null;
            dataVue.HeaderTitulo = "Selecione Um Projeto";
            return;
        }
        dataVue.ChatSelecionado = item;
        if (dataVue.UsuarioContexto.NIVEL_USUARIO == 1) {
            dataVue.HeaderTitulo = "Conversa com: <p class='m-0 p-0 ml-1' style='font-size:17px'><b style='color:red'>" + dataVue.ChatSelecionado.nome + "</b></p>";
            dataVue.Mensagens = [];
            await BuscaMensagem(dataVue.ChatSelecionado.id_usuario);
            dataVue.MostraChat = true;
            dataVue.menuLateral = false;
            ChatTimeInterval = setInterval(async() => {
                await BuscaMensagem(dataVue.ChatSelecionado.id_usuario);
            }, 1500);
        } else if (dataVue.UsuarioContexto.NIVEL_USUARIO == 0) {
            dataVue.HeaderTitulo = "Selecione Uma Conversa";
            dataVue.ListaDeConversas = await WMExecutaAjax("ChatBO", "GetListaContatos", {
                ID_CHAT: dataVue.ChatSelecionado.id_chat == null ?
                    -1 : dataVue.ChatSelecionado.id_chat
            });
            dataVue.menuLateral = false;
            if (Parametros.length > 0) {
                if (Parametros[1].id != undefined) {
                    await dataVue.ListaDeConversas.map(async item => {
                        await ConversaClick(item);
                    })
                }
            }

        }
    }
    async function BuscaMensagem(id_usuario_destinatario) {
        if (dataVue.MostraChat) {
            let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", {
                ID_CHAT: dataVue.ChatSelecionado.id_chat,
                ID_USUARIO1: dataVue.UsuarioContexto.id,
                ID_USUARIO2: id_usuario_destinatario
            });
            dataVue.Mensagens = msg.map(x => {
                x.tipo = TipoMensagem.MSG
                return x;
            });
        }
    }
    async function NovaMensagem(mensagem = MensagemEntidade()) {
        try {

            mensagem.id_chat = dataVue.ChatSelecionado.id_chat;
            let saida = await WMExecutaAjax("ChatBO", "NovaMensagem", { MENSAGEM: mensagem, ID_SERVICO: dataVue.ChatSelecionado.id_servico }, false);

            if (saida.error == undefined) {
                if (saida == false)
                    throw Error("Mensagem não enviada");
            } else
                throw Error("Mensagem não enviada");
        } catch (err) {
            console.warn("Error+++++= " + err.message);
            toastr.error("Mensagem não enviada", "Chat");
        }
    }
});