<?php
require_once("../bootstrap.php");
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title id="Titulo"><?php echo load()[1] ?></title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <!-- Importações externas -->
    <link rel="stylesheet" href="src/css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="src/fontawesome-free-5.12.1-web/css/all.css">
    <link rel="stylesheet" href="src/js/DataTable/datatables.min.css">
    <link rel="stylesheet" href="src/js/Chartjs/Chart.css">
    <link rel="stylesheet" href="src/js/Vue/vue-select.css">
    <link rel="stylesheet" href="src/js/Toastr/toastr.css">
    <link rel="stylesheet" href="src/js/select2/select2.css">
    <link rel="stylesheet" type="text/css" href="src/js/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="src/js/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="src/js/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="src/js/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="src/js/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="src/js/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="src/css/starRating/star-rating.min.css" />
    <link href="src/js/Vue/componentesExternos/vue-tags/dist/vue-tags-input.css" rel="stylesheet">
    <link rel="stylesheet" href="src/css/cssImportados/autocomplete.css" />
    <link rel="stylesheet" href="src/css/cssImportados/vueslider.css">
    <!-- Importações internas -->
    <link rel="stylesheet" href="src/css/EstiloGeral.css">
    <link rel="stylesheet" href="templates/css/template.css">

    

    <!-- Scripts externos  -->
    <script type="" src="src/js/Google/platform.js"></script>
    <script type="" src="src/js/bootstrap/jquery-3.3.1.js"></script>
    <script src="src/js/bootstrap/js/popper.js"></script>
    <script type="" src="src/js/bootstrap/bootstrap.js"></script>
    <script src="src/fontawesome-free-5.12.1-web/js/all.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script> -->
    <script src="src/js/Chartjs/Chart.js"></script>
    <script src="src/js/select2/select2.min.js"></script>
    <script src="src/js/Toastr/toastr.min.js"></script>
    <script src="src/js/DataTable/datatables.min.js"></script>
    <script src="src/js/jquery-validation/dist/jquery.validate.js"></script>
    <script src="src/js/Vue/vue.js"></script>
    <script src="src/js/Vue/vue-select.js"></script>
    <!-- <script src="src/js/Vue/axios.min.js"></script> -->
    <script src="src/js/jquery-mask/jquery.mask.js"></script>
    <script src="src/js/Classes/Classes.js"></script>
    <script src="src/js/Vue/componentesExternos/vue-tags/dist/vue-tags-input.min.js"></script>
    <script src="src/js/Geral.js"></script>

    <script src="src/js/animsition/js/animsition.min.js"></script>
    <script src="src/js/daterangepicker/moment.min.js"></script>
    <script src="src/js/daterangepicker/daterangepicker.js"></script>
    <script src="src/js/countdowntime/countdowntime.js"></script>
    <script src="src/js/jquery-ui/jquery-ui.js"></script>

    <script src="src/js/jsImportados/VueStarRating.umd.min.js"></script> <!-- css star rating -->
    <script src="src/js/jsImportados/VueAdvancedCropper.umd.js"></script> <!-- CROP IMAGE -->
    <script src="src/js/jsImportados/Autocomplete.min.js"></script>
    <script src="src/js/jsImportados/VueSliderComponent.umd.min.js"></script>
    
    
    <!-- Importações de fonte -->
    <!-- EXTERNAS -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> <!-- Google Sans -->
    <!-- INTERNAS -->


    <link rel="stylesheet" type="text/css" href="src/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="sr/fonts/iconic/css/material-design-iconic-font.min.css">
    <meta name="google-signin-client_id" content="448269089190-5muphtulletjvi0t9b4prmmj98id15l7.apps.googleusercontent.com">
</head>

<script src="src/js/Vue/components.js"></script>

<body>

    <div id="loading" hidden>
        <div>
            <i class="fas fa-wrench licon"></i>
            <p>Carregando....</p>
        </div>
    </div>
    <div id="wrapperBody" class="col p-0" style="height: 100%;">
        <div id="app" style="height: fit-content; min-height: 100%;" class="d-flex flex-column">
            <?php require Logado()[0] ? 'templates/headerLogado.php' : 'templates/header.php' ?>
            <?php require load()[0]; ?>
            <?php
            if (load()[1] != "Login" && load()[1] != "Chat" && (load()[2] == true && BuscaSecaoValor("NIVELUSUARIO") != 2)) {
                echo "<div class='restApp'></div>";
                require 'templates/footer.php';
            }
            ?>
        </div>
    </div>
