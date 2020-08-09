<?php

@require_once("../Classes/Notificacao.php");
@require_once("../DAO/NotificacoesDAO.php");
@require_once("../functions/Conexao.php");
try {

    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $NotificacaoBO = new NotificacoesBO();
        if($metodo == "Teste"){
            $NotificacaoBO->Teste();
        }
     
    }
} catch (Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}

class NotificacoesBO
{
    private $NotificacoesDAO;
    private $Notificacao;
    function __construct()
    {
        $this->Notificacao = new Notificacao();
        $this->NotificacoesDAO = new NotificacoesDAO();
    }
    public function Teste(){
        $this->NotificacoesDAO->Teste();
    }
}