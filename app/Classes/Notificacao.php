<?php
@require_once("../Enums/TipoNotificacaoEnum.php");



class Notificacao
{
    public $id = -1;
    public $descricao;
    public $titulo;
    public $id_projeto;
    public $id_chat;
    public $id_usuario;
    public $id_usuario_criacao;
    public $data_hora;
    public $tipo = TipoNotificacaoEnum::DEFAULT;
    public $visto;

    public function __construct(
        $id = -1,
        $descricao = "",
        $titulo = "",
        $id_projeto = null,
        $id_chat = null,
        $id_usuario = null,
        $id_usuario_criacao = null,
        $data_hora = null,
        $tipo = TipoNotificacaoEnum::
        DEFAULT,
        $visto = 0
    ) {

        $this->id =  $id;
        $this->descricao =   $descricao;
        $this->titulo = $titulo;
        $this->id_projeto =  $id_projeto;
        $this->id_chat = $id_chat;
        $this->id_usuario = $id_usuario;
        $this->id_usuario_criacao = $id_usuario_criacao;
        $this->data_hora = $data_hora;
        $this->tipo = $tipo;
        $this->visto = $visto;
    }
}
