<?php
@require_once("../Classes/ChatMensagem.php");
@require_once("../DAO/ChatDAO.php");
try {

    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
    }
} catch (Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}
