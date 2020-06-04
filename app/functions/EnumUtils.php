<?php

 function EnumParaArray($ENUM) {
    $oClass = new ReflectionClass($ENUM);
    $constants = $oClass->getConstants();
    return(array_combine(array_keys($constants),array_values($constants)));
}
function EnumParaObjJavaScript($ENUM){
    $class = new stdClass;
    $en = EnumParaArray($ENUM);
    foreach ($en as $key => $value) {
            $class->$key = $value;
    }
    return $class;
}