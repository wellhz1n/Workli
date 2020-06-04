<link rel="stylesheet" href="pages/home/itemFuncionamento/style.css">

<?php 
    function itemFuncionamento($titulo, $explicacao, $img) {
        return "
            <div class='itemFuncionamento'>
                <img class='imgItemFuncionamento' src='src/img/home/$img.svg' />
                <span class='tituloFuncionamento'>$titulo</span>
                <div class='explicacaoFuncionamento'>$explicacao</div>
            </div>
        
        
        ";
    }
?>

<script type="" src="pages/home/itemFuncionamento/script.js"></script>