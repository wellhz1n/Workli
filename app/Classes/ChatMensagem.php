<?php
class ChatMensagem
{
    public $id_chat_mensagem = -1;
    public $id_chat = -1;
    public $msg = null;
    public $id_usuario_remetente = -1;
    public $id_usuario_destinatario = -1;
    public $date = null;
    public $time = null;
    public $visualizado = 0;
    public $automatica = 0;
    public function __construct(
        $id_chat_mensagem = -1,
        $id_chat = -1,
        $msg = null,
        $id_usuario_remetente = -1,
        $id_usuario_destinatario = -1,
        $date = null,
        $time = null,
        $visualizado = 0,
        $automatica = 0
    ) {
        $this->id_chat_mensagem = $id_chat_mensagem;
        $this->id_chat = $id_chat;
        $this->msg = $msg;
        $this->id_usuario_remetente = $id_usuario_remetente;
        $this->id_usuario_destinatario = $id_usuario_destinatario;
        $this->date = $date;
        $this->time = $time;
        $this->visualizado = $visualizado;
        $this->automatica = $automatica;
    }
}
