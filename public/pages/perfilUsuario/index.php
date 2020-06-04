<!-- LINKS INTERNOS -->
<link rel="stylesheet" href="pages/perfilUsuario/style.css">

<?php
require "pages/perfilUsuario/componenteTexto/index.php";
?>


<div class="justify-content-center text-center m-0">
    <div class="row justify-content-center text-center">
        <div id="imgcontainer">

            <div id="maskEditImg" class="editimgbox my-4 ml-2 " hidden>
                <i class=" fas fa-edit pl-3" style="font-size: 50px;color:#fff" aria-hidden></i>
            </div>
            <wm-user-img :img="dataVue.Usuario.imagem" />
        </div>
    </div>
    <div class="row">
        <span class="col-12" id="bemVindo">Bem vindo, <?php echo $_SESSION[SecoesEnum::NOME] ?></span>
    </div>

    <?php
    if($_SESSION[SecoesEnum::NIVEL_USUARIO] == 1) {
        echo "
        <div class='rating large star-icon value-3 half color-ok label-right' id='avaliacao'>
            <div class='label-value'>
                3
            </div>
            <div class='star-container'>
                <div class='star'>
                    <i class='star-empty'></i>
                    <i class='star-half'></i>
                    <i class='star-filled'></i>
                </div>
                <div class='star'>
                    <i class='star-empty'></i>
                    <i class='star-half'></i>
                    <i class='star-filled'></i>
                </div>
                <div class='star'>
                    <i class='star-empty'></i>
                    <i class='star-half'></i>
                    <i class='star-filled'></i>
                </div>
                <div class='star'>
                    <i class='star-empty'></i>
                    <i class='star-half'></i>
                    <i class='star-filled'></i>
                </div>
                <div class='star'>
                    <i class='star-empty'></i>
                    <i class='star-half'></i>
                    <i class='star-filled'></i>
                </div>
            </div>
        </div>    
        ";
    }

    echo componenteTexto("Nome", $_SESSION[SecoesEnum::NOME]);

    if ($_SESSION[SecoesEnum::NIVEL_USUARIO] != 2) { //Campos que só aparecem se for cliente ou funcionário
        // echo componenteTexto("Cpf", $_SESSION[SecoesEnum::CPF]);
    }

    if($_SESSION[SecoesEnum::NIVEL_USUARIO] == 1) { 
        echo componenteTexto("Currículo", $_SESSION[SecoesEnum::CURRICULO]); // Deixar Curriculo sem acento para não bugar. TODO: Consertar este problema
        echo componenteTexto("Telefone", $_SESSION[SecoesEnum::NUMERO_TELEFONE]);
        echo componenteTexto("Avaliação Média", $_SESSION[SecoesEnum::AVALIACAO_MEDIA]);
    }
    ?>

</div>

<!-- Modal -->
<div class="modal fade" id="EditImgModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditImgModalLabel">Editar Foto de Perfil</h5>
                <button type="button" id="FechaModal" class="close"  aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div id="imgcontainerModal">

                            <div id="maskEditImgModal" class="editimgbox my-4 ml-2 " hidden>
                                <i class=" fas fa-file-upload pl-3" style="font-size: 50px;color:#fff" aria-hidden></i>
                            </div>
                            <wm-user-img :img="dataVue.Usuario.imgTemp == null? dataVue.Usuario.imagem : dataVue.Usuario.imgTemp" />
                        </div>
                    </div>
                    <div class="col-4 my-5" style="font-size: 10px">
                        <p>Para uma melhor experiencia busque imagens com as dimensões quadradas.</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="SalvarImg" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script type="" src="pages/perfilUsuario/script.js"></script>