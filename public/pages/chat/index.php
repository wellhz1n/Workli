<div class="col-12 p-0 m-0">
    <div class="row p-0 m-0">
        <div style="height: 89vh" id="barlateral" class="col-3 m-0 p-0 ">
            <div class="categories_box h-100 d-flex flex-column justify-content-center align-items-center ">
                <div class="IconeMenuBar">
                    <i class="fas fa-chevron-right"></i>
                </div>
                <div class="scroolChatProjeto">
                    <div :class="['ItemProjetoChat',i == 2?'selecionado':null]" v-for="i in 10">
                        <div class="ImageContainerChatItem">
                            <div class="Dados">
                                <div class="UsuarioProjeto">
                                    <wm-user-img :img="null" class="imagemUsuario" style="margin-bottom: -10px;" class_icone="iconeImagemNull" class_imagem="imagemTamanhoUser"></wm-user-img>
                                    <p class="font_Poopins text-white" style="font-size: 14px;margin: 0px;">Robertão</p>
                                </div>
                                <div class="textos">
                                    <h3 style="font-size: 20px;" class="font_Poopins_B text-white">Criar Tcc Para o Robertão</h3>
                                    <p class="font_Poopins text-white" style="font-size: 14px;margin: 0px;"><i class="far fa-clock text-white"></i> 10 Dias Atrás</p>
                                    <p class="font_Poopins text-white" style="font-size: 14px;margin: 0px;"><i class="far fa-comments"></i> 1</p>
                                </div>
                            </div>
                            <img src="src/img/background/background.png" alt="" class="ImagemItemProjeto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="height: 89vh" class="col m-0 p-0 " id="CHAT">
            <div class="categories_box h-100 d-flex flex-column justify-content-center align-items-center ">
                <h5 class="m-0" style="font-family: Poppins-Bold;">Aqui Vai Ser o Chat</h5>

            </div>
        </div>
    </div>
    <link rel="stylesheet" type="text/css" href="pages/chat/estilo.css" />
    <script src="pages/chat/script.js"></script>