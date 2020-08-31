$(document).ready(async () => {
    //#region VueData
    await app.$set(dataVue, 'TabNCategorias', GetTipoMensagem());
    await app.$set(dataVue, "TabNPagina", 1);
    await app.$set(dataVue, "TabNListCarregando", false);
    await app.$set(dataVue, "TabNCarregando", true);

    await app.$set(dataVue, "TabNList", []);
    await app.$set(dataVue, "TabNPageController", { paginas: 1, pagina_Atual: 1 });
    //#endregion
    //#region Watchers
    await app.$watch("dataVue.TabNPageController", async function (a, o) {
        await BuscaListaNotificacoes();
    }, { deep: true });
    await app.$watch("dataVue.TabNCategorias", async function (a, o) {
        await BuscaListaNotificacoes();
    }, { immediate:true,deep: true });
//#endregion
    await BuscaListaNotificacoes();
});