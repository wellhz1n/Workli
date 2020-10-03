var MenuPai = null;
var MenuLateralColapsado;
var GoogleLogin = false;

function ClassesStatics() {
    var CacheSeletor = [];
}




$(document).ready(async () => {

    $(".itemMenu").addClass("itemMenuPadding");
    $("#navbarDropdownNotify").click(function (e) {
        e.stopPropagation();
    })
    //#region NotificacaoNavegador
    Notification.requestPermission(result => {
        if (result === 'denied') {
            console.log('Permission wasn\'t granted. Allow a retry.');
            return;
        } else if (result === 'default') {
            console.log('The permission request was dismissed.');
            return;
        }
    });
    if (localStorage.getItem('Notificacao') == null) {

        var notification = new Notification("Título", {
            icon: 'http://localhost:8089/wellhz1n/Workli/Logo/LogoNotify.png',
            body: "Texto da notificação"
        });
        localStorage.setItem('Notificacao', true);
        notification.onclick = function () {
            window.open("http://localhost:8089/wellhz1n/Workli/public/?page=home");
        }
        setTimeout(() => {
            notification.close();
        }, 6000)
    }
    //#endregion



    async function AtualizaUsuarioContexto() {
        dataVue.UsuarioContexto.Email = await GetSessaoPHP(SESSOESPHP.EMAIL);
        dataVue.UsuarioContexto.Foto = await GetSessaoPHP(SESSOESPHP.FOTO_USUARIO);
        dataVue.UsuarioContexto.NIVEL_USUARIO = await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO);
        dataVue.UsuarioContexto.Nome = await GetSessaoPHP(SESSOESPHP.NOME);
        dataVue.UsuarioContexto.id = await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO);
    }
    // Padrao();
    await AtualizaUsuarioContexto();
    $('#MenuSair').on('click', () => {
        $.ajax({
            url: '../app/BO/UsuarioBO.php',
            data: { metodo: 'Logout' },
            type: 'post',
        }).then(async (output) => {
            Rediredionar('home');
        });
    })

    $('#app').on('click', () => {
        if (MenuLateralColapsado != undefined)
            if (!MenuLateralColapsado)
                $('[data-toggle=sidebar-colapse]').click();
    });

    let menuHeader = $(`#menuHeader #${(MenuPai != null && GetPageName() != "home") ? MenuPai : GetPageName()}`)[0];
    $(menuHeader).addClass('MenuHeaderAtivo');



    //#region FOOTER

    setTimeout(() => {
        WMExecutaAjax("ProjetoBO", "BuscaNumeroProjetos").then(result => {
            if ($("#numeroFooterServices")[0] != undefined) {
                $("#numeroFooterServices")[0].innerText = result["COUNT(id)"];
            }
        });

        WMExecutaAjax("UsuarioBO", "BuscaNumeroUsuarios").then(result => {
            if ($("#numeroFooterUsers")[0] != undefined) {
                $("#numeroFooterUsers")[0].innerText = result["COUNT(id)"];
            }
        });
    }, 50);
    //#endregion


});

//Funcoes de grid
function EscondeElemento(elemento) {
    $(elemento).fadeOut('slow');
    $(elemento).prop("hidden", true);
}

function AparecerElemento(elemento) {
    $(elemento).fadeIn('slow');
    $(elemento).prop("hidden", false);

}

function Adicionar(Teladeadicionar, Teladelistagem) {
    EscondeElemento(Teladelistagem);
    EscondeElemento("#btnNovo");
    EscondeElemento("#btnDeletar");
    EscondeElemento("#btnEditar");
    AparecerElemento("#btnCancelar");
    AparecerElemento("#btnSalvar");
    AparecerElemento(Teladeadicionar);

}

