$("#Titulo").text("Buscar Usuários | Conserta");

$(document).ready(async () => {
    app.$set(dataVue, "usuarioFiltro", { /* Se você precisar adicionar mais algum, os nomes tem que ser exatamente iguais aos do banco, Mateus do futuro. */
        profissao: "",
        tags: "",
        avaliacao: 0,
        nivel: "",
        tipo_usuario: 0,
        queryBusca: ""
    });
    app.$set(dataVue, "opcoesSlider", {
        dotSize: 12,
        width: 8,
        height: 80,
        contained: true,
        direction: 'ttb',
        min: 0,
        max: 2,
        clickable: true,
        duration: 0.5,
        lazy: true,
        marks: {
            '0': 'Todos',
            '1': 'Apenas Clientes',
            '2':  'Apenas Funcionários'
        },
        dotOptions: {
            tooltip: 'none',
            style: {
                backgroundColor: "white"
            },
            focusStyle: {
                boxShadow: "0 0 1px 2px rgba(28, 216, 71, 0.37)"
            },
        },
        railStyle: {
            backgroundColor: "#B2FAB5",
            cursor: "pointer"
        },
        processStyle: {
            backgroundColor: "#B2FAB5",
            cursor: "pointer"
        },
        stepStyle: {
            backgroundColor: "rgb(85 239 120)",
            marginLeft: "0px"
        },
        labelStyle: {
            letterSpacing: '1px',
            fontSize: "13px",
            wordSpacing: "3px",
            cursor: "pointer"
        }
    })

    app.$set(dataVue, "imagemUsuario", await GetSessaoPHP("FOTOUSUARIO"));

    setTimeout(() => {
        $($("#filtroWrapper .form-control input")[0]).on("focus", ()=>{
            $($("#filtroWrapper .form-control")[0]).addClass("shadowInputTags");
        });  

        $($("#filtroWrapper .form-control input")[0]).on("focusout", ()=>{
            $($("#filtroWrapper .form-control")[0]).removeClass("shadowInputTags");
        });  

    }, 1)
    app.$set(dataVue, "Carregando", true);

    app.$set(dataVue, "usuarios", {lista: {}, pagina: 1});
    app.$set(dataVue, "paginaAtual", 1)
    dataVue.usuarios = await getUsuarios(
            dataVue.paginaAtual,
            dataVue.usuarioFiltro
    );
    
    app.$watch("dataVue.paginaAtual", async function (a, o) {
        if (a != o)
            dataVue.usuarios = await getUsuarios(a, dataVue.usuarioFiltro);
    });
    
    // if(document.getElementsByClassName("tagsCUWrapper")) {
    //     function scrollHorizontally(e) {
    //         e = window.event || e;
    //         var delta = Math.max(-1, Math.min(1, (e.wheelDelta || -e.detail)));
    //         document.getElementsByClassName("tagsCUWrapper").scrollLeft -= (delta*20); // Multiplied by 40
    //         e.preventDefault();
    //     }
    //     if (document.getElementsByClassName("tagsCUWrapper").addEventListener) {
    //         // IE9, Chrome, Safari, Opera
    //         document.getElementsByClassName("tagsCUWrapper").addEventListener("mousewheel", scrollHorizontally, false);
    //         // Firefox
    //         document.getElementsByClassName("tagsCUWrapper").addEventListener("DOMMouseScroll", scrollHorizontally, false);
    //     } else {
    //         // IE 6/7/8
    //         document.getElementsByClassName("tagsCUWrapper").attachEvent("onmousewheel", scrollHorizontally);
    //     }
    // }


    async function getUsuarios(P = 1, filtro = {}) {
        dataVue.Carregando = true;
        let result = await WMExecutaAjax("UsuarioBO", "BuscarUsuarios", {P, filtro });
        dataVue.Carregando = false;

        let nivel_usuario_contexto = await GetSessaoPHP("NIVELUSUARIO");

        result.lista.forEach(element => {
            element.nivel_usuario_contexto = nivel_usuario_contexto;
        });
        return {
            lista: result.lista,
            pagina: result.paginas == 0 ? 1 : result.paginas
        }

    };

    app.$set(dataVue, "trocarPagina", async (pagina) => { 
            dataVue.paginaAtual = pagina;
        }
    )

    app.$set(dataVue, "valorVelhoTipoUsuario", 0);
    
    app.$watch("dataVue.usuarioFiltro", async function (filtro) {
        
        if(filtro["tipo_usuario"] == dataVue.valorVelhoTipoUsuario) {
            if(filtro["avaliacao"] > 0 || filtro["profissao"]) {
                filtro["tipo_usuario"] = 2;
            }
        } else {
            if(filtro["tipo_usuario"] != 2) {
                filtro["avaliacao"] = 0;
                filtro["profissao"] = ""; 
            }
        }

        dataVue.trocarPagina(1);
        dataVue.usuarios = await getUsuarios(dataVue.paginaAtual, filtro);

        dataVue.valorVelhoTipoUsuario = filtro["tipo_usuario"];
    }, {
        deep: true
    });


    app.$set(dataVue, "buscaProfissoes", async (queryProf) => { 
       let profissoes = await WMExecutaAjax("UsuarioBO", "BuscarProfissoes", {queryProf});

       dataVue.executaFunc(queryProf);  

       return profissoes;
    });

    app.$set(dataVue, "executaFunc", async (v) => {
        dataVue.usuarioFiltro.profissao = v;
    })

    $('body').on('DOMSubtreeModified', '#autocomplete-result-list-1', function(){
        if(document.querySelector("#autocomplete-result-list-1").style.visibility == "hidden") {
            $("#inputProfissao").removeClass("inputProfissaoListaVisivel");
        } else {
            $("#inputProfissao").addClass("inputProfissaoListaVisivel");
        }
    });
});