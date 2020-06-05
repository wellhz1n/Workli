<script type="application/javascript" src="pages/clienthome/script.js"></script>
<link rel="stylesheet" type="text/css" href="pages/clienthome/estilo.css" />
<div class="col-12">
    <h1>Bem Vindo Cliente: <?php echo BuscaSecaoValor(SecoesEnum::NOME) ?>
        <div class="row mx-2">
            <div @click="dataVue.imgClick(item)" v-for="item in dataVue.img" class="mr-2 imgViewerContainer">
                <div class="imageViewerOverflow d-flex justify-content-center flex-column align-items-center">
                    <i class="fas fa-eye"></i>
                    <p class="font_Poopins" style="font-size: 18px;">Visualizar</p>
                </div>
                <img class="imgViewerImg" :src="item" />
            </div>

        </div>
        <wm-modal id="modalpai" :visivel="dataVue.modalVisivelController" :callback="()=>{dataVue.modalVisivelController = false}">
            <template v-slot:header>
                <div style="height: 50px;" class="d-flex justify-content-center align-items-center">
                    <p class="mx-5 my-2">Visualizar Imagem</p>
                </div>
            </template>
            <template v-slot:body>
                <div class="imgViewerModalBody">
                    <img :src="dataVue.imgselecionada"/>
                </div>
            </template>
            <template v-slot:footer>
                <div>

                </div>
            </template>
        </wm-modal>




</div>
<script type="application/javascript">
    $("#Titulo").text("Home-Cliete")
</script>