function Cancelar(Teladeadicionar, Teladelistagem) {


    LimpaFormulario(Teladeadicionar);
    AparecerElemento(Teladelistagem);
    AparecerElemento("#btnNovo");
    AparecerElemento("#btnDeletar");
    AparecerElemento("#btnEditar");
    EscondeElemento("#btnCancelar");
    EscondeElemento("#btnSalvar");
    EscondeElemento(Teladeadicionar);


}


function LimpaFormulario(TeladoForm) {
    let c = TeladoForm + " form ";
    $(c)[0].reset();
    $(c).removeClass("border-danger");
    //$(c).toArray().forEach(o => o.value = null);
}

async function Tabela(idtabela, action, BO) {
    //Exemplo de coluna
    //{ "data": "Nome", "title": "Nome", "autowidth": true }
    let colunas = TableGetColuns(idtabela);
    let table = await $('#' + idtabela).DataTable({
        language: {
            "lengthMenu": "Exibe _MENU_ Registros por página",
            "search": "Procurar",
            "paginate": { "previous": "Retorna", "next": "Avança" },
            "zeroRecords": "Nada foi encontrado",
            "info": "Exibindo página _PAGE_ de _PAGES_",
            "infoEmpty": "Sem registros",
            "infoFiltered": "(filtrado de _MAX_ regitros totais)"
        },
        lengthChange: false,
        //"processing": true,
        //"serverSide": true,
        //"filter": true, // habilita o filtro(search box)
        //"lengthMenu": [[3, 5, 10, 25, 50, -1], [3, 5, 10, 25, 50, "Todos"]],
        pageLength: 10,
        destroy: true,
        responsive: true,
        ajax: {
            url: "../app/BO/" + BO + ".php",
            type: "POST",
            data: {
                metodo: action
            },
            complete: async function (foi) {
                await DesbloquearTela();
            },
            beforeSend: async function () {
                await BloquearTela();
            }
        },
        columns: colunas
    });
    //$('.dataTables_length').addClass('bs-select');
    return table;
}

function TableGetColuns(idtabela) {
    let tab = $("#" + idtabela + "> thead > tr > th");
    let colunas = [];
    for (let i = 0; i < tab.length; i++) {
        colunas.push({ "data": tab[i].attributes.name.value, "title": tab[i].innerText != "" ? tab[i].innerText : tab[i].attributes.name.value, "autowidth": true });
    }
    return colunas;
}

function ValorInput(obj, form) {
    form = $("#" + form).serializeArray();
    let objarray = []
    objarray = Object.values(obj);

    for (var i = 0; i < form.length; i++) {

        if ($("input[name =\'" + form[i].name + "\' ]").length > 1) {
            for (let b = 0; b < $("input[name =\'" + form[i].name + "\' ]").length; b++) {
                if ($($("input[name =\'" + form[i].name + "\' ]")[b]).val() == objarray[i]) {
                    $($("input[name =\'" + form[i].name + "\' ]")[b]).prop('checked', 'checked');
                }
            }

        } else {
            $("input[name =\'" + form[i].name + "\' ]").val(objarray[i]);
        }
    }


}

function SerialiazaGrupoForm(grupoform) {
    let formserialized = [];
    for (let i = 0; i <= grupoform.length; i++) {
        let grupoatual = grupoform[i];
        formserialized.push($(grupoatual).serializeArray());
    }
    return formserialized;
}

function ResetaGrupoFormulario(grupoform) {
    for (let i = 0; i < grupoform.length; i++) {
        $(grupoform)[i].reset();

    }
}

function ImprimirNoConsole(msg, tipo = "error") {
    switch (tipo) {
        case "error":
            console.error("❌ ERRO NO C#: " + msg);
            break;
        case "warn":
            console.warn("⚠️ Ops: " + msg);
            break;
        case "default":
            console.log("✌️ Console: " + msg);
            break;

        default:
    }
}

function ObjetoENulo(obj) {
    var state = true;
    for (let key in obj) {
        if (!(obj[key] === null || obj[key] === "")) {
            state = false;
            break;
        }
    }
    return state;
}

