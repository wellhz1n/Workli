<?php
$config = file_get_contents('../STARTUP.json', true);
$config = json_decode($config, true);
?>
<div id="footer">
    <div class="textoFooterWrapper">
        <div class="usuariosRegistrados corCinza px-5">
            <span class="numeroFooter" id="numeroFooterUsers"></span> Usuários Registrados
        </div>
        <div class="servicosPublicados corCinza px-5">
            <span class="numeroFooter" id="numeroFooterServices"></span> Serviços Publicados
        </div>

        <div class="copyright px-5">
            © 2020 Conserta Serviços Tercerizados todos os direitos reservados
        </div>
        <div class="linhaFooterWrapper">
            <div class="linhaFooter">
            </div>
        </div>
        <p class="font_Poopins" style="font-size: 14px;font-style: italic;opacity: 0.6;"><?php echo $config["versao"] ?></p>
    </div>
</div>
<script>
    //#region FOOTER

    $(document).ready(async () => {
        
    });

        
    

    //#endregion
</script>