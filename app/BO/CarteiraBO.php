<?php
@require_once("../Classes/BOGeneric.php");
@require_once("../Enums/SecoesEnum.php");
@require_once("../functions/Conexao.php");
@require_once("../functions/Session.php");
@require_once("../DAO/CarteiraDAO.php");


#region AJAX
try {

    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $CarteiraBO = new CarteiraBO();
        if ($metodo == "DeduzirCarteira") {
            $valor = $_POST["VALOR"];
            echo json_encode($CarteiraBO->DeduzirCarteira($valor));
        }
    }
} catch (Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}
#endregion
class CarteiraBO extends BOGeneric
{
    public function DeduzirCarteira($valor = null)
    {
        if ($valor != null) {

            $valor = json_decode($valor);
            $valorEmCarteira =   $this->usuarioDAO->GetValorUsuarioCarteira($this->GetIdFuncionarioCOntexto());
            if ($valor > $valorEmCarteira) {
            }
        }
    }
}