function CopiaEntidade(obj) {
    let o = $.extend(true, {}, obj);
    return o;
}
//MONETARIO+++++++++++++++++++++++++++++++++++++++++++++++++++++
function ConverteDinheiroToNumber(dinheiro) {
    let cifra = dinheiro.split(':').length >= 2;
    let milhar = cifra ? dinheiro.substr(3).split('.').length >= 2 : dinheiro.split('.').length >= 2;
    let dinheironum;
    if (cifra)
        dinheironum = milhar ? Number((dinheiro.substr(3).replace(',', '.').replace('.', '')).replace(/[^0-9\.-]+/g, "")) : Number((dinheiro.substr(3).replace(',', '.')).replace(/[^0-9\.-]+/g, ""));
    else
        dinheironum = milhar ? Number((dinheiro.replace(',', '.').replace('.', '')).replace(/[^0-9\.-]+/g, "")) : Number((dinheiro.replace(',', '.')).replace(/[^0-9\.-]+/g, ""));
    return dinheironum;
}

function ConverteNumEmDinheiro(dinheiroNum, comcifrao = false) {
    let casas = (Number(dinheiroNum.toString().replace(/[^0-9\.-]+/g, ""))).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    let subcasas = casas.substr(casas.length - (casas.length + 3)).replace('.', ',');
    let dinheiroformated = !comcifrao ? casas.substr(0, casas.length - 3).replace(',', '.') + subcasas : "R$:" + casas.substr(0, casas.length - 3).replace(',', '.') + subcasas;
    return dinheiroformated;
}

function GetMesesEntre(mes = [2]) {
    let lista = [];
    for (let i = mes[0]; i <= mes[1]; i++) {
        lista.push(Meses[i]);
    }
    return lista;
}
//
//CHART.JS
//
let mychart = null;

function GerarGraficoAnual(idchart, tipo, labels = [], label, data = [], labelstring) {
    let ctx = document.getElementById(idchart).getContext('2d');
    let config = {
        type: tipo,
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)',
                    'rgba(58, 189, 141,0.5)',
                    'rgba(186, 135, 67,0.5)',
                    'rgba(22, 145, 245,0.5)',
                    'rgba(227, 105, 255,0.5)',
                    'rgba(182,224,43,0.5)',
                    'rgba(91, 3, 158,0.5)',
                    'rgba(9, 142, 144,0.5)',
                    'rgba(210, 29, 132, 0.5)',
                    'rgba(154, 22, 25, 0.5)',
                    'rgba(25, 26, 82, 0.5)',
                    'rgba(125, 179, 196, 0.5)',
                    'rgba(233, 130, 100, 0.5)',
                    'rgba(200, 10, 4, 0.5)',
                    'rgba(158, 19, 131,0.5)',
                    'rgba(186, 15, 255,0.5)',

                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(58, 189, 141,1)',
                    'rgba(186, 135, 67,1)',
                    'rgba(22, 145, 245,1)',
                    'rgba(215, 38, 255,1)',
                    'rgba(182,224, 43,1)',
                    'rgba(91, 3, 158,1)',
                    'rgb(9, 142, 144)',
                    'rgba(210, 29, 132, 1)',
                    'rgba(154, 22, 25, 1)',
                    'rgba(25, 26, 82, 1)',
                    'rgba(125, 179, 196, 1)',
                    'rgba(233, 130, 100, 1)',
                    'rgba(200, 10, 4, 1)',
                    'rgba(158, 19, 131,1)',
                    'rgba(186, 15, 255,1)',

                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                        //max: this.max,
                        //callback: function (value) {
                        //    return (value / this.max * 100).toFixed(0) + '%'; // convert it to percentage
                        //},
                    },
                    scaleLabel: {
                        display: true,
                        labelString: labelstring,
                    },

                }]
            }
        }
    }
    if (mychart == null) {

        mychart = new Chart(ctx, config);
    } else if (mychart != null) {
        mychart.destroy();
        mychart = new Chart(ctx, config);

    }

};

