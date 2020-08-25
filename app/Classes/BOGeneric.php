<?php
@require_once('../functions/Session.php');
@require_once('../Enums/SituacaoEnum.php');

class BOGeneric
{
    private $ID_USUARIOCONTEXTO;
    public function __construct()
    {
        $this->ID_USUARIOCONTEXTO = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
    }
    public function GetUsuarioContexto()
    {
        $this->ID_USUARIOCONTEXTO = BuscaSecaoValor(SecoesEnum::IDUSUARIOCONTEXTO);
        return $this->ID_USUARIOCONTEXTO;
    }
}
