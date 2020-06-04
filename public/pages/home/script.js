var Row = null;
$(document).ready(async() => {

    app.$set(dataVue, "ItemMaisUsado", await GetItemMaisUsado());


    async function GetItemMaisUsado() {

        let Busca = await WMExecutaAjax("TipoServicoBO", "GetMaisUtilizados");
        if (Busca != undefined && Busca.length > 0) {
            return Busca;
        }
        return [];
    }

    var tabela = await Tabela("dtUsuarios", "GetUsuarioTable", "UsuarioBO");

    CliqueTabela("dtUsuarios", tabela, (saida) => {
        Row = saida;
    }, null, () => {
        Row = null;
    });




});


// $(document).ready(()=> {
//     $(".bolinhaPaginacao").on("click",(ev) => {
//         var el = ev.currentTarget;

//         var posicaoEl =  $(el).index(".bolinhaPaginacao") + 1;

//         switch (posicaoEl) {
//             case 1:
//                 $("#carrosselImagem").css("background-image", "url('src/img/login/prestadores-de-servico.jpg')")
//                 break;

//             default:
//                 break;
//         }

//        ev.css("background-color", "red");
//     });
// });