function CliqueTabela(idtabela, tabela, Objeto = () => { }, editar = () => { }, limpar = () => { }) {
    let obj;
    $(`#${idtabela} tbody`).on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            limpar(obj);
        } else {
            tabela.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            obj = tabela.row(this).data();
            Objeto(obj);
        }
    });
    $(`#${idtabela} tbody`).on('dblclick ', 'tr', function () {

        obj = tabela.row(this).data();
        Objeto(obj);
        if (!ObjetoENulo(obj)) {
            editar(obj);
        } else
            toastr.warning("Selecione um registro", "Editar", { timeOut: 2000 });
    });
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function Rediredionar(pagina) {
    window.location.href = location.origin + location.pathname + `?page=${pagina}`;
}

// OS PARANS RECEBEM UM OBJETO COM CHAVE E VALOR
function RedirecionarComParametros(pagina, paramn = [], abreOutraAba = false) {
    let url = location.origin + location.pathname + `?page=${pagina}`;
    paramn.forEach(p => {
        url = url + `&${p.chave}=${p.valor}`
    });
    if (!abreOutraAba)
        window.location.href = url;
    else
        window.open(url, "_blank");
}

function GetParam() {
    let parans = [];
    let url = location.href.split('?');
    if (url.length > 1) {
        url = url[1];
        url = url.split('&');
        url.forEach((item, index) => {
            var tmp = url[index].split('=');
            tmp.forEach((i, ix) => {
                if (ix < tmp.length - 1) {

                    let obj = {};
                    obj[tmp[ix]] = tmp[ix + 1];
                    parans.push(obj);
                }
            });
        });
    }
    parans = parans.filter(item => {
        return Object.entries(item)[0][0] != 'page';
    })
    return parans
}



async function Logar(email, senha, mostramsg = true) {
    await $.ajax({
        url: '../app/BO/UsuarioBO.php',
        data: { metodo: 'Logar', email: email, senha: senha },
        type: 'post',
    }).then(async (output) => {
        let saida;
        saida = JSON.parse(output);
        try {
            saida = JSON.parse(saida[0]);
        } catch (e) {
            if (saida[0] == true) {
                GetSessaoPHP("IDUSUARIOCONTEXTO").then((id) => { RedirecionarComParametros('perfilUsuario', [{ chave: 'id', valor: id }]); })
                return true;
            }

        }
        if (saida.error != undefined) {
            if (mostramsg)
                toastr.warning(saida.error, "Ops")
            return false;
        }
        if (saida == true) {
            GetSessaoPHP("IDUSUARIOCONTEXTO").then((id) => { RedirecionarComParametros('perfilUsuario', [{ chave: 'id', valor: id }]); })
            return true;
        }

    });
}

function ReturnNamesRequiredInputs(formId) {
    return $(`#${formId} input`).filter((x, y) => y.required).map((x, y) => y.name)
}

function WMExecutaAjax(BO, metodo, dados = {}, ConvertJSON = true, MostraMensagem = false) {

    let temp_data = { metodo: metodo };
    let dataProp = Object.getOwnPropertyNames(dados);
    dataProp.forEach(dt => {
        temp_data[dt] = dados[dt];
    });
    return new Promise((resolve, reject) => {

        $.ajax({
            url: `../app/BO/${BO}.php`,
            data: temp_data,
            type: 'post',
        }).then(Resultados => {
            if (Resultados != "" || Resultados != null)
                try {
                    Resultados = ConvertJSON ? JSON.parse(Resultados) : Resultados;
                }
                catch (err) {
                    console.warn("ERRO PHP:\n" + Resultados);
                    if (MostraMensagem)
                        MostraMensagem("Algo Deu Errado", TipoMensagem.ERROR);
                }
            if (Resultados.error != undefined) {
                console.error(`ERROR++++++++++++++++++++++++++:\n ${Resultados.error}`);
                if (MostraMensagem)
                    MostraMensagem("Algo Deu Errado", TipoMensagem.ERROR);
            }
            resolve(Resultados);
        }).catch(err => {
            reject(err);
        });

    })

}

