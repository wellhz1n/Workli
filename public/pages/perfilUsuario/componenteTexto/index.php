<link rel="stylesheet" href="pages/perfilUsuario/componenteTexto/style.css">

<?php 
    $contadorComponentes = 0;
    function componenteTexto($texto, $enum) {
        GLOBAL $contadorComponentes;
        $contadorComponentes++;

        $substituir = array("ç", "ã", "í", "é");
        $para = array("c", "a", "i", "e");

        $nomeCampoMinusculo = str_replace($substituir, $para, $texto);
        $nomeCampoMinusculo = explode(" ", $nomeCampoMinusculo);
        $nomeCampoMinusculo = strtolower($nomeCampoMinusculo[0]) . (isset($nomeCampoMinusculo[1]) ? ucfirst($nomeCampoMinusculo[1]) : ""); 

        return "
        <div class='row text-left'>
            <div class='col-4'></div>
            <span class='col-4 campoTitulo campoGeralCima'>$texto</span>
            <div class='col-4'></div>

            <div class='col-4'></div>
            <span class='col-4 campoValor campoGeral'>
                <input value='$enum' type='text' class='inputCampoPerfil $nomeCampoMinusculo' id='contadorInput$contadorComponentes' name='$nomeCampoMinusculo' disabled> 
                <div class='botaoEditar' style='cursor:pointer !important'>
                    <i class='fas fa-edit' aria-hidden='true' id='iconeEditar$contadorComponentes' onclick='mudaACorAoEditar(`$contadorComponentes`)'></i>
                 </div>
            </span>
            <div class='col-4'></div>

            <div class='col-4'></div>
            <div class='col-4 campoGeralBaixo'>
                <div class='linhaDeBaixo' id='contador$contadorComponentes'></div>
            </div> 
            <div class='col-4'></div>
        </div>";
    }
?>

<script type="" src="pages/perfilUsuario/componenteTexto/script.js"></script>