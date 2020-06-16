<?php
@require_once("../Classes/ChatMensagem.php");
@require_once("../DAO/ChatDAO.php");
@require_once("../functions/Conexao.php");

try {

    if (isset($_POST['metodo']) && !empty($_POST['metodo'])) {
        $metodo = $_POST['metodo'];
        $CHATBO = new ChatBO();

        if ($metodo == "NovaMensagem") {
            if ((isset($_POST['MENSAGEM']) && !empty($_POST['MENSAGEM']))
                && (isset($_POST['ID_SERVICO']) && !empty($_POST['ID_SERVICO']))
            ) {
                $nv = (object) $_POST['MENSAGEM'];
                $tmpMsg = new ChatMensagem();
                foreach ($tmpMsg as $key => $value) {
                    if (isset($nv->$key))
                        $tmpMsg->$key = $nv->$key;
                }
                echo $CHATBO->NovaMensagem($tmpMsg, $_POST['ID_SERVICO']);
            } else
                throw new Exception("Função Nova Mensagem Necessita dos Seguintes Parametrôs [MENSAGEM,ID_SERVICO]");
        }
        if ($metodo == "GetChatPorServico") {
            if (isset($_POST['ID_SERVICO']) && !empty($_POST['ID_SERVICO'])) {
                echo $CHATBO->GetChatPorIdServico($_POST['ID_SERVICO']);
            } else
                throw new Exception("Função GetChatPorServico Necessita dos Seguintes Parametrôs [ID_SERVICO]");
        }
        if ($metodo == "GetMensagensProjeto") {
            if ((isset($_POST['ID_CHAT']) && !empty($_POST['ID_CHAT'])) &&
            (isset($_POST['ID_USUARIO1']) && !empty($_POST['ID_USUARIO1']))&&
            (isset($_POST['ID_USUARIO2']) && !empty($_POST['ID_USUARIO2']))) {
                echo json_encode($CHATBO->GetMensagensProjeto($_POST['ID_CHAT'],$_POST['ID_USUARIO1'],$_POST['ID_USUARIO2']),JSON_UNESCAPED_UNICODE);
            } else
                throw new Exception("Função GetMensagensProjeto Necessita dos Seguintes Parametrôs [ID_CHAT,ID_USUARIO1,ID_USUARIO2]");
        }
    }
} catch (Throwable $ex) {
    $msg = new stdClass();
    $msg->error = $ex->getMessage();
    echo json_encode($msg);
}

class ChatBO
{
    private $ChatDAO;
    public function __construct()
    {
        $this->ChatDAO = new ChatDAO();
    }
    #region Mensagens CRUD
    public function NovaMensagem(ChatMensagem $NVMensagem, $id_servico)
    {
        if ($NVMensagem->id_chat == -1) {
            $NVMensagem->id_chat = GetNextID("chat");
            if (!$this->NovoChat($NVMensagem->id_chat, $id_servico))
                throw new Exception("Ocorreu um Erro ao Adicionar o Chat");
        }
        if ($NVMensagem->id_chat_mensagem == -1)
            $NVMensagem->id_chat_mensagem = GetNextID("chat_mensagens");

        $NVMensagem->msg = addslashes($NVMensagem->msg);
        return $this->ChatDAO->NovaMensagem($NVMensagem) ? "OK|" . $NVMensagem->id_chat : false;
    }
    public function GetMensagensProjeto($id_chat, $id_usuario1, $id_usuario2)
    {
        $lista = $this->ChatDAO->GetMensagensProjeto($id_chat, $id_usuario1, $id_usuario2);
        for ($i=0; $i < count($lista) ; $i++) { 
            $lista[$i]["msg"] = stripslashes($lista[$i]["msg"]);
        }
        return $lista; 
    }
    #endregion
    #region CHAT
    public function GetChatPorIdServico($id_servico)
    {
        $lista = $this->ChatDAO->GetChatPorServico($id_servico);
        return count($lista) > 0 ? $lista[0]["id_chat"] : -1;
    }
    public function NovoChat($id_chat, $id_servico)
    {
        return $this->ChatDAO->NovoChat($id_chat, $id_servico);
    }
    #endregion
}
