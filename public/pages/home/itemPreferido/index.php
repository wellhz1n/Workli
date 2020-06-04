<link rel="stylesheet" href="pages/home/itemPreferido/style.css">

<?php 
    function itemPreferido($titulo, $img) {
        return "
            <div class='itemPreferido'>
                <span class='tituloPreferido'>$titulo</span>
                <div class='linhaSeparadora'></div>
                <div class='imgWrapper'>
                    <img class='imgItemPreferido' src='data:image/jpg;base64,$img' />
                </div>
            </div>
        ";
    }
?>

<script src="pages/home/itemPreferido/script.js"></script>