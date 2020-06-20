$(document).ready(async() => {
    BloquearTela();
    //#region Propriedades
    app.$set(dataVue, "menuLateral", false);
    app.$set(dataVue, "Carregando", true);
    app.$set(dataVue, "ListaDeProjetos", []);
    app.$set(dataVue, "ChatClick", ProjetoClick);
    app.$set(dataVue, "ChatSelecionado", null);
    //#endregion
    dataVue.ListaDeProjetos = await WMExecutaAjax("ChatBO", "BuscaServicosChat");

    dataVue.Carregando = false
    dataVue.menuLateral = true
    DesbloquearTela();

    function ProjetoClick(item) {
        dataVue.ChatSelecionado = item;
    }

});