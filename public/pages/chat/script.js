$(document).ready(async() => {
    BloquearTela();
    //#region Propriedades
    app.$set(dataVue, "menuLateral", false);
    app.$set(dataVue, "Carregando", true);
    app.$set(dataVue, "ListaDeProjetos", []);
    app.$set(dataVue, "ChatClick", ProjetoClick);
    app.$set(dataVue, "ConversaClick", ConversaClick);
    app.$set(dataVue, "ChatSelecionado", null);
    app.$set(dataVue, "Mensagens",[]);
    app.$set(dataVue, "ListaDeConversas",[]);
    app.$set(dataVue, "ConversaSelecionada",null);
    app.$set(dataVue, "BackButton",BackButton);
    app.$set(dataVue, "MostraChat",false);
    app.$set(dataVue, "HeaderTitulo","Selecione Uma Projeto");
    app.$set(dataVue, "NovaMensagem",NovaMensagem );

    //#endregion
    var ChatTimeInterval = null;
    dataVue.ListaDeProjetos = await WMExecutaAjax("ChatBO", "BuscaServicosChat");

    dataVue.Carregando = false
    dataVue.menuLateral = true
    DesbloquearTela();
    function BackButton(){
        dataVue.MostraChat = false;
        dataVue.ConversaSelecionada = null;
        dataVue.Mensagens = [];
        dataVue.HeaderTitulo = "Selecione Uma Conversa";
        clearInterval(ChatTimeInterval);
    }
    async function ConversaClick(item){
        dataVue.ConversaSelecionada = item;
        dataVue.Mensagens = [];
        await BuscaMensagem(dataVue.ConversaSelecionada.id);
        dataVue.MostraChat = true;
        dataVue.menuLateral = false;
        dataVue.HeaderTitulo = "Conversa com: <p class='m-0 p-0 ml-1' style='font-size:17px'><b style='color:red'>"+dataVue.ConversaSelecionada.nome+"</b></p>";
        ChatTimeInterval = setInterval(async()=>{
        await BuscaMensagem(dataVue.ConversaSelecionada.id);
        },1500);         
    }
    async function ProjetoClick(item) {
        if(item == dataVue.ChatSelecionado){
            clearInterval(ChatTimeInterval);
            dataVue.MostraChat = false;
            dataVue.ChatSelecionado = null;
            dataVue.ListaDeConversas = [];
            ChatTimeInterval = null;
            dataVue.HeaderTitulo = "Selecione Um Projeto";
            return;
        }
        dataVue.ChatSelecionado = item;
        if(dataVue.UsuarioContexto.NIVEL_USUARIO == 1){
            dataVue.HeaderTitulo = "Conversa com: <p class='m-0 p-0 ml-1' style='font-size:17px'><b style='color:red'>"+dataVue.ChatSelecionado.nome+"</b></p>";
                dataVue.Mensagens = [];
                await BuscaMensagem(dataVue.ChatSelecionado.id_usuario);
                dataVue.MostraChat = true;
                dataVue.menuLateral = false;
                ChatTimeInterval = setInterval(async()=>{
                await BuscaMensagem(dataVue.ChatSelecionado.id_usuario);
                },1500);              
        }
        else if(dataVue.UsuarioContexto.NIVEL_USUARIO == 0){
            dataVue.HeaderTitulo = "Selecione Uma Conversa";
                dataVue.ListaDeConversas = await WMExecutaAjax("ChatBO","GetListaContatos",{ID_CHAT:dataVue.ChatSelecionado.id_chat == null?
                    -1:dataVue.ChatSelecionado.id_chat});
                dataVue.menuLateral = false;
        
            }
    }
    async function BuscaMensagem(id_usuario_destinatario){
        if (dataVue.MostraChat) {
            let msg = await WMExecutaAjax("ChatBO", "GetMensagensProjeto", { ID_CHAT: dataVue.ChatSelecionado.id_chat,
                 ID_USUARIO1: dataVue.UsuarioContexto.id, ID_USUARIO2:id_usuario_destinatario });
                 dataVue.Mensagens = msg.map(x => {
                x.tipo = TipoMensagem.MSG
                return x;
            });
    }
}
        async function NovaMensagem(mensagem = MensagemEntidade()){
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