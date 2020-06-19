$(document).ready(async() => {
    app.$set(dataVue, "menuLateral", true);
    app.$set(dataVue, "ListaDeProjetos", []);


    $(".IconeMenuBar").click(() => {
        dataVue.menuLateral = !dataVue.menuLateral;
    });
    $('#CHAT').click(() => {
        dataVue.menuLateral = false;

    });

});