</body>
<script type="text/javascript">
    var Mixin = {
        data: {
            dataVue
        }
    }
    var app = new Vue({
        el: '#app',
        delimiters: ["{{", "}}"],
        mixins: [Mixin],
        components: {
            'vueSlider': window[ 'vue-slider-component' ],
            // 'select2': Select2,
            // 'star-rating': VueStarRating.default 
            // 'tags-input':  VueTagsInput
            // 'wm-notify': WMNotify,
            // 'wm-loading': LoadingComponent,
            // 'item-servico': ItemServico,
            // 'wm-container': WMContainer,
            // 'wm-input': WM_Input,
            // 'wm-input-cpf': WM_InputCpf,
            // 'wm-checkbox': WM_CheckBox,
            // 'wm-select': WM_Select,
            // 'v-select': VSELECT,
            // 'wm-user-img': WMUSERIMG,
            // 'wm-image-upload': WM_ImageUpload,
            // 'tiposervicoItem': WMTIPOSERVICOITEM,
            // 'wm-lista': WMList,
            // 'wm-percent': WMPercent,
            // 'wm-textarea': WM_TextArea,
            // 'wm-projeto': WM_NovoProjeto,
            // 'wm-home-item': HomeItem,
            // 'wm-projeto-item': WmProjetoItem,
            // 'wm-modal': WmModal,
            // 'wm-paginacao': Wm_Paginacao,
            // 'wm-error': WM_Error,
            // 'wm-image-viewer': WM_IMAGEVIEWER,
            // 'wm-chat': WMCHAT,
            // "wm-chart": WMChart,
            // 'wm-input-mask':WMINPUTMASK,
        },
        methods: {
            Redirect(page) {
                Rediredionar(page);
            },
            RedirectComParan(page, paran = [], AbreOutraAba = false) {
                RedirecionarComParametros(page, paran, AbreOutraAba);
            },
            RedirecionaPerfil(idUsuario) {
                if (idUsuario !== undefined)
                    RedirecionarComParametros('perfilUsuario', [{
                        chave: 'id',
                        valor: idUsuario
                    }], true);
                else
                    MostraMensagem("Algo deu errado.", ToastType.WARN, "Abrir Perfil");
            },
            async GetUsuarioDeContexto() {
                return {
                    NIVEL_USUARIO: await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO),
                    Email: await GetSessaoPHP(SESSOESPHP.EMAIL),
                    Foto: await GetSessaoPHP(SESSOESPHP.FOTO_USUARIO),
                    Nome: await GetSessaoPHP(SESSOESPHP.NOME),
                    id: await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO),
                    id_funcionario: await GetSessaoPHP(SESSOESPHP.IDFUNCIONARIOCONTEXTO)
                }
            },
            async AtualizaUsuarioContexto() {
                this.dataVue.UsuarioContexto.Nome = await GetSessaoPHP(SESSOESPHP.NOME);
                this.dataVue.UsuarioContexto.id = await GetSessaoPHP(SESSOESPHP.IDUSUARIOCONTEXTO);
                this.dataVue.UsuarioContexto.NIVEL_USUARIO = await GetSessaoPHP(SESSOESPHP.NIVEL_USUARIO);
                this.dataVue.UsuarioContexto.Email = await GetSessaoPHP(SESSOESPHP.EMAIL);
                this.dataVue.UsuarioContexto.Foto = await GetSessaoPHP(SESSOESPHP.FOTO_USUARIO);
                this.dataVue.UsuarioContexto.id_funcionario = await GetSessaoPHP(SESSOESPHP.IDFUNCIONARIOCONTEXTO);
            }
        },
        async beforeMount() {
            await this.AtualizaUsuarioContexto();
        },
        watch: {
            'dataVue': function(val) {}

        },
        
    });


    // console.clear();
</script>




</html>