function WMVerificaForm() {
    let teste = true;
    let listaInputs = [];
    app.$children.forEach(x => {
        for (let item in x.$children) {
            if (x.$children[item].obrigatorio) {
                if (x.$children[item].$attrs.tipo == 'wm-seletor' && x.$children[item].selecionado == null)
                    listaInputs.push(x.$children[item]);
                else if (x.$children[item].$attrs.tipo != "wm-seletor") {
                    if (x.$children[item].$attrs.tipo == "wm-imagem") {
                        if (x.$children[item].$data.listaImagem.filter(x => !x.deletado).length == 0) {
                            listaInputs.push(x.$children[item]);
                        } else if (x.$children[item].$data.listaImagem.filter(x => x.principal && !x.deletado).length == 0) {
                            listaInputs.push(x.$children[item]);
                            toastr.info(`O campo <strong>${x.$children[item].titulo}</strong> deve possuir uma imagem como a principal.`, 'Ops');
                        }
                    } else if (x.$children[item].value == "" || x.$children[item].value == null)
                        listaInputs.push(x.$children[item]);
                }
            }
        }
        if (listaInputs.length > 1)
            listaInputs.forEach(y => {
                $(`#${y.id}`).addClass('erro');
                teste = false;
            });
        else if (listaInputs.length > 0) {
            $(`#${listaInputs[0].id}`).addClass('erro');
            if (listaInputs[0].$attrs.tipo == "wm-imagem" && listaInputs[0].$data.listaImagem.filter(x => !x.deletado).length > 0 && listaInputs[0].$data.listaImagem.filter(x => x.principal && !x.deletado).length == 0) {
                teste = false;
                return false;
            } else {

                toastr.info(`Preencha o campo <strong>${listaInputs[0].titulo}</strong>.`, 'Ops');
                teste = false;
            }
        }
    });
    if (listaInputs.length > 1 && !teste)
        toastr.info(`Preencha os campos.`, 'Ops');

    return teste;
}

function WMLimpaErrorClass(id) {
    if ($(`#${id}`).hasClass('erro')) {
        $(`#${id}`).removeClass('erro')
    }


}

function BloquearTela(msg = "Carregando...") {
    $('#loading div p').text(msg);
    $('#loading').prop('hidden', false);
}

function DesbloquearTela() {
    $('#loading').prop('hidden', true);
}

function GetPageName() {

    let p = window.location.href.split('?page=')[1].split("&")[0];
    if (p == undefined)
        p = 'home';
    else if (p == 'admhome')
        p = 'home';
    else if (p == 'clienthome')
        p = 'home';
    else if (p == 'proghome')
        p = 'home';


    return p;
}


function GetSessaoPHP(sessao, ConvertJSON = false) {
    return new Promise(result => {

        let valor = WMExecutaAjax('SecoesBO', "GetSecoes", { 'sessao': sessao }, ConvertJSON).then(saida => {
            return saida;
        });
        result(valor);
    })
}
async function SetSessaoPHP(sessao, valor) {
    await WMExecutaAjax('SecoesBO', "SetSecoes", { 'sessao': sessao, 'valor': valor });
}
async function COnvertImagemBase64ToBLOB(img) {
    let imgconvertida = await atob(img);
    return imgconvertida;
}
async function COnvertImagemBLOBToBase64(img) {
    let imgconvertida = await btoa(img);
    return imgconvertida;
}
async function LerImagem(input) {

    return new Promise(async (resolve, reject) => {

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            var imgBase;
            reader.readAsDataURL(input.files[0]); // convert to base64 string
            reader.onload = await

                function (e) {
                    if (e != undefined) {

                        imgBase = e.target.result.split(',')[1];
                        resolve(imgBase);
                    }
                }
        }
    });
}
//MASCARA
var ChavesMask = {
    '#': { pattern: /\d/ },
    'S': { pattern: /[a-zA-Z]/ },
    'A': { pattern: /[0-9a-zA-Z]/ },
    'U': { pattern: /[a-zA-Z]/, transform: v => v.toLocaleUpperCase() },
    'L': { pattern: /[a-zA-Z]/, transform: v => v.toLocaleLowerCase() }
}

