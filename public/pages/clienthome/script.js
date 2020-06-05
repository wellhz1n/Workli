$(document).ready(async() => {
    app.$set(dataVue, "img", ["src/img/background/background.png",
        "src/img/login/cliente.jpg",
        "src/img/login/imagemCarrossel1.jpg",
        "src/img/login/imagemCarrossel2.jpg"
    ])
    app.$set(dataVue, "modalVisivelController", false);
    app.$set(dataVue, "modalVisivelController1", false);
    app.$set(dataVue, "dataVue.callback", () => { dataVue.modalVisivelController = false; });
    app.$set(dataVue, "dataVue.callback1", () => { dataVue.modalVisivelController1 = false; });



});