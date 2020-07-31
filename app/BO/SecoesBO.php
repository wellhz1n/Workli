<?php
include("../functions/Session.php");

try {   
    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        if($metodo == "GetSecoes"){
            echo BuscaSecaoValor($_POST['sessao']);
        }
        if($metodo == "SetSecoes"){
            $s = CriaSecao($_POST['sessao'],json_encode($_POST['valor']));
            echo $s;
        }
    } else
    echo null;
} catch (\Throwable $th) {
}
