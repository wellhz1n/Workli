$(document).ready(async () => {
    app.$set(dataVue, "usuarioFiltro", { /* Se você precisar adicionar mais algum, os nomes tem que ser exatamente iguais aos do banco, Mateus do futuro. */
        profissao: "",
        tags: "",
        avaliacao: 0,
        nivel: ""
    });

    app.$set(dataVue, "imagemUsuario", await GetSessaoPHP("FOTOUSUARIO"));

    setTimeout(() => {
        $($("#filtroWrapper .form-control input")[0]).on("focus", ()=>{
            $($("#filtroWrapper .form-control")[0]).addClass("shadowInputTags");
        });  

        $($("#filtroWrapper .form-control input")[0]).on("focusout", ()=>{
            $($("#filtroWrapper .form-control")[0]).removeClass("shadowInputTags");
        });  

    }, 1)


    if(document.getElementsByClassName("tagsCUWrapper")) {
        function scrollHorizontally(e) {
            e = window.event || e;
            var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
            document.getElementsByClassName("tagsCUWrapper").scrollLeft -= (delta*20); // Multiplied by 40
            e.preventDefault();
        }
        if (document.getElementsByClassName("tagsCUWrapper").addEventListener) {
            // IE9, Chrome, Safari, Opera
            document.getElementsByClassName("tagsCUWrapper").addEventListener("mousewheel", scrollHorizontally, false);
            // Firefox
            document.getElementsByClassName("tagsCUWrapper").addEventListener("DOMMouseScroll", scrollHorizontally, false);
        } else {
            // IE 6/7/8
            document.getElementsByClassName("tagsCUWrapper").attachEvent("onmousewheel", scrollHorizontally);
        }
    }
    //MODAL
});