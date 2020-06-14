<script type="application/javascript" src="pages/clienthome/script.js"></script>
<link rel="stylesheet" type="text/css" href="pages/clienthome/estilo.css" />
<div class="col-12">
    <!-- <h1>Bem Vindo Cliente: <?php echo BuscaSecaoValor(SecoesEnum::NOME) ?>
        <div class="row mx-2">
            <div @click="dataVue.imgClick(item)" v-for="item in dataVue.img" class="mr-2 imgViewerContainer">
                <div class="imageViewerOverflow d-flex justify-content-center align-items-center flex-column ">
                    <i style="font-size: 18px;" class="fas fa-eye mt-1"></i>
                    <p class="font_Poopins" style="font-size: 17px;">Visualizar</p>
                </div>
                <img class="imgViewerImg" :src="item" />
            </div>

        </div>
        <wm-modal height="650px" id="modalpai" :visivel="dataVue.modalVisivelController" :callback="()=>{dataVue.modalVisivelController = false}">
            <template v-slot:header>
                <div style="height: 50px;" class="d-flex justify-content-center align-items-center">
                    <p class="mx-5 my-2">Visualizar Imagem</p>
                </div>
            </template>
            <template v-slot:body>
                <div class="imgViewerModalBody p-2">
                    <div class="mx-1">
                        <i style="color: #218838;" class="fas fa-arrow-circle-left"></i>
                    </div>
                    <div>
                        <img class="imgViewerModalImage" :src="dataVue.imgselecionada" />
                        <div class="ImageBalls">
                            <div v-for="item in dataVue.img" :class="['imgball',item == dataVue.imgselecionada?'selected':'']"></div>

                        </div>
                    </div>
                    <div class="m-1">
                        <i style="color: #218838;" class="fas fa-arrow-circle-right"></i>
                    </div>

                </div>
            </template>
            <template v-slot:footer>
                <div>

                </div>
            </template>
        </wm-modal> -->
        <wm-chat heigth="300px" :userpropostaimage="dataVue.ftboot" :mensagens="dataVue.msg" :idusuariodestinatario="2"></wm-chat>
<!-- <wm-image-viewer :imgs="dataVue.img"></wm-image-viewer> -->

</div>
<script type="application/javascript">
    $("#Titulo").text("Home-Cliete")
</script>