<?php
exec("node ../Sincronizador/src/index.js", $output); 
foreach ($output as $key => $value) {
    echo  $value;
    echo  "<br>";

}