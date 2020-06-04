<link rel="stylesheet" href="pages/home/itemVantagem/style.css">

<?php 
    function itemVantagem($titulo, $explicacao, $img) {
        return "
            <div class='itemVantagem'>
                <div class='wrapperTituloVantagem'>
                    <img class='imgItemVantagem' src='src/img/home/$img.svg' />
                    <span class='tituloVantagem'>$titulo</span>
                </div>
                <div class='explicacaoVantagem'>$explicacao</div>
            </div>
        ";
    }
?>

<script type="" src="pages/home/itemVantagem/script.js"></script>