function applyMask(value, mask, masked = true) {
    value = value || ""
    var iMask = 0
    var iValue = 0
    var output = ''
    while (iMask < mask.length && iValue < value.length) {
        cMask = mask[iMask]
        masker = ChavesMask[cMask]
        cValue = value[iValue]
        if (masker) {
            if (masker.pattern.test(cValue)) {
                output += masker.transform ? masker.transform(cValue) : cValue
                iMask++
            }
            iValue++
        } else {
            if (masked) output += cMask
            if (cValue === cMask) iValue++
            iMask++
        }
    }
    return output
}


//Login Google

async function onSignIn(googleUser) {
    try {

        BloquearTela();
        var profile = googleUser.getBasicProfile();
        var nome = profile.getName();
        var email = profile.getEmail();
        var img = await BuscaImagemPorURLtoBase64(profile.getImageUrl());
        signOut();
        let emaiExiste = await WMExecutaAjax('UsuarioBO', 'VerificaEmailExiste', { EMAIL: email });
        if (!emaiExiste) {
            RegistraModal();
        } else {
            let log = await Logar(email, '', false);
            if (log != undefined && !log) {
                toastr.info('Email Já em Uso', 'Ops');
                return false
            }
        }
    } finally {
        GoogleLogin = true;
        DesbloquearTela();
    }

    function RegistraModal() {
        $('#modalCriarContaGoogle').on('hide.bs.modal', function (e) {
            signOut();
        });
        $('#nome').text(nome);
        $('#email').text(email);
        $('#imagem').attr("src", `data:image/png;base64,${img}`);
        $("#modalCriarContaGoogle").modal('show');
        $('#mdContinuar').on('click', async () => {
            try {
                BloquearTela();

                var nivel = $('.checkFuncionario').is(':checked') ? 1 : 0;
                var cpf = $('#cpfModal').cleanVal();
                var Resultado = await WMExecutaAjax('UsuarioBO', "RegistraUsuarioGoogle", { Usuario: { nome, email, cpf, nivel, imagem: img } });
                if (Resultado) {
                    $("#modalCriarContaGoogle").modal('hide');
                    Logar(email, '');
                } else {
                    toastr.info(Resultado, "Algo deu Errado");
                    return false;
                }
            } finally {
                DesbloquearTela();
            }
        })
    }


}

function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
        console.log('User signed out.');
    });
}

function BuscaImagemPorURLtoBase64(url) {
    return new Promise(async (resolve, reject) => {

        var xhr = await new XMLHttpRequest();
        xhr.onload = function () {
            var reader = new FileReader();
            reader.onloadend = async function () {
                resolve(reader.result.split(',')[1]);
            }
            reader.readAsDataURL(xhr.response);
        };
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
    });
}

function BloquearTelaSemLoader() {
    $('body').addClass('BloqueiaClick');
}

function DesbloquearTelaSemLoader() {
    $('body').removeClass('BloqueiaClick');

}

