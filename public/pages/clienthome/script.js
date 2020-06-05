$(document).ready(async() => {
    app.$set(dataVue, "img", ["src/img/background/background.png",
        "src/img/login/cliente.jpg",
        "src/img/login/imagemCarrossel1.jpg",
        "src/img/login/imagemCarrossel2.jpg"
    ]);
    app.$set(dataVue, 'imgselecionada', null);
    app.$set(dataVue, "modalVisivelController", false);
    app.$set(dataVue, "imgClick", (img) => {;
        dataVue.imgselecionada = img;
        dataVue.modalVisivelController = true;
    })



});