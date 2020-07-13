<?php
@include("../functions/EnumUtils.php");
@include("../Enums/SituacaoEnum.php");

try {
    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $Seletores =  new Seletores();
        if ($metodo == "GetSituacaoSeletor")
            $Seletores->GetSituacaoSelect();
    }
} catch (Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}

class Seletores
{

    public function GetSituacaoSelect()
    {
        $nivelArr = EnumParaArray(SituacaoEnum::class);
        $nivelIconeArr = EnumParaArray(IconeSituacao::class);
        $resultado = array();
        foreach ($nivelArr as $key => $value) {
            $cl = new stdClass;
            $cl->id = $value;
            $cl->nome = str_replace("_", " ", $key);
            $cl->icone = $nivelIconeArr[$key];
            array_push($resultado, $cl);
        }
        echo json_encode($resultado);
    }
}