function ChatSeparatorGenerator(msgs = []) {
    //"13/06/2020 13:40:45"
    // let lista = [
    //     ["2020-06-10", "10:30:10"],
    //     ["2020-06-10", "12:30:10"],
    //     ["2020-06-11", "09:20:10"],
    //     ["2020-06-12", "15:10:10"],
    //     ["2020-06-13", "13:50:10"]
    // ]
    // msgs = lista.map((item, index) => {
    //     return MensagemEntidade(index, 'teste' + index, 0, 1, item[0], item[1])
    // });
    let msgModificada = [];
    for (let index = 0; index < msgs.length; index++) {
        if (msgs.filter(x => x.date == msgs[index].date && x.tipo == TipoMensagem.Separador).length == 0 &&
            msgModificada.filter(v => v.date == msgs[index].date && v.tipo == TipoMensagem.Separador).length == 0) {
            let separadorText = '';
            let ontem = new Date(new Date().setDate((new Date().getDate() - 1))).toISOString().split('T')[0];
            if (msgs[index].date == GetDataAtual())
                separadorText = "Hoje"
            else if (msgs[index].date == ontem)
                separadorText = "Ontem"
            else {
                let mes = new Date(msgs[index].date + " " + msgs[index].time).getMonth() + 1;
                separadorText = `${new Date(msgs[index].date + " " + msgs[index].time).getDate()}/${mes < 10 ? '0' + mes : mes}`
            }

            msgModificada.push(SeparadorMensagemEntidade(index, separadorText, msgs[index].date));

        }

        msgModificada.push(msgs[index]);
    }
    return msgModificada;
}


function GetDataAtual() {
    return `${new Date(new Date().toString('YYY-MM-DD')).getFullYear()}-${new Date(new Date().toString('YYY-MM-DD')).getMonth() + 1 < 10 ? '0' + (new Date(new Date().toString('YYY-MM-DD')).getMonth() + 1) : new Date(new Date().toString('YYY-MM-DD')).getMonth() + 1}-${new Date(new Date().toString('YYY-MM-DD')).getDate()}`;
}


function MostraMensagem(Mensagem, TipoMensagem = ToastType.INFO, Tiulo = document.title) {
    switch (TipoMensagem) {
        case ToastType.INFO:
            toastr.info(Mensagem, Tiulo);
            break;
        case ToastType.SUCCESS:
            toastr.success(Mensagem, Tiulo);
            break;
        case ToastType.ERROR:
            toastr.error(Mensagem, Tiulo);
            console.error("MENSAGEM \n" + Mensagem);
            break;
        case ToastType.WARN:
            toastr.warning(Mensagem, Tiulo);
            console.warn("MENSAGEM \n" + Mensagem);
            break;

        default:
            toastr.info(Mensagem, Tiulo);
            break;

    }
}
$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results == null) {
        return null;
    }
    return decodeURI(results[1]) || 0;
}

async function AtualizaUsuarioColuna(idUsuario, coluna, dado, sessao, tabela) {

    let objectToSend = { ID: idUsuario, coluna: coluna, dado: dado }
    if (sessao) {
        objectToSend["sessao"] = sessao;
    }
    if (tabela) {
        objectToSend["tabela"] = tabela;
    }

    let resultado = await WMExecutaAjax("UsuarioBO", "SetDadoUsuario", { dados: objectToSend });

    return resultado;
}


function getURLParameter(name) { /* Obtem a variavel do topo da tela*/
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, null])[1]
    );
}

async function mandaNotificacaoFuncionario(idMeuProjeto, idFuncionario, nomeCliente, projetoTitulo, nomeFuncionario) {
    var result = await WMExecutaAjax("ProjetoBO", "MandaMensagemFunc",
    {
        informacoes: {
            idProjeto: idMeuProjeto,
            idFuncionario: idFuncionario, 
            nomeCliente: nomeCliente,
            projetoTitulo: projetoTitulo
        }
    });
    if(result) {
        if(nomeFuncionario) {
            MostraMensagem(`O funcionário <strong>${nomeFuncionario}</strong> irá analisá-lo e fazer uma proposta.`, ToastType.SUCCESS, "Projeto enviado!");
        } else {
            MostraMensagem(`O funcionário irá analisá-lo e fazer uma proposta.`, ToastType.SUCCESS, "Projeto enviado!");
        }
    }
}