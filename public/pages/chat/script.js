$(document).ready(async() => {
    app.$set(dataVue, "menuLateral", false);
    app.$set(dataVue, "ListaDeProjetos", []);
    dataVue.ListaDeProjetos = await WMExecutaAjax("ChatBO", "BuscaServicosChat");
    dataVue.menuLateral = true
    $(".IconeMenuBar").click(() => {
        dataVue.menuLateral = !dataVue.menuLateral;
    });
    $('#CHAT').click(() => {
        dataVue.menuLateral = false;

    });

});